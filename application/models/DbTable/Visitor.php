<?php

    /**
    *   连接visitor数据表基本class
    */
    class Application_Model_DbTable_Visitor extends Zend_Db_Table_Abstract
    { 	
    	protected $_name = 'visitor';
    	protected $_primary = 'vis_id';
    }
?>