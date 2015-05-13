<?php

    /**
    *  连接donate数据表基本class
    */
    class Application_Model_DbTable_Donate extends Zend_Db_Table_Abstract
    {
    	protected $_name = 'donate';
    	protected $_primary = 'donate_id';
    }

?>