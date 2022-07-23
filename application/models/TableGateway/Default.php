<?php

/**
 * Responsável pelo contato direto com a base de dados na área de Acesso de funcionários devidamente registrados.
 *
 * @category   Zend
 * @package    Application
 * @subpackage Model.Mapper
 * @author     Bruno da Costa Monteiro <brunodacostamonteiro@gmail.com>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Application_Model_TableGateway_Default
{
    /**
     * Padrão para salvar informações de um item específico na base de dados
     * -- Se houver informação na chave primária edita, senão cadastra
     * 
     * @param array $data
     * @param string $primaryKey
     * @param string $tbl nome da tabela que será utilizada
     * @param string $schema schema que será utilizado
     * @return array
     */
    public static function salvarItem($data, $primaryKey, $tbl, $schema='Entregas')
    {
        $class      = "Application_Model_DbTable_{$schema}_{$tbl}";
        $tblData    = new $class();
        $tpSalvar   = empty($data[$primaryKey]) ? 'cadastrar' : 'editar';

        try {
            if ($tpSalvar === 'cadastrar') {
                unset($data[$primaryKey]);//garantia de que é um novo registro
                $row        = $tblData->createRow($data);
                $coData = $row->save();

            } elseif ($tpSalvar === 'editar') {
                $row = $tblData->find($data[$primaryKey])->current();
                $row->setFromArray($data);
                $row->save();
            }

            $result = array('type'      => 'success',
                            'result'    => true,
                            'redirect'  => '',
                            'id'        => $tpSalvar === 'cadastrar' ? $coData : $data[$primaryKey],
                            'flashMsg'  => 'Informação '. ($tpSalvar === 'cadastrar' ? 'cadastrada' : 'editada') .' com sucesso!');

        } catch (Exception $exc) {
            Fans_Utils_CadastrarLog::salvar('warn', " tentou salvar informações da tabela {$class}! Erro: {$exc->getMessage()}");
            $result = array('type'      => 'erro',
                            'result'    => false,
                            'flashMsg'  => 'Houve um erro interno e não foi possível salvar suas informações...');
        }

        return $result;
    }


    /**
     * Função que ativa ou desativa um determinado item da base de dados
     *   --
     *
     * @return boolean
     *
     * @param int|string $coData código do item que será desativado
     * @param bool $ativar verificar se é para desativar ou ativar o item informado
     * @param string $key item da tabela que será utilizado como localizador
     * @param string $tbl nome da tabela que será utilizada
     * @param string $schema schema que será utilizado
     * @return array
     */
    public static function modificarStatusItem($coData, $ativar, $key, $tbl, $schema='Entregas')
    {
        try {
            $class      = "Application_Model_DbTable_{$schema}_{$tbl}";
            $tblData    = new $class();
            $data['st_ativo']   = $ativar === true ? 'S' : 'N';
            $where              = array((isset($key['pk']) ? $key['pk'] : $key) ." = ?" => (int)$coData);
            $tblData->update($data, $where);

            Fans_Utils_CadastrarLog::salvar('info', " desativou um item da tabela {$class}. #{$coData}!");
            $result = array('type'      => 'success',
                            'result'    => true,
                            'redirect'  => '',
                            'flashMsg'  => 'Item removido com sucesso!');

        } catch (Exception $exc) {
            Fans_Utils_CadastrarLog::salvar('warn', " tentou desativar um item da tabela {$class}. #{$coData}! Erro: {$exc->getMessage()}");
            $result = array('type'      => 'erro',
                            'result'    => false,
                            'flashMsg'  => 'Houve um erro interno ao remover o formulário informado');
        }

        return $result;
    }


    /**
     * Lista informações de uma determinada tabela
     *
     * @param array $filters
     * @param string $tbl
     * @param string $schema
     * @return array
     */
    public static function listarItens($filters=array(), $tbl='', $schema='Entregas')
    {
        $class      = "Application_Model_DbTable_{$schema}_{$tbl}";
        $tblData    = new $class();
        $result     = array();
        
        try {
            $lstData = $tblData->fetchAll($filters, array(2) );

            if (count($lstData) >= 1) {
                $result = $lstData->toArray();
            }
        } catch (Exception $exc) {
            Fans_Utils_CadastrarLog::salvar('warn', " tentou listar informações da tabela {$class}! Erro: {$exc->getMessage()}");
            $result = array();
        }

        return $result;
    }


    /**
     * Retorna as informações completas de um determinado item de tabela
     *     -- Retorna o resultado de um fetchRow
     *
     * @param array $filters Exemplo array('co_agrupador = ?' => $coAgrupador)
     * @param string $tbl
     * @param string $schema
     * @return array
     */
    public static function exibirInformacoes($filters=array(), $tbl='', $schema='Entregas')
    {
        $class      = "Application_Model_DbTable_{$schema}_{$tbl}";
        $tblData    = new $class();
        $result     = array();

        try {
            $lstData = $tblData->fetchRow($filters);

            if (count($lstData) >= 1) {
                $result = $lstData->toArray();
            }
        } catch (Exception $exc) {
            Fans_Utils_CadastrarLog::salvar('warn', " tentou listar informações da tabela {$class}! Erro: {$exc->getMessage()}");
            $result = array();
        }

        return $result;
    }


    /**
     * Função que lista informações para serem exibidas em input:select de formulários
     *
     * @param array $whereQuery (exemplo: "conv.ds_nome_completo LIKE ?' => '%'.$term.'%'")
     * @param array $fields
     * @param string $order
     * @param string $tbl
     * @param string $schema
     * @return array
     */
    public static function completeInputDefault($whereQuery, $fields, $order, $tbl='', $schema='Entregas')
    {
        try {
            $result     = array();
            $class      = "Application_Model_DbTable_{$schema}_{$tbl}";
            $tblData    = new $class();
            $slcData    = $tblData->select()->distinct();
            $slcData->from(array('a' => strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $tbl))), $fields, 'db_sis_'.lcfirst($schema) );

            if (!empty($whereQuery)) {
                foreach ($whereQuery as $key => $value) {
                    $slcData->where($key, $value);
                }
            }
            if (!empty($order)) {
                $slcData->order($order);
            }
            //echo $slcData;exit;
            $data = $tblData->fetchAll($slcData);

            // Tornando o resultado legível para widget autocomplete
            if (count($data) >= 1) {
                $result = array_column($data->toArray(), $fields[1], $fields[0]);
            }
        } catch (Exception $exc) {
            Fans_Utils_CadastrarLog::salvar('warn', " tentou retornar informações para um autocomplete! Erro: {$exc->getMessage()}");
        }

        return $result;
    }


    /**
     * Função que lista informações para serem exibidas em autocomplete
     *
     * @param array $whereQuery (exemplo: "conv.ds_nome_completo LIKE ?', '%'.$term.'%'")
     * @param array $fields
     * @param string $order
     * @param string $tbl
     * @param string $schema
     * @return array
     */
    public static function completeDefault($whereQuery, $fields, $order, $tbl='', $schema='Patrimon')
    {
        try {
            $result     = array();
            $class      = "Application_Model_DbTable_{$schema}_{$tbl}";
            $tblData    = new $class();
            $slcData    = $tblData->select()->distinct();
            $slcData->from(array('a' => strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $tbl))), $fields, 'db_sis_'.lcfirst($schema) )
                        ->where($whereQuery)
                        ->order($order)
                        ->limit(15);
            $data = $tblData->fetchAll($slcData);

            // Tornando o resultado legível para widget autocomplete
            if (count($data) >= 1) {
                $result = $data;
            }
        } catch (Exception $exc) {
            Fans_Utils_CadastrarLog::salvar('warn', " tentou retornar informações para um autocomplete! Erro: {$exc->getMessage()}");
        }

        return $result;
    }
}
