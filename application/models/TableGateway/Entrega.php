<?php

/**
 *
 * @category   Zend
 * @package    Admin
 * @subpackage Model
 * @author     Bruno da Costa Monteiro <brunodacostamonteiro@gmail.com>
 * @license    BSD License
 */
class Application_Model_TableGateway_Entrega
{

    /**
     * INIT
     */
    public function __construct()
    {   
    }


    /**
     *
     * @param array $filters
     * @param int $pagina
     * @return array
     */
    public function listar($filters, $pagina)
    {
        try {
            $result         = array( 'data' => array(), 'count' => 0 );
            $itemCountPage  = 50;

            $tblData = new Application_Model_DbTable_Entregas_TbEntrega();
            $slcData = $tblData->select()->distinct();
            $slcData->from(array('a' => 'tb_entrega'), array('*'))
                    ->where("a.st_ativo = ? ", "S")
                    ->order("a.co_entrega ASC");
            
            if (!empty($filters)) {
                foreach ($filters as $key => $value) {
                    if (!empty(trim($value))) {
                        $slcData->where($key .' like ?', "%".$value."%");
                    
                    }
                }
            }
            
            $DbPaginator = new Zend_Paginator_Adapter_DbTableSelect($slcData);
            if ($DbPaginator->count() >= 1) {
                $result['count']    = $DbPaginator->count();
                $result['data']     = $DbPaginator->getItems(($pagina > 0 ? $pagina-1 : 0)*$itemCountPage, $itemCountPage)->toArray();
            }

        } catch (Exception $exc) {
            print_r($exc); die();

            Fans_Utils_CadastrarLog::salvar('warn', " tentou filtrar o resultado de uma consulta! Erro: {$exc->getMessage()}");
            $result = array('type'      => 'erro',
                            'result'    => false,
                            'flashMsg'  => 'Houve um problema interno e não foi possível retornar a resultado de sua busca. Tente novamente mais tarde');
        }

        return $result;
    }  
    
    
    /**
     * 
     * @param int $coEntrega
     * @return array
     */
    public function informacoesPorId($coEntrega) 
    {
        try {
            $result  = array();
            $tblData = new Application_Model_DbTable_Entregas_TbEntrega();
            $slcData = $tblData->select()->distinct();
            $slcData->from(array('a' => 'tb_entrega'), array('*'))
                    ->where("a.st_ativo = ? ", "S")
                    ->where('a.co_entrega = ?', (int)$coEntrega);
            
            $infoData = $tblData->fetchRow($slcData);

            if (count((array)$infoData) >= 1) {
                $result = $infoData->toArray();
            }
        } catch (Exception $exc) {
            Fans_Utils_CadastrarLog::salvar('warn', " tentou filtrar o resultado de uma consulta! Erro: {$exc->getMessage()}");
            $result = array('type'      => 'erro',
                            'result'    => false,
                            'flashMsg'  => 'Houve um problema interno e não foi possível retornar a resultado de sua busca. Tente novamente mais tarde');
        }

        return $result;                   
    }
}