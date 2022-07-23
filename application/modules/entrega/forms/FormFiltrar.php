<?php

/**
 * @category   Zend
 * @package    Entrega
 * @subpackage Forms
 * @author     Bruno da Costa Monteiro <brunodacostamonteiro@gmail.com>
 * @license    BSD License
 * @version    GIT: $Id$ development.
 */
class Entrega_Form_FormFiltrar extends Twitter_Bootstrap_Form_Vertical
{

    public function init()
    {
        $count      = 0;

        $this->setIsArray(true)
             ->setElementsBelongTo('')
             ->setAction('/entrega/gestao/listar')
             ->setName('formFiltrarEntrega')
             ->setAttrib('id', 'formFiltrarEntrega')
             ->setMethod('post');


        //-- APRESENTAÇÃO: Grupo1 ---------------------------------------//
        $this->addElement('text', 'ds_titulo', array(
            'label'         => 'Título:',
            'class'         => "form-control input-md",
            'required'      => true,
            'maxlength'     => '255',
            'filters'       => array(array('StripTags'), array('StringTrim')),
            'validators'    => array(array('StringLength', false, array(0,255)))
        ));
        $this->addDisplayGroup(array('ds_titulo'), (++$count).'group_12', array());


        //-- Botão de filtrar informações -----------------------------------------//
        $this->addElement('button', 'btnFiltrar', array(
            'buttonType'        => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS,
            'class'             => 'pull-right margin-top-40 margin-bottom40',
            'icon'              => 'ok',
            'type'              => 'submit',
            'label'             => 'Buscar informações',
            'data-loading-text' => 'Enviando...'
        ));
    }


}