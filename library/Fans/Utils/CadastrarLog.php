<?php
/**
 * Utilitário para salvar informações referentes a logs em um arquivo específico.
 * -- Não trabalha com tabelas. Logs visiveis apenas para monitoramento dos fatos, sem vinculo direto com tabelas
 * -- Recomenda-se uma função estática para cada função pública
 *
 * @category   Zend
 * @package    Sedf
 * @subpackage Utils
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Fans_Utils_CadastrarLog
{
    /**
     * Função que irá utilizar informações do perfil do usuário para criar log
     * -- Não faz validações e já inclui informações de matrícula e nome do usuário
     *
     * @param string $tipo warn|info
     * @param string $acao Descrição do ato sem maiores detalhes
     */
    public static function salvar($tipo, $acao)
    {
        $self           = new self();
        $identity       = Zend_Auth::getInstance()->getIdentity();

        $self->_saveFile($tipo, $acao, $identity);
    }


    /**
     * Salva informações diretamente em um arquivo de log
     *
     * @param string $tipo warn|info
     * @param string $acao Descrição do ato sem maiores detalhes
     * @param object $identity
     */
    private function _saveFile($tipo, $acao, $identity)
    {
        try {
            $writer         = new Zend_Log_Writer_Stream(APPLICATION_PATH .'/../data/log/'. date("Ym") .'.txt');
            $logger         = new Zend_Log($writer);
            $matricula      = isset($identity->samaccountname) ? $identity->samaccountname : '000000';

            if ($tipo === 'info') {
                $logger->info("[{$matricula}] " . str_replace("\n", "<br />", $acao));

            } else {
                $logger->warn("[{$matricula}] " . str_replace("\n", "<br />", $acao));
            }

        } catch (Exception $exc) {
            return false;
        }
    }


    /**
     * Salva informações diretamente em um arquivo de log
     *
     * @param string $tipo warn|info
     * @param string $acao Descrição do ato sem maiores detalhes
     * @param object $identity
     */
    private function _saveDbAction($tipo, $acao, $identity)
    {
        try {
            //Instânciando os itens necessários
            $config     = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
            if (class_exists('SQLite3')) {
                $db         = new SQLite3($config->resources->db->params->dbname);
                $matricula  = isset($identity->samaccountname) ? $identity->samaccountname : '000000';
                $columnMap  = array(
                    'priority'  => $tipo,
                    'message'   => "[{$matricula}] " . str_replace("\n", "<br />", htmlspecialchars($acao)),
                    'timestamp' => date("Y-m-d") .' '. date("H:i:s"),
                    'username'  => $matricula);
                $db->query('INSERT INTO "log_action" ("'. implode('","',array_keys($columnMap)) .'") VALUES ("'. implode('","', array_values($columnMap)) .'")');

            } else {
                return false;
            }

        } catch (Exception $exc) {
            return false;
        }
    }


    /**
     * Salva as informações de um determinado arquivo.
     * Garantia de Log em todos os arquivos enviados para o servidor.
     *
     * @param array $data
     * @return array
     */
    public static function _saveDbFile($identity, $realDir, $nomeArquivo)
    {
        $self   = new self();
        $hash   = sha1_file(realpath($realDir).DIRECTORY_SEPARATOR.$nomeArquivo);

        try {
            if (!empty($hash)) {
                $data['txt_nome']       = $nomeArquivo;
                $data['txt_hash']       = $hash;
                $data['txt_username']   = isset($identity->samaccountname) ? $identity->samaccountname : '000000';
                $data['nu_versao']      = '1';
                $data['dt_registro']    = date("Y-m-d H:i:s");

                $tblLogArquivo = new Application_Model_DbTable_Dodf_LogArquivo();
                $row = $tblLogArquivo->createRow($data);
                $id  = $row->save();

                $result = array('type'      => 'success',
                                'result'    => true);
            } else {
                $self->_saveFile('warning', ' tentou salvar as informações na base de dados, porém, não foi possível gerar um hash do arquivo', $identity);
                $result = array('type'      => 'erro',
                                'result'    => false,
                                'flashMsg'  => "O arquivo '{$nomeArquivo}' por algum motivo não conseguiu produzir um hash de segurança e foi removido.");
            }

        } catch (Exception $exc) {
            $self->_saveFile('warning', ' houve um erro interno e não foi possível salvar as informações na base de dados.', $identity);
            $result = array('type'      => 'erro',
                            'result'    => false,
                            'flashMsg'  => "Devido um erro interno não foi possível enviar o arquivo '{$nomeArquivo}' para o servidor.");
        }

        return $result;
    }

}