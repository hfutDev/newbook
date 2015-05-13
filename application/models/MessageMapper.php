<?php

    /**
    * 操作留言的的具体方法
    */
    class Application_Model_MessageMapper
    {
    	protected $message;
    	protected $messageDb;
    	
    	function __construct()
    	{
    		$this->message = new Application_Model_DbTable_Message();
    		$this->messageDb = $this->message->getAdapter();
    	}

        /**
         * 列出所有留言信息
         * @param  string $offset 数据开始位置
         * @param  string $count  数据量
         * @return [two-dim array]    
         */
        public function listAll($offset='0', $count='12')
        {
            $order = "mes_time DESC";
            $res = $this->message->fetchAll($where=null, $order, $count, $offset)->toArray();
            return $res;
        }

    	/**
    	 * 取出用户留言信息
    	 * @param  [num] $id     用户id
    	 * @param  string $offset 数据开始位置
    	 * @return [two-dim array]     
    	 */
    	public function listMessage($id, $offset='0', $count='3')
    	{
    		$where = $this->messageDb->quoteInto('uid = ?', $id);
    		$order = "mes_time DESC";
    		$res = $this->message->fetchAll($where, $order, $count, $offset)->toArray();
    		return $res;
    	}

        /**
         * 根据留言id查询留言内容
         * @param  [num] $id 留言id
         * @return [one-dim array]     
         */
        public function findOne($id)
        {
            $where = $this->messageDb->quoteInto('mes_id = ?', $id);
            $res = $this->message->fetchRow($where);
            if ($res) {
                $res = $res->toArray();
            }
            return $res;
        }

        /**
         * 计算总共留言数目
         * @return [num] 
         */
        public function theSum()
        {
            $res = $this->message->fetchAll()->count();
            return $res;
        }

        /**
         * 计算某位用户留言的总量
         * @param  [num] $uid 用户id
         * @return [num]      
         */
        public function userSum($uid)
        {
            $where = $this->messageDb->quoteInto('uid = ?', $uid);
            $res = $this->message->fetchAll($where)->count();
            return $res;
        }

        /**
         * 增加评论
         * @param [num] $userId  被评论的用户id 
         * @param [num] $guestId 访客id
         * @param [num] $content 评论在数据表中的id号
         */
    	public function addOne($userId, $guestId, $content)
    	{
    		$date = new Zend_Date();
	    	$date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Y-m-d H:i:s');
            $content = $this->messageDb->quote($content);
	    	$data = array(
	    		'uid' => $userId, 
	    		'guest_id' => $guestId,
	    		'mes_content' => $content,
	    		'mes_time' => $time
	    		);
	    	$res = $this->message->insert($data);
	    	return $res;
    	}

        /**
         * 删除某条留言
         * @param  [num] $id 留言id
         * @return [num]     受影响的行数
         */
        public function delOne($id)
        {
            $where = $this->messageDb->quoteInto('mes_id =?', $id);
            $res = $this->message->delete($where);
            return $res;
        }
    }

?>
