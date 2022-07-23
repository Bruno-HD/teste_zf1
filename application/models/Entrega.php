<?php

/**
 *
 * @category   Zend
 * @subpackage Model
 * @author     Bruno da Costa Monteiro <brunodacostamonteiro@gmail.com>
 * @license    BSD License
 * @version    GIT: $Id$ development.
 */
class Application_Model_Entrega
{
    
    /**
     *
     * @var Application_Model_TableGateway_Entrega
     */
    public $mapperEntrega;
    

    public function __construct()
    {
        $this->mapperEntrega   = new Application_Model_TableGateway_Entrega();
    }
    
    
    /**
     * retorna os patrimônios com base nos filtros especificados
     * 
     * @param array $filters
     * @param int $pagina
     * @return array
     */
    public function listar($filters=array(), $pagina=0) 
    {
        $lstData = $this->mapperEntrega->listar($filters, $pagina);
        
        $paginator = Zend_Paginator::factory( range(0, $lstData['count']) );
        $paginator->setItemCountPerPage(50)
               ->setPageRange(10)
               ->setCurrentPageNumber($pagina);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('_pagination.phtml');

        return (object)array_merge(array(
            'paginator' => $paginator, 
            'lstEntregas' => $lstData['data'], 
            'count' => $lstData['count'])
        );
    }
    
    /**
     * 
     * @param int $coEntrega
     * @return array
     */
    public function informacoesPorId($coEntrega) 
    {
        $infoData = $this->mapperEntrega->informacoesPorId($coEntrega);
        
        return $infoData;
    }
    
    
    /**
     * 
     * @param array $data
     * @return array
     */
    public function salvar($data)
    {
        // Início da transação
        Zend_Db_Table_Abstract::getDefaultAdapter()->beginTransaction();

        $result = Application_Model_TableGateway_Default::salvarItem($data, 'co_entrega', 'TbEntrega');

        if ($result['type'] == 'success') {
            Zend_Db_Table_Abstract::getDefaultAdapter()->commit();
            Fans_Utils_CadastrarLog::salvar('info', " Salvou as informações da entrega #{$result['co_entrega']}");

            $result = array('type'      => 'success',
                            'result'    => true,
                            'coEntrega' => $result['co_entrega'],
                            'flashMsg'  => 'Entrega salva com sucesso!');
        } else {
            Zend_Db_Table_Abstract::getDefaultAdapter()->rollBack();
        }

        return $result;
    }
    
    /**
     * 
     * @param int $coEntrega
     * @return array
     */
    public function desativar($coEntrega) 
    {
        $result = Application_Model_TableGateway_Default::modificarStatusItem($coEntrega, 'N', 'co_entrega', 'TbEntrega');
        
        return $result;
    }    
}
