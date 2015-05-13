<?php

    /**
    * 操作求书数据表的具体方法
    */
    class Application_Model_BuyMapper  
    {

    	protected $buy;
    	protected $buyDb;
    	
    	function __construct()
    	{
    		$this->buy = new Application_Model_DbTable_Buy();
    		$this->buyDb = $this->buy->getAdapter();
    	}

    	/**
    	 * 取出求书信息
    	 * @param count 信息数量
    	 * @param offset 信息点开始位置
    	 * @return  [二维数组]
    	 */
    	public function listBuy($count='9', $offset='0', $state='1')
    	{
    		$order = "buy_id DESC";
            $where = $this->buyDb->quoteInto('buy_state = ?', $state);
    		$res = $this->buy->fetchAll($where, $order, $count, $offset)->toArray();
    		return $res;
    	}

        /**
         * 根据用户id和求书状态查询信息
         * @param  [num] $uid     用户id
         * @param  [num] $offset  数据开始位置
         * @param  string $state  求书状态
         * @param  string $count  数据量
         * @return [two-dim array]         
         */
        public function listByUserState($uid, $offset, $state='1', $count='5')
        {
            $order = "buy_id DESC";
            $where = $this->buyDb->quoteInto('buy_userid = ? AND ', $uid);
            $where .= $this->buyDb->quoteInto('buy_state = ?', $state);
            $res = $this->buy->fetchAll($where, $order, $count, $offset)->toArray();
            return $res;
        }

        /**
         * 计算用户求书信息总量
         * @param  [num] $uid   用户id
         * @param  string $state 交易状态
         * @return [num]        
         */
        public function listAllUserState($uid, $state='1')
        {
            $where = $this->buyDb->quoteInto('buy_userid = ? AND ', $uid);
            $where .= $this->buyDb->quoteInto('buy_state = ?', $state);
            $res = $this->buy->fetchAll($where)->count();
            return $res;
        }

        /**
         * 列出求书所有信息
         * @return [two-dim array] 
         */
        public function listAll($state='1')
        {
            $where = $this->buyDb->quoteInto('buy_state = ?', $state);
            $res = $this->buy->fetchAll($where)->toArray();
            return $res;
        }

    	/**
         * 增加求书信息
         * @param  [string] $bname   书名
         * @param  [string] $content 求书喊话内容
         * @param  [string] $uname   求书用户名
         * @param  [num] $uid     用户id
         * @return [num]          求书信息在数据表的id号
         */
    	public function insertOne($bname, $content, $uname, $uid)
    	{
            $date = new Zend_Date();
            $date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Y-m-d H:i:s');
            $bname  = $this->buyDb->quote($bname);
            $content = $this->buyDb->quote($content);
            $data = array(
                'buy_bookname' => $bname,
                'buy_content' => $content,
                'buy_username' => $uname,
                'buy_userid' => $uid,
                'time' => $time
                );
    		$res = $this->buy->insert($data);
    		return $res;
    	}

        /**
         * 删除求书
         * @param  [num] $id 求书id
         * @return [num]     成功则返回1
         */
        public function delOne($id)
        {
            $where = $this->buyDb->quoteInto('buy_id = ?', $id);
            $res = $this->buy->delete($where);
            return $res;
        }

    	/**
    	 * 修改求书状态
    	 * @param id 修改的求书信息id号
    	 * @param state 状态代号  默认修改为2（交易结束）
    	 * @return  [二维数组]
    	 */
    	public function updateState($id, $state='2')
    	{
    		$where = $this->buyDb->quoteInto('buy_id = ?', $id);
    		$data = array('buy_state' => $state);
    		$res = $this->buy->update($data, $where);
    		return $res;
    	}
    }
?>
