<?php

    /**
    * 操作预约漂流书籍的所有方法
    */
    class Application_Model_TodriftMapper
    {
    	protected $todrift;
    	protected $todriftDb;
    	
    	function __construct()
    	{
    		$this->todrift = new Application_Model_DbTable_Todrift();
    		$this->todriftDb = $this->todrift->getAdapter();
    	}

    	/**
    	 * 查询漂流书籍查询预约信息
    	 * @param  [num] $id 漂流id
    	 * @return [two-dim array]     
    	 */
    	public function findByDrift($id)
    	{
    		$order = "time DESC";
    		$where = $this->todriftDb->quoteInto("d_id = ?", $id);
    		$res = $this->todrift->fetchAll($where, $order)->toArray();
    		return $res;
    	}

        /**
         * 列车所有预约信息，包括预约、借书
         * @param  string $offset 数据开始位置
         * @param  string $count  数据量
         * @param  string $type   预约类型
         * @return [two-dim array]        
         */
        public function listAll($offset='0', $count='6', $type='1')
        {
            $order = "time DESC";
            $where = $this->todriftDb->quoteInto("order_type = ?", $type);
            $res = $this->todrift->fetchAll($where, $order, $count, $offset)->toArray();
            return $res;
        }

        
        /**
         * 计算数据总量
         * @param  string $type 预约类型
         * @return [num] 总数
         */
        public function theSum($type='1')
        {
            $where = $this->todriftDb->quoteInto("order_type = ?", $type);
            $res = $this->todrift->fetchAll($where)->toArray();
            return count($res);
        }

    	/**
    	 * 增加预约信息
    	 * @param [num] $id   漂流书籍id
    	 * @param [string] $user  预约者
    	 * @param [string] $tel  电话号码
    	 */
    	public function addOne($user, $id, $tel, $type)
    	{
    		$date = new Zend_Date();
            $date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Y-m-d H:i:s');
            $data = array(
            	'user' => $user,
            	'd_id' => $id,
            	'tel' => $tel,
            	'time' => $time,
                'order_type' => $type
            	);
            $res = $this->todrift->insert($data);
            return $res;
    	}
    }
?>