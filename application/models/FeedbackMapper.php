<?php

    /**
    * 处理客户意见的具体方法
    */
    class Application_Model_FeedbackMapper
    {
    	protected $feedback;
    	protected $feedbackDb;
    	
    	function __construct()
    	{
    		$this->feedback = new Application_Model_DbTable_Feedback();
    		$this->feedbackDb = $this->feedback->getAdapter();
    	}

    	/**
    	 * 记录客户留言
    	 * @param [string] $content 留言内容
    	 * @return [num] 留言记录id
    	 */
    	public function addOne($content)
    	{
    		$date = new Zend_Date();
	    	$date->setOptions(array('format_type' => 'php'));
	    	$time = $date->toString('Y-m-d H:i:s');
	    	$data = array(
	    		'fb_content' => $content,
	    		'time' => $time
	    		 );
	    	$res = $this->feedback->insert($data);
	    	return $res;
    	}

    	/**
    	 * 列出客户留言信息
    	 * @param  string $offset 数据开始位置 用于分页
    	 * @return [two-dim array]     
    	 */
    	public function listFeedback($offset='0')
    	{
    		$order = "time DESC";
    		$count = "6";
    		$res = $this->feedback->fetchAll($where=null, $order, $count, $offset)->toArray();
    		return $res;
    	}
    }

?>