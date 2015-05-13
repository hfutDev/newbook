<?php

    /**
    * 操作捐书意向表的所有方法
    */
    class Application_Model_TodonateMapper
    {
    	protected $todonate;
    	protected $todonateDb;
    	
    	function __construct()
    	{
    		$this->todonate = new Application_Model_DbTable_Todonate();
    		$this->todonateDb = $this->todonate->getAdapter();
    	}

    	/**
    	 * 列出所有相同状态的捐赠意向信息
    	 * @param  string $offset 数据开始位置
    	 * @param  string $count  数据量
    	 * @param  string $state  捐赠处理状态
    	 * @return [two-dim array]        
    	 */
    	public function listToDonate($offset='0', $count='6', $state='2')
    	{
    		$order = "todonate_id DESC";
    		$where = $this->todonateDb->quoteInto("todonate_state = ?", $state);
    		$res = $this->todonate->fetchAll($where, $order, $count, $offset)->toArray();
    		return $res;
    	}

        /**
         * 计算数据总量
         * @param  string $state 捐赠信息处理状态
         * @return [num]
         */
        public function theSum($state='2')
        {
            $where = $this->todonateDb->quoteInto("todonate_state = ?", $state);
            $res = $this->todonate->fetchAll($where)->toArray();
            return count($res);
        }

    	/**
    	 * 增加捐书意向信息
    	 * @param string $name 捐书者
    	 * @param num $col   学院代号
    	 * @param string $tel  电话号码
    	 * @param string $book  书名
    	 */
    	public function addOne($name, $col, $tel, $book)
    	{
    		$date = new Zend_Date();
            $date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Y-m-d H:i:s');
            $data = array(
            	'td_name' => $name,
            	'college' => $col,
            	'tel' => $tel,
            	'book' => $book,
            	'time' => $time 
            	);
            $res = $this->todonate->insert($data);
            return $res;
    	}

        /**
         * 改变我要捐赠书籍信息登记处理状态
         * @param  [num] $id 等级记录id
         * @return [num]     数据表受影响的行数
         */
        public function updateState($id)
        {
            $where = $this->todonateDb->quoteInto('todonate_id = ?', $id);
            $data = array('todonate_state' => 1);
            $res = $this->todonate->update($data, $where);
            return $res;
        }

    	/**
    	 * 删除某条
    	 * @param  num $id 信息id
    	 * @return num    数据表受影响的行数
    	 */
    	public function delOne($id)
    	{
    		$where = $this->todonateDb->quoteInto('todonate_id = ?', $id);
    		$res = $this->todonate->delete($where);
    		return $res;
    	}
    }

?>