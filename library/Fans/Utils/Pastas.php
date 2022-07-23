<?php

/**
 * Utilitário para itens relacionados a pastas do sistema.
 * -- Não envolve arquivos
 * -- Recomenda-se uma função estática para cada função pública 
 *
 * @category   Zend
 * @package    Sedf
 * @subpackage Utils
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Fans_Utils_Pastas
{
    
    /**
     * Função que irá criar pastas inexistentes, conforme um array enviado previamente para o sistema
     * 
     * @param type $arrDir
     * @param type $inicialDir
     */
    public function criarPastasInexistentes($arrDir, $inicialDir='') 
    {
        try {
            $dirPosterior = $inicialDir;

            foreach ($arrDir as $dirAtual) {
                if (!file_exists($dirPosterior.$dirAtual)) {
                    @mkdir($dirPosterior.$dirAtual, 0777);
                }
                $dirPosterior .= $dirAtual.'/';
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    
    /**
     * Função estática da _criarPastasInexistentes()
     * 
     * @param type $arrDir
     * @param type $inicialDir
     */
    static function criarPastasInexistentesS($arrDir, $inicialDir) 
    {
        $pastas = new Fans_Utils_Pastas();
        $pastas->criarPastasInexistentesS($arrDir, $inicialDir);
    }
}