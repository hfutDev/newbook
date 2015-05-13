<?php

    /**
    * 操作profession表的具体方法
    */
    class Application_Model_CollegeMapper
    {

    	protected $college;
        protected $collegeDb;
        protected $global;
    	
    	function __construct()
    	{
    		$this->college = new Application_Model_DbTable_College();
            $this->collegeDb = $this->college->getAdapter();
            $this->global = new Application_Model_Global();
    	}

        /**
         * 取出所有信息
         */
    	public function findAll()
    	{
    		$res = $this->college->fetchAll()->toArray();
            $res = $this->global->addUrl($res, 'college');
    		return $res;
    	}

        public function findOne($id)
        {
            $where = $this->collegeDb->quoteInto('col_id = ?', $id);
            $res = $this->college->fetchRow($where);
            if ($res) {
                $res = $res->toArray();
            }
            return $res;
        }
    }
?>