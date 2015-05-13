<?php

    /**
    *   连接college数据表基本class
    */
    class Application_Model_DbTable_Category extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'category';
    	protected $_primary = 'cat_id';
    }
?>