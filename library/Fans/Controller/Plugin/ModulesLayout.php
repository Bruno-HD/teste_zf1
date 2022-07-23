<?php

/**
 * Esta classe visa tornar o sistema de troca de layouts mais dinâmica. Sempre que houver troca de módulos é importante
 * que haja também troca de layout.
 *
 * Por se tratar de um plugin ele irá verificar em todos os acessos qual é o módulo específico do usuário e tentar encontrar
 * o layout padrão para este módulo, caso não encontre o sistema irá utilizar o layout do módulo Default.
 *
 * @category   Zend
 * @package    Fans
 * @subpackage Plugin
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Fans_Controller_Plugin_ModulesLayout extends Zend_Controller_Plugin_Abstract
{

    /**
     * Função que irá utilizar o request do usuário para saber qual layout irá utilizar;
     * Somente irá mudar o layout caso o arquivo do módulo exista!
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $layout = Zend_Layout::getMvcInstance();

        // Verifica se o módulo é diferente do padrão
        if ($module !== 'default') {//Senão utilizar application.ini
            $layoutPath = APPLICATION_PATH. "/modules/". $module ."/layouts/scripts/";
            $layoutName = $module;

            // check if module layout exists else use default
            if(file_exists($layoutPath.$layoutName.'.phtml')) {
                $layout->setLayoutPath($layoutPath);
                $layout->setLayout($layoutName);
            } 
        }
    }
    
}