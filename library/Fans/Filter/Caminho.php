<?php

/**
 * Classe responsável por garantir a integridade do banco e dos arquivos, permitindo que os mesmos tenham nomes
 * acessíveis pelo navegador. Também servirá para padronizar os nomes de pastas e arquivos.
 *
 * @category   Zend
 * @package    Sedf
 * @subpackage Filter
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Fans_Filter_Caminho //implements Zend_Filter_Interface
{

    /**
     * Função que irá retornar uma string formatada para nome de pastas, garantindo o acesso da mesma por
     * navegadores
     *
     * @param string $nome
     * @return string
     */
    public function filter($nome='/')
    {
        $Nomes = '';

        //-- Evita invasão por tentativa de voltar pastas.
        $nome = str_replace('.', '', $nome);

        //-- Evita o bug de //
        while(strpos($nome, '//') !== false) {
            $nome = str_replace('//', '/', $nome);
        }

        //-- Gera array para verificação unitária
        $verif_dir = explode('/', $nome);
        if (!empty ($verif_dir)) {
            foreach ($verif_dir as $pastas) {
                //$pastas = Fans_Filter_RemoverAcentos::filtrar($pastas);                 # Remove acentos
                $pastas = str_replace('-', 'Ç', $pastas);                               # Transforma traço em Ç (evitar sumir com o traço)
                $pastas = str_replace('_', 'é', $pastas);                               # Transforma underline em é (evitar sumir com o underline)
                $pastas = Zend_Filter::filterStatic($pastas, 'Alnum', array(true));     # Permitir apenas Letras-Numeros-Underline
                $pastas = str_replace('é', '_', $pastas);                               # Retornar o underline para o lugar
                $pastas = str_replace('Ç', '-', $pastas);                               # Retornar o traço para o lugar
                $pastas = substr($pastas, 0, 70);

                $Nomes .= $pastas .'/';
            }
        }

        return str_replace('//', '/', $Nomes ? $Nomes : '/');
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