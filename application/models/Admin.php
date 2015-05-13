<?php

    /**
    *  后台管理公用模块
    */
    class Application_Model_Admin
    {
    	public $view;

    	function __construct($view)
    	{
    		$this->view = $view;
    	}

    	public function theCount()
    	{
    		$book = new Application_Model_Book();
    		$buy = new Application_Model_Buy();
    		$message = new Application_Model_MessageMapper();
    		$reply = new Application_Model_ReplyMapper();
            $user = new Application_Model_UserMapper();
    		$publishSum = $book->theSum();
    		$this->view->pSum = $publishSum;
    		$buySum = $buy->theSum();
    		$this->view->bSum = $buySum;
    		$messageSum = $message->theSum();
    		$this->view->mSum = $messageSum;
    		$replySum = $reply->theSum();
    		$this->view->rSum = $replySum;
            $userSum = $user->theSum();
            $this->view->uSum = $userSum;
            $this->view->userName = $_SESSION['admin'];
            $this->view->userDegree = $_SESSION['admin_degree'];
    	}
    }
?>