<?php

    /**
    * 后台管理功能
    */
    class AdminController extends Zend_Controller_Action
    {
        protected $admin;

    	public function init()
    	{
            session_start();
    		$this->_helper->layout->setlayout('admin');
    		$this->admin = new Application_Model_Admin($this->view);
    		$this->admin->theCount();
    	}

        /**
         * 输出主页
         */
    	public function indexAction()
    	{
            // session_destroy();
            //检测是否登录
            if (!$_SESSION['admin']) {
                $this->_redirect('/admin/login');
            }
    	}

        /**
         * 输出后台登录页面
         */
        public function loginAction()
        {
            $this->_helper->layout->disableLayout();
        }

        public function loginchkAction()
        {
            $admin = new Application_Model_AdminMapper();
            $name = $this->getRequest()->getParam('username');
            $pass = $this->getRequest()->getParam('password');
            //$pass = md5($pass);
            if (isset($name) && isset($pass)) {
                $checkRes = $admin->findOne($name, $pass);
                if ($checkRes) {
                    $_SESSION['admin'] = $checkRes['name'];
                    $_SESSION['admin_degree'] = $checkRes['degree'];
                    $this->_redirect("/admin/index");
                } else {
                    $this->_redirect("/admin/login");
                }
            } else {
                $this->_redirect("/admin/login");
            }
            $this->_helper->layout->disableLayout();
        }

        public function logoutAction()
        {
            session_destroy();
            $this->_redirect('/index/index');
        }

        /**
         * 输出交易正在交易和以交易完成信息页面
         */
    	public function publishAction()
    	{
            if (!$_SESSION['admin']) {
                $this->_redirect('/admin/login');
            }
            $count = 12;
    		$book = new Application_Model_Book();
            $global = new Application_Model_Global();
            $type = $this->getRequest()->getParam('type');
            $page = $this->getRequest()->getParam('page');
            if (!$page) {
                $page = 1;
            }
            switch ($type) {
                case '1':
                    $theSum = $book->theSum('1');
                    $pages = $global->thePages($theSum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = $count*($page-1);
                    if ($pages == 0) {
                        $page = 0;
                        $offset = 0;
                    }
                    $bookInfo = $book->listHot('1', $count, $offset);
                    $pageNav = $global->pageNav($page, $pages, 'adminpubing');
                    $this->view->pubingClass = "active";
                    break;

                case '2':
                    $theSum = $book->theSum('2');
                    $pages = $global->thePages($theSum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = $count*($page-1);
                    if ($pages == 0) {
                        $page = 0;
                        $offset = 0;
                    }
                    $bookInfo = $book->listHot('2', $count, $offset);
                    $pageNav = $global->pageNav($page, $pages, 'adminpubed');
                    $this->view->pubedClass = "active";
                    break;
                
                default:
                    $theSum = $book->theSum('1');
                    $pages = $global->thePages($theSum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = $count*($page-1);
                    $bookInfo = $book->listHot('1', $count, $offset);
                    $pageNav = $global->pageNav($page, $pages, 'adminpubing');
                    $this->view->pubingClass = "active";
                    break;
            }
    		$this->view->publish = $bookInfo;
            $this->view->pageNav = $pageNav;
            $this->view->page = $page;
            $this->view->pages = $pages;
    	}

        /**
         * 输出根据用户查询交易信息
         */
        public function userpublishAction()
        {
            if (!$_SESSION['admin']) {
                $this->_redirect('/admin/login');
            }
            $count = 12;
            $book = new Application_Model_Book();
            $global = new Application_Model_Global();
            $user = new Application_Model_UserMapper();
            $userInfo  = $user->listHot('0', '50');
            $this->view->userInfo = $userInfo;
            $userId = $this->getRequest()->getParam('user-id');
            $userName = $this->getRequest()->getParam('user-name');
            if ($userName) {
                $userSearch = $user->findByName($userName);
                if ($userSearch) {
                    $userId = $userSearch['user_id'];
                }
            }
            if ($userId) {
                $_SESSION['searchUser'] = $userId;
                $userSearch = $user->findOne($userId);
            } else {
                if ($_SESSION['searchUser']) {
                    $userId = $_SESSION['searchUser'];
                    $userSearch = $user->findOne($userId);
                }
            }
            $type = $this->getRequest()->getParam('type');
            $page = $this->getRequest()->getParam('page');
            if (!$page) {
                $page = 1;
            }
            if ($userId) {
                switch ($type) {
                    case '3':
                        $userSum = $book->userSum($userId, '1');
                        $pages = $global->thePages($userSum, $count);
                        if ($page > $pages) {
                            $page = $pages;
                        }
                        $offset = $count*($page-1);
                        if ($pages == 0) {
                            $page = 0;
                            $offset = 0;
                        }
                        $bookInfo = $book->listByUser($userId, $offset, '1', $count);
                        $bookInfo['user'] = $userSearch;
                        $pageNav = $global->pageNav($page, $pages, 'adminuserpubing');
                        $this->view->upubingClass = "active";
                        $this->view->searchType = "3";
                        break;

                    case '4':
                        $userSum = $book->userSum($userId, '2');
                        $pages = $global->thePages($userSum, $count);
                        if ($page > $pages) {
                            $page = $pages;
                        }
                        $offset = $count*($page-1);
                        if ($pages == 0) {
                            $page = 0;
                            $offset = 0;
                        }
                        $bookInfo = $book->listByUser($userId, $offset, '2', $count);
                        $bookInfo['user'] = $userSearch;
                        $pageNav = $global->pageNav($page, $pages, 'adminuserpubed');
                        $this->view->upubedClass = "active";
                        $this->view->searchType = "4";
                        break;
                    
                    default:
                        $this->_redirect('/admin/publish');
                        break;
                }
            } else {
                if ($type == 3) {
                    $this->view->searchType = "3";
                } elseif ($type == 4) {
                    $this->view->searchType = "4";
                }
            }
            $this->view->publish = $bookInfo;
            $this->view->pageNav = $pageNav;
            $this->view->page = $page;
            $this->view->pages = $pages;
        }

        /**
         * 输出求书信息
         */
        public function seekAction()
        {
            if (!$_SESSION['admin']) {
                $this->_redirect('/admin/login');
            }
            $count = 12;
            $buy = new Application_Model_Buy();
            $global = new Application_Model_Global();
            $type = $this->getRequest()->getParam('type');
            $page = $this->getRequest()->getParam('page');
            if (!$page) {
                $page = 1;
            }
            switch ($type) {
                case '1':
                    $theSum = $buy->theSum('1');
                    $pages = $global->thePages($theSum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = $count*($page-1);
                    if ($pages == 0) {
                        $page = 0;
                        $offset = 0;
                    }
                    $buyInfo = $buy->listHot($count, $offset, '1');
                    $pageNav = $global->pageNav($page, $pages, 'adminseeking');
                    $this->view->seekingClass = "active";
                    break;

                case '2':
                    $theSum = $buy->theSum('2');
                    $pages = $global->thePages($theSum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = $count*($page-1);
                    if ($pages == 0) {
                        $page = 0;
                        $offset = 0;
                    }
                    $buyInfo = $buy->listHot($count, $offset, '2');
                    $pageNav = $global->pageNav($page, $pages, 'adminseeked');
                    $this->view->seekedClass = "active";
                    break;
                
                default:
                    $theSum = $buy->theSum('1');
                    $pages = $global->thePages($theSum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = $count*($page-1);
                    if ($pages == 0) {
                        $page = 0;
                        $offset = 0;
                    }
                    $buyInfo = $buy->listHot($count, $offset, '1');
                    $pageNav = $global->pageNav($page, $pages, 'adminseeking');
                    $this->view->seekingClass = "active";
                    break;
            }
            $this->view->buy = $buyInfo;
            $this->view->pageNav = $pageNav;
            $this->view->page = $page;
            $this->view->pages = $pages;
        }

        /**
         * 输出用户求书信息
         */
        public function userseekAction()
        {
            if (!$_SESSION['admin']) {
                $this->_redirect('/admin/login');
            }
            $count = 12;
            $buy = new Application_Model_Buy();
            $global = new Application_Model_Global();
            $user = new Application_Model_UserMapper();
            $userInfo  = $user->listHot('0', '50');
            $this->view->userInfo = $userInfo;
            $userId = $this->getRequest()->getParam('user-id');
            $userName = $this->getRequest()->getParam('user-name');
            if ($userName) {
                $userSearch = $user->findByName($userName);
                if ($userSearch) {
                    $userId = $userSearch['user_id'];
                }
            }
            if ($userId) {
                $_SESSION['searchUser'] = $userId;
                $userSearch = $user->findOne($userId);
            } else {
                if ($_SESSION['searchUser']) {
                    $userId = $_SESSION['searchUser'];
                    $userSearch = $user->findOne($userId);
                }
            }
            $type = $this->getRequest()->getParam('type');
            $page = $this->getRequest()->getParam('page');
            if (!$page) {
                $page = 1;
            }
            if ($userId) {
                switch ($type) {
                    case '3':
                        $theSum = $buy->userSum($userId, '1');
                        $pages = $global->thePages($theSum, $count);
                        if ($page > $pages) {
                            $page = $pages;
                        }
                        $offset = $count*($page-1);
                        if ($pages == 0) {
                            $page = 0;
                            $offset = 0;
                        }
                        $buyInfo = $buy->findByUserState($userId, $offset, '1', $count);
                        $pageNav = $global->pageNav($page, $pages, 'adminuserseeking');
                        $this->view->useekingClass = "active";
                        $this->view->searchType = "3";
                        break;

                    case '4':
                        $theSum = $buy->userSum($userId, '2');
                        $pages = $global->thePages($theSum, $count);
                        if ($page > $pages) {
                            $page = $pages;
                        }
                        $offset = $count*($page-1);
                        if ($pages == 0) {
                            $page = 0;
                            $offset = 0;
                        }
                        $buyInfo = $buy->findByUserState($userId, $offset, '2', $count);
                        $pageNav = $global->pageNav($page, $pages, 'adminuserseeking');
                        $this->view->useekedClass = "active";
                        $this->view->searchType = "4";
                        break;
                    
                    default:
                        $this->_redirect('/admin/seek');
                        break;
                }
            } else {
                if ($type == 3) {
                    $this->view->searchType = "3";
                } elseif ($type == 4) {
                    $this->view->searchType = "4";
                }
            }
            $this->view->buy = $buyInfo;
            $this->view->pageNav = $pageNav;
            $this->view->page = $page;
            $this->view->pages = $pages;
        }

        /**
         * 输出所有留言内容或者评论内容
         */
        public function messagereplyAction()
        {
            if (!$_SESSION['admin']) {
                $this->_redirect('/admin/login');
            }
            $count = 12;
            $person = new Application_Model_Person();
            $global= new Application_Model_Global();
            $page = $this->getRequest()->getParam('page');
            $type = $this->getRequest()->getParam('type');
            if (!$page) {
                $page = 1;
            }
            switch ($type) {
                case 'mes':
                    $theSum = $person->mesSum();
                    $pages = $global->thePages($theSum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = $count*($page-1);
                    if ($page == 0) {
                        $page = 0;
                        $offset = 0;
                    }
                    $messageInfo = $person->showAllMessage($offset, $count);
                    $this->view->mes = $messageInfo;
                    $this->view->page = $page;
                    $this->view->pages = $pages;
                    $pageNav = $global->pageNav($page, $pages, "adminmes");
                    $this->view->pageNav = $pageNav;
                    $this->render('mes');
                    break;

                case 'reply':
                    $theSum = $person->mesSum();
                    $pages = $global->thePages($theSum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = $count*($page-1);
                    if ($page == 0) {
                        $page = 0;
                        $offset = 0;
                    }
                    $messageInfo = $person->showAllMessage($offset, $count);
                    $this->view->mes = $messageInfo;
                    $replyInfo = $person->showReply($messageInfo['mes']);
                    $this->view->reply = $replyInfo;
                    $pageNav = $global->pageNav($page, $pages, "adminreply");
                    $this->view->pageNav = $pageNav;
                    $this->view->page = $page;
                    $this->view->pages = $pages;
                    $this->render('reply');
                    break;
                
                default:
                    $this->_redirect('/admin/index');
                    break;
            }
        }

        /**
         * 输出某用户的留言
         */
        public function usermessagereplyAction()
        {
            if (!$_SESSION['admin']) {
                $this->_redirect('/admin/login');
            }
            $count = 12;
            $person = new Application_Model_Person();
            $global= new Application_Model_Global();
            $user = new Application_Model_UserMapper();
            $userInfo  = $user->listHot('0', '50');
            $this->view->userInfo = $userInfo;
            $page = $this->getRequest()->getParam('page');
            if (!$page) {
                $page = 1;
            }
            $userId = $this->getRequest()->getParam('user-id');
            $userName = $this->getRequest()->getParam('user-name');
            if ($userName) {
                $userSearch = $user->findByName($userName);
                if ($userSearch) {
                    $userId = $userSearch['user_id'];
                }
            }
            if ($userId) {
                $_SESSION['searchUser'] = $userId;
                $userSearch = $user->findOne($userId);
            } else {
                if ($_SESSION['searchUser']) {
                    $userId = $_SESSION['searchUser'];
                    $userSearch = $user->findOne($userId);
                }
            }
            $type = $this->getRequest()->getParam('type');
            switch ($type) {
                case 'mes':
                    $userSum = $person->userMesSum($userId);
                    $pages = $global->thePages($userSum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = $count*($page-1);
                    if ($page == 0) {
                        $page = 0;
                        $offset = 0;
                    }
                    $messageInfo = $person->showMessage($userId, $offset, $count);
                    $this->view->mes = $messageInfo;
                    $pageNav = $global->pageNav($page, $pages, "adminusermes");
                    $this->view->pageNav = $pageNav;
                    $this->view->page = $page;
                    $this->view->pages = $pages;
                    $this->view->searchType = "mes";
                    $this->render('usermessage');
                    break;

                case 'reply':
                    $userSum = $person->userReplySum($userId);
                    $pages = $global->thePages($userSum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = $count*($page-1);
                    if ($page == 0) {
                        $page = 0;
                        $offset = 0;
                    }
                    $replyInfo = $person->showMessageByReply($userId, $offset, $count);
                    $this->view->reply = $replyInfo;
                    $pageNav = $global->pageNav($page, $pages, "adminureply");
                    $this->view->pageNav = $pageNav;
                    $this->view->page = $page;
                    $this->view->pages = $pages;
                    $this->view->searchType = "reply";
                    $this->render('ureply');
                    break;
                
                default:
                    $this->_redirect('/admin/index');
                    break;
            } 
        }

        /**
         * 输出网站用户信息
         */
        public function userAction()
        {
            if (!$_SESSION['admin']) {
                $this->_redirect('/admin/login');
            }
            $count = 12;
            $user = new Application_Model_UserMapper();
            $global = new Application_Model_Global();
            $page = $this->getRequest()->getParam('page');
            if (!$page) {
                $page = 1;
            }
            $userSum = $user->theSum();
            $pages = $global->thePages($userSum, $count);
            if ($page > $pages) {
                $page = $pages;
            }
            $offset = $count*($page-1);
            if ($page == 0) {
                $page = 0;
                $offset = 0;
            }
            $userInfo = $user->listHot($offset, $count);
            $this->view->user = $userInfo;
            $pageNav = $global->pageNav($page, $pages, "adminuser");
            $this->view->pageNav = $pageNav;
            $this->view->page = $page;
            $this->view->pages = $pages;
        }

        public function adminuserAction()
        {
            if (!$_SESSION['admin']) {
                $this->_redirect('/admin/login');
            }
            $admin = new Application_Model_AdminMapper();
            $name = $this->getRequest()->getParam('username');
            $pass = $this->getRequest()->getParam('password');
            $degree = $this->getRequest()->getParam('degree');
            if (isset($name) && isset($pass)) {
                $addRes = $admin->addOne($name, $pass, $degree);
            }
            $adminInfo = $admin->listAll();
            $this->view->admin = $adminInfo;
        }

        /**
         * 改变书籍交易状态
         */
        public function changebookstateAction()
        {
            $sale = new Application_Model_SaleMapper();
            $delBookId = $this->getRequest()->getParam('id');
            $type = $this->getRequest()->getParam('type');
            $res = $sale->updateState($delBookId, $type);
            if ($res) {
                echo "sucess";
                exit();
            } else {
                echo "fail";
                exit();
            }
        }

        /**
         * 改变求书状态
         */
        public function changebuystateAction()
        {
            $buy = new Application_Model_BuyMapper();
            $delBuyId = $this->getRequest()->getParam('id');
            $type = $this->getRequest()->getParam('type');
            $res = $buy->updateState($delBuyId, $type);
            if ($res) {
                echo "sucess";
                exit();
            } else {
                echo "fail";
                exit();
            }
        }

        /**
         * 改变用户状态
         */
        public function changeuserstateAction()
        {
            $user = new Application_Model_UserMapper();
            $userId = $this->getRequest()->getParam('id');
            $state = $this->getRequest()->getParam('state');
            $res = $user->updateState($userId, $state);
            if ($res) {
                echo "sucess";
                exit();
            } else {
                echo "fail";
                exit();
            }
        }

        /**
         * 删除卖书书籍
         */
        public function delbookAction()
        {
            $user = new Application_Model_UserMapper();
            $sbl = new Application_Model_SalebooklabelMapper();
            $sale = new Application_Model_SaleMapper();
            $book = new Application_Model_BookinfoMapper();
            $image = new Application_Model_Image();
            $bookId = $this->getRequest()->getParam('id');
            $bookInfo = $book->findOneById($bookId);
            $imageName = end(explode('/', $bookInfo['photo_path']));
            $res['sale'] = $sale->delOneByBook($bookId);
            $res['sbl'] = $sbl->delsblByBook($bookId);
            $res['book'] = $book->delOne($bookId);
            $res['image'] = $image->delImage($imageName);
            if ($res['book']) {
                echo "sucess";
                exit();
            } else {
                echo "fail";
                exit();
            }
        }

        /**
         * 删除求书
         */
        public function delbuyAction()
        {
            $buy = new Application_Model_BuyMapper();
            $buyId = $this->getRequest()->getParam('id');
            $delRes = $buy->delOne($buyId);
            if ($delRes) {
                echo "sucess";
                exit();
            } else {
                echo "fail";
                exit();
            }
        }

        /**
         * 删除评论
         */
        public function delreplyAction()
        {
            $reply = new Application_Model_ReplyMapper();
            $delReplyId = $this->getRequest()->getParam('id');
            $res = $reply->delOne($delReplyId);
            if ($res) {
                echo "sucess";
                exit();
            } else {
                echo "fail";
                exit();
            }
        }

        /**
         * 删除留言 相关的评论一并删除
         */
        public function delmesAction()
        {
            $message = new Application_Model_MessageMapper();
            $reply = new Application_Model_ReplyMapper();
            $delMesId = $this->getRequest()->getParam('id');
            $delReplyRes = $reply->delOneByMesId($delMesId);
            $delMesRes = $message->delOne($delMesId);
            if ($delMesRes) {
                echo "sucess";
                exit();
            } else {
                echo "fail";
                exit();
            }
        }
    }
?>
