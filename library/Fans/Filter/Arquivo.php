<?php

/**
 * Classe responsável por garantir o nome e a extensão do arquivo enviado.
 *
 * @category   Zend
 * @package    Sedf
 * @subpackage Filter
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Fans_Filter_Arquivo //implements Zend_Filter_Interface
{
    public function filter($value)
    {
        // Separa a extensão do nome
        $extensao   = substr($value, -4);

        // Verifica se o arquivo esta sem nome;
        if (strlen($value) == 4) {
            $value = 'Sem_Nome'.$extensao;
        }

        // Trata os possíveis erros no nome do arquivo.
        $value  = Fans_Filter_RemoverAcentos::filtrar($value);
        $value  = preg_replace('/[^A-Za-z0-9 ._-]+/', "", $value);
        $value  = str_replace('th_', 'th', $value);
        $value  = str_replace('.', '_', substr($value, 0, -4));

        return $value.$extensao;
    }

    static function filtrar($value)
    {
        $filter = new Fans_Filter_Arquivo();
        return $filter->filter($value);
    }
}