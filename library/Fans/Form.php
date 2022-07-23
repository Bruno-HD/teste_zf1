<?php

/**
 * Esta classe faz com que o padrão do formulário seja table ao invés de DD e DD
 *
 * Esta classe remove os decorators anteriores e insere novos decorators e sua utilização é bem simples, basta extender
 * esta classe ao invés de extender Zend_Form em seu formulário.
 *
 * @category   Zend
 * @package    Sedf
 * @subpackage Form
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Fans_Form extends Zend_Form
{
    
    /**
     * Docorator principal do Zend_form, não precisa ser chamado nos elementos...
     * 
     * @var array $elementDecorators
     */
    protected $elementDecorators = array(
                                  'viewHelper',
                                  'Errors',
                                  array(array('data'=>'HtmlTag'),array('tag'=>'td', 'style'=>'vertical-align: top;')),
                                  array('Label',array('tag'=>'td', 'style'=>'font-weight: bold;')),
                                  array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
                                 );

    /*
    public $elementDecorators = array(
                                    'Label',
                                    array(array('labelTd'=>'HtmlTag'),
                                          array('tag'=>'td', 'class'=>'label_cell')),
                                    array(array('elemTdOpen'=>'HtmlTag'),
                                          array('tag'=>'td', 'openOnly'=>true,
                                                'class'=>'input_cell', 'placement'=>'append')),
                                    'ViewHelper',
                                    'Errors',
                                    array('Description', array('tag' => 'div')),
                                    array(array('elemTdClose'=>'HtmlTag'),
                                          array('tag'=>'td', 'closeOnly'=>true, 'placement'=>'append')),
                                    array(array('row' => 'HtmlTag'), array('tag' => 'tr')));
     */
    
    
    /**
     * Decorator para os inputs do tipo checkbox
     * 
     * @var array $checkboxDecorator
     */
    public $checkboxDecorator = array(
                                    'ViewHelper',
                                    'Errors',
                                    'Description',
                                    array('HtmlTag',array('tag' => 'td')),
                                    array('Label',array('tag' => 'td','class' =>'element')),
                                    array('Description', array('tag' => 'span')),
                                    array(array('row' => 'HtmlTag'), array('tag' => 'tr')));
 
    
    /**
     * Decorator para os inputs do tipo button
     * 
     * @var array $buttonDecorators
     */
    public $buttonDecorators = array(
                                    'ViewHelper',
                                    array('HtmlTag',array('tag' => 'td')),
                                    //array('Label',array('tag' => 'td')), NO LABELS FOR BUTTONS
                                    array(array('row' => 'HtmlTag'), array('tag' => 'tr')));
 
    
    /**
     * Array de informações que irá modificar o decorator principal
     * 
     * @var array $setDecorators
     */
    protected $setDecorators = array(
                                    'FormElements',
                                    array(array('data'=>'HtmlTag'),array('tag'  =>'table',
                                                                         'id'   =>'registro',
                                                                         'style'=>'width:500px; line-height: 25px;')),
                                    'Form'
        );    
    
    
    /**
     * Modifica o decorator após a geração do zend_form
     * 
     * @param Zend_Config $options
     */
    public function __construct($options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        } elseif ($options instanceof Zend_Config) {
            $this->setConfig($options);
        }

        // Extensions...
        $this->init();

        $this->loadDefaultDecorators();        
        
        // Sempre gerar um zend no formato de tabela.
        $this->setElementDecorators($this->elementDecorators);
        $this->setDecorators($this->setDecorators);
    }

}