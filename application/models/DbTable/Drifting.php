<?php

    /**
    *  连接drifting数据表基本class
    */
    class Application_Model_DbTable_Drifting extends Zend_Db_Table_Abstract
    {
    	protected $_name = 'drifting';
    	protected $_primary = 'drift_id';
    }

?>