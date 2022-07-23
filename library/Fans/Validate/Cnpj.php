<?php

/**
 * Arquivo models/validate/Cpf.php
 */
class Fans_Validate_Cnpj extends Zend_Validate_Abstract 
{
    const CNPJ_INVALIDO = "cnpj_invalido";

    /**
     * Possíveis mensagens de erros que o sistema poderá emitir
     *
     * @var array
     */    
    protected $_messageTemplates = array(self::CNPJ_INVALIDO => "CNPJ Inválido");

    /**
     * Defined by Zend_Validate_Interface
     *
     * retorna true se o valor do CNPJ informado for um valor válido
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value) 
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $value);

        // Valida tamanho
        if (strlen($cnpj) != 14) {
            $this->_error(self::CNPJ_INVALIDO);
            return false;
        }

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;

        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto)) {
            $this->_error(self::CNPJ_INVALIDO);
            return false;
        }

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;

        if ($cnpj{13} == ($resto < 2 ? 0 : 11 - $resto)) {
            return true;
        } else {
            $this->_error(self::CNPJ_INVALIDO);
            return false;
        }
    }

}
