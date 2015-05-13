<?php

    /**
    * 有关书籍的所有动作，包括发布卖书和求书
    */
    class BookController extends Zend_Controller_Action
    {
    	
    	public function init()
    	{
    		//输出layout共同部分
    		$commen = new Application_Model_Commen($this->view);
    		$commen->theCommen();
    	}

    	/**
    	 * 发布页面
    	 * @return [type] [description]
    	 */
    	public function publishAction()
    	{
            //导航栏反白处理
            $this->view->publishClass = "nav-a-current";
    		$book = new Application_Model_Book();
    		$global = new Application_Model_Global();
    		$count = '6';
    		$page = $this->getRequest()->getParam('page');
    		if (!$page || !is_numeric($page)) {
    			$page = 1;
    		}
    		$sum = $book->theSum();
    		$pages = $global->thePages($sum, $count);
            if ($page > $pages) {
                $page = $pages;
            }
    		$offset = ($page-1)*$count;
    		if ($page == $pages) {
    			$count = $sum-($page-1)*$count;
    		}
    		$bookInfo = $book->listHot('1', $count, $offset);
    		$this->view->bookInfo = $bookInfo;
    		$pageNav = $global->pageNav($page, $pages, 'publish');
    		$this->view->pageNav = $pageNav;
    		$this->view->page = $page;
    		$this->view->pages = $pages;
    	}

        /**
         * 输出一键发布页面
         */
        public function topublishAction()
        {
            //是否登录检测
            if (!$_SESSION['uid']) {
                $url = array('url' => '/book/topublish');
                $this->_forward('logbad', 'global', null, $url);
            }
        }

        /**
         * 一键发布 信息处理
         */
        public function publishchkAction()
        {
            //是否登录检测
            if (!$_SESSION['uid']) {
                header('Location: http://online.hfut.edu.cn/404.php');
                exit(1);
            }

            //检查提交方式
            if ($this->getRequest()->isGet()) {
                header('Location: http://online.hfut.edu.cn/404.php');
                exit(1);
            }
            $uid = $_SESSION['uid'];
            $book = new Application_Model_Book();
            $bookName = $this->getRequest()->getParam('bookname');
            $press = $this->getRequest()->getParam('press'); 
            $originalPrice = $this->getRequest()->getParam('original-price');
            $nowPrice = $this->getRequest()->getParam('going-price');
            $college = $this->getRequest()->getParam('college');
            $bookDes = $this->getRequest()->getParam('bookdes');
            $endTime = $this->getRequest()->getParam('expiration-date');
            $tel = $this->getRequest()->getParam('tel');
            $labelName = $this->getRequest()->getParam('get-lables');
            $file = $this->getRequest()->getParam('book-cover');
            $publishRes = $book->addOne($bookName, $press, $bookDes, $originalPrice, $nowPrice, $college, $endTime, $uid, $labelName, $file);
            //更新用户电话号码
            $user = new Application_Model_UserMapper();
            $user->updateTel($uid, $tel);
            if ($publishRes) {
                $alertValue = array('a' => "成功发布书籍啦~~~", 'b'=> 'publish');
                $this->_forward('ok', 'global', null, $alertValue);
            } else {
                $failInfo = "Bad!木有发布成功...";
                $alertValue = array('a' => $failInfo);
                $this->_forward('bad', 'global', null, $alertValue);
            }
        }

        /**
         * 求书喊话页面
         * @return [type] [description]
         */
    	public function seekAction()
    	{
            //导航栏反白处理
            $this->view->seekClass = "nav-a-current";
    		$buy = new Application_Model_Buy();
    		$global = new Application_Model_Global();
            //判断是否登录
            if ($_SESSION['uid']) {
                $this->view->loginJudge = true;
            }
    		$count = 9;
    		$page = $this->getRequest()->getParam('page');
    		if (!$page || !is_numeric($page)) {
    			$page = 1;
    		}
    		$sum = $buy->theSum();
            $pages = $global->thePages($sum, $count);
            if ($page > $pages) {
                $page = $pages;
            }
    		$offset = ($page-1)*$count;
    		if ($page == $pages) {
    			$count = $sum-($page-1)*$count;
    		}
    		$buyInfo = $buy->listHot($count, $offset);
    		$this->view->buyInfo = $buyInfo;
    		$pageNav = $global->pageNav($page, $pages, 'seek');
    		$this->view->pageNav = $pageNav;
    		$this->view->page = $page;
            $this->view->pages = $pages;
            $this->view->loginUrl = "http://user.hfutonline.net/login?from=http://book.hfutonline.net/book/seek/page/" . $page;
    	}

        /**
         * 发布求书页面，根据用户输入输出相关书籍
         */
        public function relativeAction()
        {
            $this->_helper->layout->disableLayout();
            $search = new Application_Model_Search();
            $inputName = $this->getRequest()->getParam('buybook');
            $bookInfo = $search->addAllInfo($inputName, '2', '1');
            $this->view->relabook = $bookInfo['book'];
        }

        /**
         * 发布求书页面
         */
        public function toseekAction()
        {
            // 是否登录检测
            if (!$_SESSION['uid']) {
                $url = array('url' => '/book/toseek');
                $this->_forward('logbad', 'global', null, $url);
            }
            //输出最近发布
            $book = new Application_Model_Book();
            $hotBook = $book->listHot('1', '8', '0');
            $this->view->relabook = $hotBook['book'];
        }

        /**
         * 求书信息发布处理
         */
        public function seekchkAction()
        {
            //是否登录检测
            if (!$_SESSION['uid']) {
                header('Location: http://online.hfut.edu.cn/404.php');
                exit(1);
            }

            //检查提交方式
            if ($this->getRequest()->isGet()) {
                header('Location: http://online.hfut.edu.cn/404.php');
                exit(1);
            }
            $buy = new Application_Model_Buy();
            $bookName = $this->getRequest()->getParam('bookname');
            $desText = $this->getRequest()->getParam('book-des-text');
            $tel = $this->getRequest()->getParam('tel');
            $addRes = $buy->addOne($bookName, $desText, $_SESSION['username'], $_SESSION['uid']);
            //更新用户电话号码
            $user = new Application_Model_UserMapper();
            $user->updateTel($_SESSION['uid'], $tel);
            if ($addRes) {
                $alertValue = array('a' => '发布求书信息成功啦~~~', 'b' => 'seek');
                $this->_forward('ok', 'global', null, $alertValue);
            } else {
                $alertValue = array('a' => 'Bad!木有发布成功...再试一遍吧！');
                $this->_forward('bad', 'global', null, $alertValue);
            }
        }

        /**
         * 输出详细书籍展示页面
         */
        public function detailAction()
        {
            $book = new Application_Model_Book();
            $college = new Application_Model_CollegeMapper();
            $search = new Application_Model_Search();
            $global = new Application_Model_Global();
            $bookId = $this->getRequest()->getParam('book');
            $bookInfo = $book->listOne($bookId);
            if (!$bookInfo['book']) {
                $bookId = $book->listLast();
                $bookInfo = $book->listOne($bookId);
            }
            //输出最近发布的书籍
            if ($_SESSION['uid']) {
                $book->setHistoryBook($bookId);
                $this->view->userOnload = true;
                $this->view->sideTitle = "最近浏览";
                $sideBook = $book->getHistoryBook();
            } else {
                $this->view->userOnload = false;
                $this->view->sideTitle = "最近发布";
                $hotBook = $book->listHot('1', '5', '0');
                $sideBook = $hotBook['book'];
            }
            $this->view->sideBook = $sideBook;
            $colName = $college->findOne($bookInfo['book']['book_col']);
            $bookInfo['book']['college'] = $colName['college'];
            $this->view->bookInfo = $bookInfo;
            //输出相关书籍
            $recommendBook = $search->addAllInfo($bookInfo['book']['book_name'], '2', '1');
            for ($i=0; $i < count($recommendBook['book']); $i++) { 
                $recommendBook['book'][$i] = $global->addUrl($recommendBook['book'][$i], 'book');
            }
            $this->view->recommend = $recommendBook['book'];
            $this->view->loginUrl = "http://user.hfutonline.net/login?from=http://book.hfutonline.net/book/detail/book/" .$bookId; 
        }
    }
?>
