<?php

    /**
    *   连接sale数据表基本class
    */
    class Application_Model_DbTable_Sale extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'sale';
    	protected $_primary = 'sale_id';
    }
?>