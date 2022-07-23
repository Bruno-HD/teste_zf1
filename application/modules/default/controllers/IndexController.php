<?php

/**
 *
 * @category   Zend
 * @package    Application
 * @subpackage Controller
 * @author     Bruno da Costa Monteiro <brunodacostamonteiro@gmail.com>
 * @license    BSD License
 * @version    Release: 1.0
 */
class IndexController extends Zend_Controller_Action
{


    /**
     * INIT
     */
    public function init()
    {
        
    }


    //ACTION: Exibi uma página inicial
    public function indexAction()
    {
        $this->view->msgEntrada = "Sistema de teste para iTarget";
    }

}
