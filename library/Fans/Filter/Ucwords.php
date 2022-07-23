<?php

/**
 * Classe que retorna nome de Pessoas, empresas etc em palavras que lembram o padrão de nomes brasileiro.
 *
 * Ex.: $value = "BRUNO DA COSTA MONTEIRO";
 *      Fans_Filter_Ucwords->filtrar($value) ---> Bruno da Costa Monteiro
 *
 * @category   Zend
 * @package    Sedf
 * @subpackage Filter
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Fans_Filter_Ucwords implements Zend_Filter_Interface
{

    /**
     * Função que irá retornar uma string formatada no padrão de nomes brasileiros
     * -- preposições não serão transformados em ucfirst();
     *
     * @param type $value
     * @return type
     */
    public function filter($value)
    {
        $value = mb_strtolower($value, 'UTF-8');
        $minusculas = array('da', 'de', 'do', 'das', 'des', 'dos', 'e');
        
        $value = explode(' ', $value);
        foreach ($value as $chave => $palavra) {
            if (((int)$chave == 0) or (!in_array($palavra, $minusculas))) {
                $inicial = mb_strtoupper(substr($palavra, 0, 1), 'UTF-8');
                
                //-- Correção de erros na acentuação das primeiras palavras...
                if (strlen($inicial) == 0) {
                    $inicial = mb_strtoupper(substr($palavra, 0, 2), 'UTF-8');
                }
                
                $valor[$chave] = trim($inicial . mb_strtolower(substr($palavra, 1), 'UTF-8'));
            } else {
                $valor[$chave] = $palavra;
            }
        }
        
        return implode(' ', $valor); 
    }
    

    /**
     * Função estática de filter
     *
     * @param string $value
     * @return string
     */
    static function filtrar($value)
    {
        $filtro = new self();
        return $filtro->filter($value);
    }
    
}