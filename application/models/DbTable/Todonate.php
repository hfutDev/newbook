<?php

    /**
    *  连接todonate数据表基本class
    */
    class Application_Model_DbTable_Todonate extends Zend_Db_Table_Abstract
    {
    	protected $_name = 'todonate';
    	protected $_primary = 'todonate_id';
    }

?>