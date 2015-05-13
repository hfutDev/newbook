<?php

    /**
    *  连接admin数据表基本class
    */
    class Application_Model_DbTable_Admin extends Zend_Db_Table_Abstract
    {
    	protected $_name = 'admin';
    	protected $_primary = 'admin_id';
    }

?>