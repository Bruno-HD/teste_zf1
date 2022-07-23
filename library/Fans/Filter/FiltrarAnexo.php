<?php

/**
 * Classe que garantirá o retorno de uma string formatada, para o nome de um arquivo que será salvo no servidor
 *
 * @category   Zend
 * @package    Sedf
 * @subpackage Filter
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Fans_Filter_FiltrarAnexo implements Zend_Filter_Interface
{

    /**
     * Função que irá filtrar o nome do arquivo para que seja visível pelos navegadores
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        $extensao   = pathinfo($value, PATHINFO_EXTENSION);
        $value      = substr($value, 0, strlen($value) - (strlen($extensao)+1) );
        
        if (strlen($value) == 4) {
            $value = 'Sem_Nome';                      # Evitar que o arquivo fique sem nome
        }
        
        //-- Removendo acentos...
        $rm_ace = new Fans_Filter_RemoverAcentos();
        $value  = $rm_ace->filter($value);        
        
        //-- removendo todos os outros caracteres especiais que podem existir...
        $value  = str_replace("áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC ", $value);
        $value  = str_replace('%20', '_', $value);		# Corrige problemas no nome
        $value  = str_replace('th_', 'th', $value);             # Corrige problemas no nome
        $value  = str_replace('@', '', $value);                 # Corrige problemas no nome
        $value  = str_replace('#', '', $value);                 # Corrige problemas no nome
        $value  = str_replace('$', '', $value);                 # Corrige problemas no nome
        $value  = str_replace('^', '', $value);                 # Corrige problemas no nome
        $value  = str_replace('&', '', $value);                 # Corrige problemas no nome
        $value  = str_replace(';', '', $value);                 # Corrige problemas no nome
        $value  = str_replace('*', '', $value);                 # Corrige problemas no nome
        $value  = str_replace('(', '_', $value);                # Corrige problemas no nome
        $value  = str_replace(')', '_', $value);                # Corrige problemas no nome
        $value  = str_replace('%', '', $value);                 # Corrige problemas no nome
        $value  = str_replace(' ', '_',$value);                 # Corrige problemas no nome
        $value  = str_replace('.', '_', $value);                # Corrige problemas no nome
        
        return $value.'.'.$extensao;
    }


    /**
     * Função estática de filter
     *
     * @param string $value
     * @return string
     */
    public static function filtro($value)
    {
        $Anexo = new self();
        return $Anexo->filter($value);
    }
}