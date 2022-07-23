<?php

/**
 * Classe responsável por garantir a integridade do banco ao tratar de informações que não usaram o Zend_Db
 *
 * @category   Zend
 * @package    Sedf
 * @subpackage Filter
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Fans_Filter_AntiInjection implements Zend_Filter_Interface
{

    /**
     * Função que irá filtrar a string para evitar SQL-Injection
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
      /*-- Alterações antes do filtro ---------------*/
        $value = str_replace('#', '&#35;', $value);		# Evitar que o # atrapalhe no sistema	
        $value = str_replace('\'', '&#39;', $value);		# Evitar que o ' atrapalhe no sistema
        $value = str_replace('\"', '&#34;', $value);		# Evitar que o " atrapalhe no sistema		

      /*-- Filtro do Sistema --------------*/		
        $value = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/", "", $value);
        $value = trim($value);        				# Remove espaços vazios do início e do fim
        $value = strip_tags($value);  				# Remove tags HTML e PHP.
        $value = addslashes($value);  				# Adiciona barras invertidas à uma string.
        if (strnatcmp(phpversion(),'7.0.0') >= 0 || !function_exists('mysql_escape_string')) {
            $value = stripslashes($value);
        } else {
            $value = mysql_escape_string($value);
        }

      /*-- Alterações pós filtro --------------*/
        $value = str_replace(',', '&#44;', $value);             # Evitar que a ,  atrapalhe no sistema
        $value = str_replace('&39;', '&#39;', $value);		# Evitar que o ' atrapalhe no sistema
        $value = str_replace('&35;', '&#35;', $value);		# Evitar que o # atrapalhe no sistema				
        $value = str_replace('&34;', '&#34;', $value);		# Evitar que o " atrapalhe no sistema

        return $value; 
    }


    /**
     * Função estática de filter
     *
     * @param string $value
     * @return string
     */
    public static function filtro($value)
    {
        $filtro = new self();
        return $filtro->filter($value);
    }
}