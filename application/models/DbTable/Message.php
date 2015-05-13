<?php

    /**
    *   连接message数据表基本class
    */
    class Application_Model_DbTable_Message extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'message';
    	protected $_primary = 'mes_id';
    }
?>