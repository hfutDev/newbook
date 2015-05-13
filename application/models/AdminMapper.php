<?php

    /**
    *  数据表admin的基本操作
    */
    class Application_Model_AdminMapper 
    {

    	protected $admin;
    	protected $adminDb;
    	
    	function __construct()
    	{
    		$this->admin = new Application_Model_DbTable_Admin();
    		$this->adminDb = $this->admin->getAdapter();
    	}

    	/**
    	 * 列出管理员信息
    	 * @return [two-dim array]         
    	 */
    	public function listAll()
    	{
    		$order = "admin_id";
    		$res = $this->admin->fetchAll();
    		return $res;
    	}

    	/**
    	 * 通过用户名查询管理员信息
    	 * @param  [string] $name 管理员姓名
    	 * @return [one-dim array]     
    	 */
    	public function findOne($name, $pass)
    	{
    		$where = $this->adminDb->quoteInto('name = ?', $name);
    		$where .= $this->adminDb->quoteInto(' AND pass = ?', $pass);
    		$res = $this->admin->fetchRow($where);
    		if ($res) {
    			$res = $res->toArray();
    		}
    		return $res;
    	}

    	/**
    	 * 增加管理员
    	 * @param [string] $name   姓名
    	 * @param [string] $pass   密码
    	 * @param [num] $degree    数据表id值
    	 */
    	public function addOne($name, $pass, $degree)
    	{
    		$data = array('name' => $name, 'pass' => $pass, 'degree' =>$degree);
    		$res = $this->admin->insert($data);
    		return $res;
    	}

    	/**
    	 * 删除用户管理员
    	 * @param  [num] $id 管理员id
    	 * @return [num]     被删除管理员的id
    	 */
    	public function delOne($id)
    	{
    		$where = $this->adminDb->quoteInto('admin_id = ?', $id);
    		$res = $this->admin->delete($where);
    		return $res;
    	}
    }

?>