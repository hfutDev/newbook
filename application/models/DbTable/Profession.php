<?php

    /**
    *   连接profession数据表基本class
    */
    class Application_Model_DbTable_Profession extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'profession';
    	protected $_primary = 'pro_id';
    }
?>