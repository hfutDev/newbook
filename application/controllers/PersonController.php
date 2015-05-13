<?php

    /**
    *  个人中心所有页面输出
    */
    class PersonController extends Zend_Controller_Action
    {
    	protected $person;
    	
    	public function init()
	    {
	    	//设定layout
	    	$this->_helper->layout->setLayout('person');
	    	//输出layout共同内容
	        $commen = new Application_Model_Commen($this->view);
	        $commen->theCommen();
	        //测试暂用
	        // $_SESSION['uid'] = '1';
	        $getUserId = $this->getRequest()->getParam('person');
	        //记录
	        if ($getUserId) {
	        	$_SESSION['guid'] = $getUserId;
	        } else {
	        	if ($_SESSION['guid']) {
	        		$getUserId = $_SESSION['guid'];
	        	} else {
	        		$getUserId = $_SESSION['uid'];
	        	}
	        }
	        //判断是否登录
	        // if (!$_SESSION['uid']) {
	        // 	$changeUrl = "/person/index/person" . $getUserId;
	        // 	$alertValue = array('a' => $changeUrl);
	        // 	$this->_forward('logbad', 'global', null, $alertValue);
	        // }
	        //判断登录身份
	        if ($getUserId == $_SESSION['uid']) {
	        	$this->person = $_SESSION['uid'];
	        } else {
	        	$this->person = $getUserId;
	        }
	        //输出个人中心公用模块
	        $commen->thePerson($this->person);
	    }

	    /**
	     * 输出个人中心主页
	     */
	    public function indexAction()
	    {
	    	$book = new Application_Model_Book();
	    	$buy = new Application_Model_Buy();
	    	$people = new Application_Model_Person();
	    	//判断是否登录
	        if (!$_SESSION['uid']) {
	        	$changeUrl = "/person/index/person" . $getUserId;
	        	$alertValue = array('a' => $changeUrl);
	        	$this->_forward('logbad', 'global', null, $alertValue);
	        } else {
	        	$user = new Application_Model_UserMapper();
		        $userChkRes = $user->findOne($this->person);
		        if (!$userChkRes) {
		        	unset($_SESSION['guid']);
		        	$judge = "personbad";
		        }
		    	if ($this->person == $_SESSION['uid']) {
		    		$saleBook = $book->listByUser($this->person, '0', '1', '4');
		    		$this->view->saleBook = $saleBook;
		    		$mesInfo = $people->showMessage($this->person);
		    		$this->view->mesInfo = $mesInfo;
		    		$visInfo = $people->showVisitor($this->person, '4');
		    		$this->view->visInfo = $visInfo;
		    		$judge = "pindex";
		    	} elseif ($userChkRes && $this->person != $_SESSION['uid']) {
		    	    $buyBook = $buy->findByUserState($this->person, '0', '1', '3');
		    	    $this->view->buyBook = $buyBook;
		    	    $saleBook = $book->listByUser($this->person, '0', '1', '3');
		    	    $this->view->saleBook = $saleBook;
		    	    $judge = "gindex";
		    	}
		    	switch ($judge) {
		    		case 'personbad':
		    		    $this->_helper->layout->setLayout('layout');
		    			$this->render('personbad');
		    			break;

		    		case 'pindex':
		    			$this->render('pindex');
		    			break;

		    		case 'gindex':
		    			$this->render('gindex');
		    			break;
		    		
		    		default:
		    		    $this->_helper->layout->setLayout('layout');
		    			$this->render('lost');
		    			break;
		    	}
	        }
	    }

	    /**
	     * 输出个人中心我的易书页面 包括主人和访客模式
	     */
	    public function perguestbookAction()
	    {
	    	$book = new Application_Model_Book();
	    	$buy = new Application_Model_Buy();
	    	$count = 5;
	    	$offset = 0;
	    	$saleBook = $book->listByUser($this->person, $offset, '1', $count);
	    	$this->view->saleBook = $saleBook;
	    	$buyBook = $buy->findByUserState($this->person, $offset, '1', $count);
	    	$this->view->buyBook = $buyBook;
	    	$type = $this->getRequest()->getParam('type');
	    	switch ($type) {
	    		case 'per':
	    			$this->render('perbook');
	    			break;
	    		
	    		case 'guest':
	    			$this->render('guestbook');
	    			break;

	    		default:
	    			$this->_helper->layout->setLayout('layout');
	    			$this->render('lost');
	    			break;
	    	}
	    }

	    /**
	     * 输出个人中心正在发布信息页面
	     */
	    public function perpublishAction()
	    {
	    	$book = new Application_Model_Book();
	    	$global = new Application_Model_Global();
	    	$count = 5;
	    	$page = $this->getRequest()->getParam('page');
	    	if (!$page) {
	    		$page = 1;
	    	}
	    	$type = $this->getRequest()->getParam('type');
	    	switch ($type) {
	    		case 'publishing':
			    	$userSum = $book->userSum($this->person, '1');
			    	$pages = $global->thePages($userSum, $count);
			    	if ($page > $pages) {
			    		$page = $pages;
			    	}
			    	$offset = $count*($page-1);
			    	if (!$page) {
			    		$offset = 0;
			    	}
			    	$saleBook = $book->listByUser($this->person, $offset, '1', $count);
			    	$this->view->saleBook = $saleBook;
			    	$pageNav = $global->pageNav($page, $pages, 'perpublishing');
			    	$this->view->title = "正在发布";
	    			break;

	    		case 'published':
		    		$userSum = $book->userSum($this->person, '2');
			    	$pages = $global->thePages($userSum, $count);
			    	if ($page > $pages) {
			    		$page = $pages;
			    	}
			    	$offset = $count*($page-1);
			    	if (!$page) {
			    		$offset = 0;
			    	}
	    			$saleBook = $book->listByUser($this->person, $offset, '2', $count);
			    	$this->view->saleBook = $saleBook;
			    	$pageNav = $global->pageNav($page, $pages, 'perpublished');
			    	$this->view->title = "已完成发布";
	    			break;

	    		default:
	    		    $this->_helper->layout->setLayout('layout');
	    			$this->render('lost');
	    			break; 
	    	}
	    	$this->view->pageNav = $pageNav;
	    	$this->view->page = $page;
	    	$this->view->pages = $pages;
	    }

	    /**
	     * 输出个人中心求书信息页面
	     */
	    public function perseekAction()
	    {
	    	$buy = new Application_Model_Buy();
	    	$global= new Application_Model_GLobal();
	    	$count = 5;
	    	$page = $this->getRequest()->getParam('page');
	    	if (!$page) {
	    		$page = 1;
	    	}
	    	$type = $this->getRequest()->getParam('type');
	    	switch ($type) {
	    		case 'seeking':
	    			$userSum = $buy->userSum($this->person, '1');
	    			$pages = $global->thePages($userSum, $count);
	    			if ($page > $pages) {
	    				$page = $pages;
	    			}
	    			$offset = $count*($page-1);
	    			if (!$page) {
			    		$offset = 0;
			    	}
	    			$buyBook = $buy->findByUserState($this->person, $offset, '1', $count);
			    	$this->view->buyBook = $buyBook;
			    	$pageNav = $global->pageNav($page, $pages, 'perseeking');
			    	$this->view->title = "正在求书";
	    			break;

	    		case 'seeked':
	    			$userSum = $buy->userSum($this->person, '2');
	    			$pages = $global->thePages($userSum, $count);
	    			if ($page > $pages) {
	    				$page = $pages;
	    			}
	    			$offset = $count*($page-1);
	    			if (!$page) {
			    		$offset = 0;
			    	}
	    			$buyBook = $buy->findByUserState($this->person, $offset, '2', $count);
			    	$this->view->buyBook = $buyBook;
			    	$pageNav = $global->pageNav($page, $pages, 'perseeked');
			    	$this->view->title = "已完成求书";
	    			break;
	    		
	    		default:
	    			$this->_helper->layout->setLayout('layout');
	    			$this->render('lost');
	    			break;
	    	}
	    	$this->view->pageNav = $pageNav;
	    	$this->view->page = $page;
	    	$this->view->pages = $pages;
	    }

	    /**
	     * 个人中心留言板页面输出  包括主人和访客模式
	     * @return [type] [description]
	     */
	    public function mesAction()
	    {
	    	$people = new Application_Model_Person();
	    	$global = new Application_Model_GLobal();
	    	$count = 3;
	    	$page = $this->getRequest()->getParam('page');
	    	if (!$page) {
	    		$page = 1;
	    	}
	        $userSum = $people->userMesSum($this->person);
	        $pages = $global->thePages($userSum, $count);
	        if ($page > $pages) {
	        	$page = $pages;
	        }
	    	$offset = $count*($page-1);
	    	if (!$page) {
	    		$offset = 0;
	    	}
	    	$mes = $people->showMessage($this->person, $offset);
	    	$this->view->mes = $mes;
	    	$reply = $people->showReply($mes['mes']);
	    	$this->view->reply = $reply;
	    	$this->view->page = $page;
	    	$this->view->pageSize = $count;
	    	$this->view->pages = $pages;
	    	$type = $this->getRequest()->getParam('type');
	    	switch ($type) {
	    		case 'per':
	    			$this->view->title = "我的留言动态";
	    			$pageNav = $global->pageNav($page, $pages, 'permes');
	    			break;

	    		case 'guest':
	    			$this->view->title = "TA的留言动态";
	    			$pageNav = $global->pageNav($page, $pages, 'guestmes');
	    			break;
	    		
	    		default:
	    			$this->_helper->layout->setLayout('layout');
	    			$this->render('lost');
	    			break;
	    	}
	    	$this->view->pageNav = $pageNav;
	    }

	    /**
	     * 输出个人中心最近访客  包括主人和访客模式
	     */
	    public function visitorAction()
	    {
	    	$people = new Application_Model_Person();
	    	$visitor = $people->showVisitor($this->person, '20');
	    	$this->view->visitor = $visitor;
	    	$type = $this->getRequest()->getParam('type');
	    	switch ($type) {
	    		case 'per':
	    			$this->view->title = "最近来访";
	    			break;

	    		case 'guest':
	    			$this->view->title = "TA的访客";
	    			break;
	    		
	    		default:
	    			$this->_helper->layout->setLayout('layout');
	    			$this->render('lost');
	    			break;
	    	}
	    }

	    /**
	     * 输出访客模式 发布书籍信息页面
	     */
	    public function guestpublishAction()
	    {
	    	$book = new Application_Model_Book();
	    	$global = new Application_Model_Global();
	    	$count = 5;
	    	$page = $this->getRequest()->getParam('page');
	    	if (!$page) {
	    		$page = 1;
	    	}
	    	$userSum = $book->userSum($this->person, '1');
	    	$pages = $global->thePages($userSum, $count);
	    	if ($page > $pages) {
	    		$page = $pages;
	    	}
	    	$offset = $count*($page-1);
	    	if (!$page) {
	    		$offset = 0;
	    	}
	    	$saleBook = $book->listByUser($this->person, $offset, '1', $count);
	    	$this->view->saleBook = $saleBook;
	    	$pageNav = $global->pageNav($page, $pages, 'guestpublish');
	    	$this->view->pageNav = $pageNav;
	    	$this->view->page = $page;
	    	$this->view->pages = $pages;
	    }

	    /**
	     * 输出访客模式 求书信息页面
	     */
	    public function guestseekAction()
	    {
	    	$buy = new Application_Model_Buy();
	    	$global= new Application_Model_GLobal();
	    	$count = 5;
	    	$page = $this->getRequest()->getParam('page');
	    	if (!$page) {
	    		$page = 1;
	    	}
	    	$userSum = $buy->userSum($this->person, '1');
			$pages = $global->thePages($userSum, $count);
			if ($page > $pages) {
				$page = $pages;
			}
			$offset = $count*($page-1);
			if (!$page) {
	    		$offset = 0;
	    	}
			$buyBook = $buy->findByUserState($this->person, $offset, '1', $count);
	    	$this->view->buyBook = $buyBook;
	    	$pageNav = $global->pageNav($page, $pages, 'guestseek');
	    	$this->view->pageNav = $pageNav;
	    	$this->view->page = $page;
	    	$this->view->pages = $pages;
	    }

	    /**
	     * 取消书籍交易
	     */
	    public function cancelbookAction()
	    {
	    	$sale = new Application_Model_SaleMapper();
	    	$buy = new Application_Model_BuyMapper();
	    	$type = $this->getRequest()->getParam('deal_type');
	    	$id = $this->getRequest()->getParam('deal_id');
	    	if ($type == 'publish') {
	    		$res = $sale->updateState($id);
	    		if ($res) {
	    			echo 1;
	    		} else {
	    			echo 'fail';
	    		}
	    	} elseif ($type == 'seek') {
	    		$res = $buy->updateState($id);
	    		if ($res) {
	    			echo 1;
	    		} else {
	    			echo 'fail';
	    		}
	    	}
	    	exit(1);
	    }
    }

?>