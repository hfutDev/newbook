<?php

    /**
    *   连接feedback数据表基本class
    */
    class Application_Model_DbTable_Feedback extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'feedback';
    	protected $_primary = 'fb_id';
    }
?>