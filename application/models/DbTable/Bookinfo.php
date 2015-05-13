<?php

    /**
    *   连接bookinfo数据表基本class
    */
    class Application_Model_DbTable_Bookinfo extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'bookinfo';
    	protected $_primary = 'book_id';
    }
?>