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
class Entrega_GestaoController extends Zend_Controller_Action
{
    /**
     *
     * @var Application_Model_Entrega
     */
    public $modEntrega;


    /**
     * INIT
     */
    public function init()
    {
        $this->modEntrega  = new Application_Model_Entrega();
    }


    public function listarAction() 
    {
        // Tratando informações
        $pagina         = (int)$this->getParam('pagina', 0);
        $filters        = $this->getAllParams();
        
        unset($filters['module'], $filters['controller'], $filters['action']);

        // Tratando formulário de filtros
        $formulario   = new Entrega_Form_FormFiltrar();
        $arrayFilters = $formulario->populate( $filters )->getValues();

        $lstData = $this->modEntrega->listar($arrayFilters, $pagina);

        $this->view->data                   = $lstData;
        $this->view->filters                = $filters;
        $this->view->formulario             = $formulario;
    }


    public function manterAction() 
    {
        //$this->_helper->layout->disableLayout();
        $formEntrega    = new Entrega_Form_FormManter();
        $post           = $this->getRequest()->getPost();
        $coEntrega      = (int)$this->_getParam('co_entrega');//Verificar edição

        if (!empty($post)) {
            if ($formEntrega->populate($post)->prepararFormulario()->isValid($post)) {
                $result = $this->modEntrega->salvar($formEntrega->getValues());
            } else {
                $result = array('type'      => 'erro',
                                'result'    => false,
                                'erros'     => $formEntrega->getMessages(),
                                'flashMsg'  => 'Alguma informação não foi preenchida corretamente.');
            }
            $this->_helper->json($result);//Exit

        } else {
            if (!empty($coEntrega)) {
                $infoEntrega     = $this->modEntrega->informacoesPorId( $coEntrega );
                $formEntrega->populate($infoEntrega);
                
                $this->view->infoPatrimonio     = $infoEntrega;
            }
        }

        $this->view->formulario         = $formEntrega;
        $this->view->coData             = $coEntrega;
        $this->view->config             = new Zend_Config_Ini('../application/configs/application.ini', APPLICATION_ENV);
    }

    public function removerAction() 
    {
        $coEntrega  = (int)$this->_getParam('co_entrega');
        $result     = array("type" => "erro", "flashMsg" => "cod não informado!");
        
        if (!empty($coEntrega)) {
            $result = $this->modEntrega->desativar($coEntrega);
        }
        
        $this->_helper->json($result);//Exit
    }
}
