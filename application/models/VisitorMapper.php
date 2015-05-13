<?php

    /**
    * 操作访客信息的具体方法
    */
    class Application_Model_VisitorMapper  
    {
    	protected $visitor;
    	protected $visitorDb;
    	
    	function __construct()
    	{
    		$this->visitor = new Application_Model_DbTable_Visitor();
    		$this->visitorDb = $this->visitor->getAdapter();
    	}

    	/**
    	 * 列出用户的所有访客
    	 * @param  [num] $id 用户id
    	 * @return [two-dim array]   
    	 */
    	public function listVisitor($id, $count='4')
    	{
    		$order = "vis_time DESC";
            $offset = 0;
    		$where = $this->visitorDb->quoteInto('uid = ?', $id);
    		$res = $this->visitor->fetchAll($where, $order, $count, $offset)->toArray();
    		return $res;
    	}

    	/**
    	 * 记录访客
    	 * @param [num] $userId  用户id
    	 * @param [num] $guestId 访客id
    	 */
    	public function addOne($userId, $guestId)
    	{
    		$date = new Zend_Date();
	    	$date->setOptions(array('format_type' => 'php'));
	    	$time = $date->toString('Y-m-d H:i:s');
	        $searchWhere = $this->visitorDb->quoteInto('uid = ?', $userId) . $this->visitorDb->quoteInto('AND guest_id = ?', $guestId);
	        $order = "vis_time DESC";
	        $searchRes = $this->visitor->fetchAll($searchWhere, $order)->toArray();
	        //return $searchRes;
	        if ($searchRes) {
	        	$lastVis = $searchRes['0'];
	        	//一天之内访问多次只记录一次
	        	if ((substr($time, 0, 4))-(substr($lastVis['vis_time'], 0, 4)) > 0) {
	        		$judge = '1';
	        	} elseif ((substr($time, 5, 2))-(substr($lastVis['vis_time'], 5, 2)) > 0) {
	        		$judge ='1';
	        	} elseif ((substr($time, 8, 2))-(substr($lastVis['vis_time'], 8, 2)) > 0) {
	        		$judge = '1';
	        	}
	        	if ($judge == '1') {
	        		$data = array(
	        			'uid' => $userId,
	        			'guest_id' => $guestId,
	        			'vis_time' => $time
	        			);
	        		$res = $this->visitor->insert($data);
	        		return $res;
	        	} else {
	        		$res = '0';
	        		return $res;
	        	}
	        }
    	}

        /**
         * 计算某位访客总量
         * @param  [num]  $uid 
         * @return [num]     
         */
        public function userSum($uid)
        {
            $where = $this->visitorDb->quoteInto('uid = ?', $uid);
            $res = $this->visitor->fetchAll($where)->count();
            return $res;
        }
    }

?>