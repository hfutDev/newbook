<?php

    /**
    *   连接person数据表基本class
    */
    class Application_Model_DbTable_Person extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'person';
    	protected $_primary = 'per_id';
    }
?>