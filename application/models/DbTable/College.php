<?php

    /**
    *   连接college数据表基本class
    */
    class Application_Model_DbTable_College extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'college';
    	protected $_primary = 'col_id';
    }
?>