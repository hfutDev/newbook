<?php

    /**
    *   连接reply数据表基本class
    */
    class Application_Model_DbTable_Reply extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'reply';
    	protected $_primary = 'reply_id';
    }
?>