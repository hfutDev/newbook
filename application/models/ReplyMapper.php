<?php

    /**
    * 操作回复评论的具体方法
    */
    class Application_Model_ReplyMapper  
    {
    	protected $reply;
    	protected $replyDb;
    	
    	function __construct()
    	{
    		$this->reply = new Application_Model_DbTable_Reply();
    		$this->replyDb = $this->reply->getAdapter();
    	}

    	/**
    	 *  列出某条留言的评论
    	 * @param  [num] $id     留言id
    	 * @param  string $offset 数据开始位置
    	 * @return [two-dim array]  
    	 */
    	public function listReply($id, $offset='0')
    	{
    		$where = $this->replyDb->quoteInto('mid = ?', $id);
    		$order = "reply_time DESC";
            $count = "20";
    		$res = $this->reply->fetchAll($where, $order, $count, $offset)->toArray();
    		return $res;
    	}

        /**
         * 根据用户查询评论内容
         * @param  [num] $id      用户id
         * @param  string $offset 数据开始位置
         * @param  string $count  数据量
         * @return [two-dim array]         
         */
        public function findByUser($id, $offset='0', $count='12')
        {
            $where = $this->replyDb->quoteInto('gid = ?', $id);
            $order = "reply_time DESC";
            $res = $this->reply->fetchAll($where, $order, $count, $offset)->toArray();
            return $res;
        }

    	/**
    	 * 记录评论
    	 * @param [num] $messageId 留言id
    	 * @param [num] $guestId   访客id
    	 * @param [string] $content   评论内容
    	 */
    	public function addOne($messageId, $guestId, $content)
    	{
    		$date = new Zend_Date();
	    	$date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Y-m-d H:i:s');
            $content = $this->replyDb->quote($content);
	    	$data = array(
	    		'mid' => $messageId,
	    		'gid' => $guestId,
	    		'reply_content' => $content,
	    		'reply_time' => $time
	    		);
	    	$res = $this->reply->insert($data);
	    	return $res;
    	}

        /**
         * 删除某条评论
         * @param  [num] $id 评论id号
         * @return [num]     受影响的行数
         */
        public function delOne($id)
        {
            $where = $this->replyDb->quoteInto('reply_id = ?', $id);
            $res = $this->reply->delete($where);
            return $res;
        }

        /**
         * 根据留言id删除评论
         * @param  [num] $id 评论id
         * @return [num]     影响的行数
         */
        public function delOneByMesId($id)
        {
            $where = $this->replyDb->quoteInto('mid = ?', $id);
            $res = $this->reply->delete($where);
            return $res;
        }

        /**
         * 计算评论总数
         * @return [num] 
         */
        public function theSum()
        {
            $res = $this->reply->fetchAll()->count();
            return $res;
        }

        /**
         * 输出某户的总的评论数
         * @param  [num] $id 用户id
         * @return [num]  影响的记录行数   
         */
        public function userSum($id)
        {
            $where = $this->replyDb->quoteInto('gid = ?', $id);
            $sum = $this->reply->fetchAll($where)->count();
            return $sum;
        }
    }

?>
