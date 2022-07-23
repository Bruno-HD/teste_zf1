<?php

/**
 * Classe que garantirá o retorno de uma string no formato informado
 *
 * @category   Zend
 * @package    Sedf
 * @subpackage Filter
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Zend_View_Helper_Mask extends Zend_View_Helper_HeadLink
{

    /**
     * 
     *
     * @param string $val
     * @param string $mask
     * @return string
     */
    public function mask($val, $mask) 
    {
        $maskared   = '';
        $k          = 0;
        
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#'){
                if(isset($val[$k])) {
                   $maskared .= $val[$k++];
                }
            } else {
                if(isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }
        
        return $maskared;
    }
}