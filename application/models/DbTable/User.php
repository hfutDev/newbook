<?php

    /**
    *   连接user数据表基本class
    */
    class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'user';
    	protected $_primary = 'user_id';
    }
?>