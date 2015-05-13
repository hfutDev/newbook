<?php

    /**
    *   连接label数据表基本class
    */
    class Application_Model_DbTable_Label extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'label';
    	protected $_primary = 'label_id';
    }
?>