<?php

    /**
    * 操作profession表的具体方法
    */
    class Application_Model_CategoryMapper
    {

    	protected $category;
        protected $categoryDb;
        protected $global;
    	
    	function __construct()
    	{
    		$this->category = new Application_Model_DbTable_category();
            $this->categoryDb = $this->category->getAdapter();
            $this->global = new Application_Model_Global();
    	}

        /**
         * 取出所有信息
         */
    	public function findAll()
    	{
    		$res = $this->category->fetchAll()->toArray();
            $res = $this->global->addUrl($res, 'category');
    		return $res;
    	}

        public function findOne($id)
        {
            $where = $this->categoryDb->quoteInto('cat_id = ?', $id);
            $res = $this->category->fetchRow($where);
            if ($res) {
                $res = $res->toArray();
            }
            return $res;
        }
    }
?>