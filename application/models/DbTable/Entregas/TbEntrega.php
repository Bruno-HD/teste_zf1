<?php

/**
 *
 * @category   Zend
 * @package    Default
 * @subpackage Model_DbTable
 * @author     Bruno da Costa Monteiro <Bruno da Costa Monteiro>
 * @license    BSD License
 * @version    Release: 1.0
 */
class Application_Model_DbTable_Entregas_TbEntrega extends Zend_Db_Table_Abstract
{
    
    protected $_name        = 'tb_entrega';
    protected $_primary     = 'co_entrega';
    protected $_cols        = array('co_entrega', 'ds_titulo', 'tx_descricao', 'dt_prazo_entrega', 'tp_entrega_concluida', 'st_ativo');
    // protected $_metadata    = array('id_entrega'            => array('DATA_TYPE' => 'INTEGER', 'PRIMARY' => TRUE),
    //                                 'ds_titulo'             => array('DATA_TYPE' => 'string'),
    //                                 'tx_descricao'          => array('DATA_TYPE' => 'string'),
    //                                 'dt_prazo_entrega'      => array('DATA_TYPE' => 'string'),
    //                                 'tp_entrega_concluida'  => array('DATA_TYPE' => 'string'),
    //                                 'st_ativo'              => array('DATA_TYPE' => 'string'));
    // protected $_sequence    = true;

}

