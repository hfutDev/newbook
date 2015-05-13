<?php

    /**
    * 操作sale数据表的具体操作
    */
    class Application_Model_SaleMapper
    {
    	protected $sale;
    	protected $saleDb;
    	
    	function __construct()
    	{
    		$this->sale = new Application_Model_DbTable_Sale();
    		$this->saleDb = $this->sale->getAdapter();
    	}

    	/**
    	 * 通过用户id 查找交易信息
    	 * @param  [num] $id 用户id
    	 * @return [two-dim array]
    	 */
    	public function findByUser($id)
    	{
    		$where = $this->saleDb->quoteInto('sale_userid = ?', $id);
    		$res = $this->sale->fetchAll($where)->toArray();
    		return $res;
    	}

        /**
         * 根据用户id和书记交易状态查询书籍信息
         * @param  [num] $uid     用户id
         * @param  [num] $offset  数据开始位置
         * @param  string $count  数据量
         * @param  string $state  书籍交易状态
         * @return [two-dim array]         
         */
        public function findByUserState($uid, $offset, $state='1', $count='5')
        {
            $order = "sale_id DESC";
            $where = $this->saleDb->quoteInto('sale_userid = ? AND ', $uid);
            $where .= $this->saleDb->quoteInto('sale_state = ?', $state);
            $res = $this->sale->fetchAll($where, $order, $count, $offset)->toArray();
            return $res;
        }

        /**
         * 计算用户某个书籍交易状态的书籍总量
         * @param  [num] $uid   用户id
         * @param  string $state 交易状态
         * @return [num]        
         */
        public function userSum($uid, $state='1')
        {
            $where = $this->saleDb->quoteInto('sale_userid = ? AND ', $uid);
            $where .= $this->saleDb->quoteInto('sale_state = ?', $state);
            $res = $this->sale->fetchAll($where)->count();
            return $res;
        }

    	/**
    	 * 通过书籍id 查找交易信息
    	 * @param  [num] $id  书籍id
    	 * @return [two-dim array]
    	 */
    	public function findByBook($id)
    	{
    		$where = $this->saleDb->quoteInto('sale_bookid = ?', $id);
    		$res = $this->sale->fetchRow($where);
            if ($res) {
                $res = $res->toArray();
            }
    		return $res;
    	}

    	/**
    	 * 通过交易状态查找交易信息
    	 * @param  string $state
    	 * @return [two-dim array]
    	 */
    	public function findByState($state='1', $count='12', $offset='0')
    	{
            $order = "sale_id DESC";
    		$where = $this->saleDb->quoteInto('sale_state = ?', $state);
    		$res = $this->sale->fetchAll($where, $order, $count, $offset)->toArray();
    		return $res;
    	}

        /**
         * 列出所有数据
         * @param  string $state 交易状态
         * @return [two-dim array]      
         */
        public function listAll($state='1')
        {
            $where = $this->saleDb->quoteInto('sale_state = ?', $state);
            $res = $this->sale->fetchAll($where)->toArray();
            return $res;
        }

    	/**
    	 * 插入交易信息
    	 * @param  [num] $bookId
    	 * @param  [num] $userId
    	 * @param  [string] $endTime
    	 * @return [unm] 插入数据所得到id
    	 */
    	public function insertOne($bookId, $userId, $endTime)
    	{
    		$date = new Zend_Date();
	    	$date->setOptions(array('format_type' => 'php'));
	    	$startTime = $date->toString('Y-m-d');
	    	$data = array(
	    		'sale_bookid' => $bookId,
	    		'sale_userid' => $userId,
	    		'start_time' => $startTime,
	    		'end_time' => $endTime
	    		 );
	    	$res = $this->sale->insert($data);
	    	return $res;
    	}

        /**
         * 根据交易id删除交易信息
         * @param  [num] $id  交易id
         * @return [num]      受影响的行数
         */
        public function delOne($id)
        {
            $where = $this->saleDb->quoteInto('sale_id = ?', $id);
            $delRes = $this->sale->delete($where);
            return $delRes;
        }

        /**
         * 根据书籍id删除交易信息
         * @param  [num] $id  书籍id号
         * @return [num]      受影响的行数
         */
        public function delOneByBook($id)
        {
            $where = $this->saleDb->quoteInto('sale_bookid = ?', $id);
            $delRes = $this->sale->delete($where);
            return $delRes;
        }

        /**
         * 更新书籍交易状态
         * @param  [num] $id   书籍id
         * @param  string $state 交易状态代号
         * @return [num]       修改交易信息的id
         */
        public function updateState($id, $state='2')
        {
            $where = $this->saleDb->quoteInto('sale_bookid = ?', $id);
            $data = array('sale_state' => $state);
            $res = $this->sale->update($data, $where);
            return $res;
        }
    }

?>