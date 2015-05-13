<?php

    /**
    *   连接buy数据表基本class
    */
    class Application_Model_DbTable_Buy extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'buy';
    	protected $_primary = 'buy_id';
    }
?>