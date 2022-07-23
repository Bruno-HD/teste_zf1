<?php

/**
 * @category   Zend
 * @package    Entrega
 * @subpackage Forms
 * @author     Bruno da Costa Monteiro <brunodacostamonteiro@gmail.com>
 * @license    BSD License
 * @version    GIT: $Id$ development.
 */
class Entrega_Form_FormManter extends Twitter_Bootstrap_Form_Vertical
{

    public function init()
    {
        $count      = 0;

        $this->setIsArray(true)
             ->setElementsBelongTo('')
             ->setAction('/entrega/gestao/manter')
             ->setName('formManterEntrega')
             ->setAttrib('id', 'formManterEntrega')
             ->setMethod('post');

        //-- ID, caso seja edição de conteúdo -----------------//
        $this->addElement('hidden', 'co_entrega', array('validators' => array('Int')));


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

        
        //-- APRESENTAÇÃO: Grupo2 ---------------------------------------//
        $this->addElement('textarea', 'tx_descricao', array(
            'label'         => 'Descrição:',
            'rows'          => '4',
            'class'         => "form-control input-sm",
            'required'      => true,
            'filters'       => array(array('StringTrim')),
        ));
        $this->addDisplayGroup(array('tx_descricao'), (++$count).'group_12', array());
      
        
        //-- APRESENTAÇÃO: Grupo3 ---------------------------------------//
        $this->addElement('text', 'dt_prazo_entrega', array(
            'label'         => 'Prazo de entrega:',
            'class'         => "form-control input-md",
            'required'      => true,
            'maxlength'     => '255',
            'filters'       => array(array('StripTags'), array('StringTrim')),
            'validators'    => array(array('StringLength', false, array(0,10)))
        ));
        $this->addElement('select', 'tp_entrega_concluida', array(
            'label'         => 'Entrega concluída?',
            'class'         => "form-control input-md",
            'required'      => true,
            'multiOptions'  => array(
                "S"   => "Sim",
                "N"   => "Não",
            )
        ));
        $this->addDisplayGroup(array('dt_prazo_entrega', 'tp_entrega_concluida'), (++$count).'group_6_6', array());



        //-- Botão de salvar informações -----------------------------------------//
        $this->addElement('button', 'btnEnviar', array(
            'buttonType'        => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS,
            'class'             => 'pull-right margin-top-40 margin-bottom40',
            'icon'              => 'ok',
            'type'              => 'button',
            'label'             => 'Salvar Informações',
            'data-loading-text' => 'Enviando...'
        ));
        $this->addDisplayGroup(array('btnEnviar'), (++$count).'group_12', array());
    }

    /**
     *
     */
    public function prepararFormulario()
    {
        return $this;
    }

}