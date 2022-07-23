<?php

/**
 * Classe responsável pela inicialização do Cache, da ACL e dos Módulos do sistema.
 *
 * @category   Zend
 * @package    Application
 * @subpackage Bootstrap
 * @author     Bruno da Costa Monteiro <brunodacostamonteiro@gmail.com>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Preapara o sistema para executar o cache para (paginator|dbTable|options|translate|locale)
     */
    protected function _initCache()
    {
        if (is_writable(APPLICATION_PATH . '/../data/cache')) {
            $frontendOptions = array(
                'lifetime'                  => 24*3600*365, //-- 365 Dias
                'automatic_serialization'   => true
            );

            $backendOptions = array(
                'cache_dir'                 => APPLICATION_PATH . '/../data/cache/',
                'read_control_type'         => 'adler32',
                'hashed_directory_level'    => 1
            );

            $cache = Zend_Cache::factory(
                'Core',                                         # Metodo utilizado
                'File',                                         # Como será gravado o cache
                $frontendOptions,                               # Pega o que foi passado no FrontEnd
                $backendOptions);                               # pega o que foi passado no BackEnd


//            Zend_Paginator::setCache($cache);                   # Cache do Zend_Paginator
            Zend_Db_Table::setDefaultMetadataCache($cache);     # Cache da tabela que será usada
//            Zend_Date::setOptions(array('cache' => $cache));    # Cache para o Zend_Date
            Zend_Translate::setCache($cache);                   # Cache para os Zend_Translate
            Zend_Locale::setCache($cache);                      # Cache para o Zend_Locale

            Zend_Registry::set('cache', $cache);                # Registra em $Cache o que foi e será gravado.
        }
    }


    /**
     * Função que irá criar os grupos de acesso e restrição do sistema...
     */
    protected function _initAcl()
    {
        
    }


    /**
     * Inicia as rotas existentes dos módulos inseridos nesta aplicação.
     *
     * @return \Zend_Application_Module_Autoloader
     */
    protected function _initModules()
    {
        $dir          = APPLICATION_PATH ."/modules/";
        $toCamelcase  = new Zend_Filter_Word_DashToCamelCase();

        foreach (new DirectoryIterator($dir) as $file) {
            if ($file->isDir()) {
                $basePath   = $file->getFilename();
                $namespace  = $file->getFilename() !== 'default' ? ucfirst($toCamelcase->filter($file->getFilename())) : 'Application';

                $autoloader = new Zend_Application_Module_Autoloader(array('namespace' => $namespace, 'basePath' => APPLICATION_PATH."/modules/".$basePath));
                Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/modules/'. $basePath .'/controllers/helpers');
            }
        }

        return $autoloader;
    }


    /**
     * Inicia as bibliotecas que serão utilizadas nesta aplicação
     */
    protected function _initAutoload()
    {
        Zend_Loader_Autoloader::getInstance()->registerNamespace('Fans_');
    }
}

