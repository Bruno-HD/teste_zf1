<?php

/**
 * Classe que garantirá o retorno de uma string formatada e sem acentuação
 *
 * @category   Zend
 * @package    Sedf
 * @subpackage Filter
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Fans_Filter_RemoverAcentos //implements Zend_Filter_Interface
{

    /**
     * Função que irá retornar uma string formatada sem acentos.
     *
     * @param string $value
     * @param string $enc
     * @return string
     */
    public function filter($value, $enc='UTF-8')
    {
        $acentos = array(
            'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
            'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
            'C' => '/&Ccedil;/',
            'c' => '/&ccedil;/',
            'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
            'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
            'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
            'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
            'N' => '/&Ntilde;/',
            'n' => '/&ntilde;/',
            'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
            'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
            'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
            'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
            'Y' => '/&Yacute;/',
            'y' => '/&yacute;|&yuml;/',
            'a.' => '/&ordf;/',
            'o.' => '/&ordm;/'
        );

        $palavra = preg_replace($acentos, array_keys($acentos), htmlentities($value,ENT_NOQUOTES, $enc));

        return $palavra;
    }


    /**
     * Função estática de filter
     *
     * @param string $value
     * @return string
     */
    public static function filtrar($value)
    {
        $filtro = new self();
        return $filtro->filter($value);
    }
}