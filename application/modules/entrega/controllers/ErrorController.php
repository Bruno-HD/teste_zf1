<?php

/**
 * Controller de erros do Zend Framework
 *
 * Caso o usuário acesse /acess/login ele será redirecionado para a index/index (que é o verdadeiro login do sistema.)
 *
 * @category   Zend
 * @package    Application
 * @subpackage Controller
 * @license    BSD License
 * @version    Release: 1.0
 */
class Entrega_ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Página não encontrada';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request   = $errors->request;
    }


    public function acessoNegadoAction()
    {
        $post = $this->getRequest()->getPost();

        if (empty($post)) {
            $formulario = new Admin_Form_Login();
            $this->view->formulario = $formulario;

        } else {
            $result = array(
                'type'      => 'erro',
                'result'    => false,
                'flashMsg'  => 'Você não esta mais logado no sistema ou não tem permissão para enviar o formulário. Tente fazer acessar novamente o sistema.'
            );

            $this->_helper->json($result);
        }
    }


    public function resourceNaoEncontradoAction()
    {
        //Mensagem de erro na view...
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}
