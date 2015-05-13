<?php

    /**
    * 捐赠板块所有操作方法
    */
    class DriftController extends Zend_Controller_Action
    {
    	public function init()
        {
            session_start();
            $commen = new Application_Model_Commen($this->view);
            //输出layout共同内容
            $commen->theCommen();
            $this->view->driftClass = "nav-a-current";
        }
        
        /**
         * 读书漂流首页
         */
    	public function indexAction()
    	{
    		$drift = new Application_Model_DriftingMapper();
            $global = new Application_Model_Global();
            $type = $this->getRequest()->getParam('order');
            $count = '8';
            $page = $this->getRequest()->getParam('page');
            if (!$page || !is_numeric($page)) {
                $page = 1;
            }
            switch ($type) {
                case 'both':
                    $sum = $drift->theSum(null);
                    $pages = $global->thePages($sum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = ($page-1)*$count;
                    if ($page == $pages) {
                        $count = $sum-($page-1)*$count;
                    }
                    if (!$sum) {
                        $offset =0;
                    }
                    $driftInfo = $drift->listAll($offset, $count);
                    $driftPathInfo = $drift->addPath($driftInfo);
                    $pageNav = $global->pageNav($page, $pages, 'drift');
                    $this->view->allStyle = "selected";
                    break;

                case 'in':
                    $sum = $drift->theSum('1');
                    $pages = $global->thePages($sum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = ($page-1)*$count;
                    if ($page == $pages) {
                        $count = $sum-($page-1)*$count;
                    }
                    if (!$sum) {
                        $offset =0;
                    }
                    $driftInfo = $drift->listDrifting($offset, $count, '1');
                    $driftPathInfo = $drift->addPath($driftInfo);
                    $pageNav = $global->pageNav($page, $pages, 'driftin');
                    $this->view->inStyle = "selected";
                    break;

                case 'out':
                    $sum = $drift->theSum('2');
                    $pages = $global->thePages($sum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = ($page-1)*$count;
                    if ($page == $pages) {
                        $count = $sum-($page-1)*$count;
                    }
                    if (!$sum) {
                        $offset =0;
                    }
                    $driftInfo = $drift->listDrifting($offset, $count, '2');
                    $driftPathInfo = $drift->addPath($driftInfo);
                    $pageNav = $global->pageNav($page, $pages, 'driftout');
                    $this->view->outStyle = "selected";
                    break;
                
                default:
                    $sum = $drift->theSum(null);
                    $pages = $global->thePages($sum, $count);
                    if ($page > $pages) {
                        $page = $pages;
                    }
                    $offset = ($page-1)*$count;
                    if ($page == $pages) {
                        $count = $sum-($page-1)*$count;
                    }
                    if (!$sum) {
                        $offset =0;
                    }
                    $driftInfo = $drift->listAll($offset, $count);
                    $driftPathInfo = $drift->addPath($driftInfo);
                    $pageNav = $global->pageNav($page, $pages, 'drift');
                    $this->view->allStyle = "selected";
                    break;
            }
            $this->view->driftInfo = $driftInfo;
            $this->view->driftPathInfo = $driftPathInfo;
            $this->view->pageNav = $pageNav;
            $this->view->page = $page;
            $this->view->pages = $pages;
    	}

        /**
         * 获取漂流书籍额外信息
         */
        public function dpathAction()
        {
            $driftPath = new Application_Model_DriftpathMapper();
            $drifting = new Application_Model_DriftingMapper();
            $driftId = $this->getRequest()->getParam('id');
            $driftInfo = $drifting->findOne($driftId);
            $driftPathInfo = $driftPath->findByDrift($driftId);
            if ($driftInfo['drift_state'] != 1) {
                $this->view->driftPathInfo = $driftPathInfo;
                // print_r($driftPathInfo);
                // $this->render('a');
            } else {
                $lastTime = $driftPathInfo['0']['time'];
                if (substr($lastTime, 4, 5) == 12) {
                    $nextTime = substr($lastTime, 0, 4)+1 . "01" . substr($lastTime, 6);
                } else {
                    $nextTime = substr($lastTime, 0, 4) . substr($lastTime, 4, 2)+1 . substr($lastTime, 6);
                }
                // echo $nextTime;
                $this->render('b');
            }
            exit();
        }

        public function todriftchkAction()
        {
            $todrift = new Application_Model_TodriftMapper();
            $user = $this->getRequest()->getParam('name');
            $id = $this->getRequest()->getParam('bookID');
            $tel = $this->getRequest()->getParam('tel');
            $type = $this->getRequest()->getParam('type');
            $res = $todrift->addOne($user, $id, $tel, $type);
            if ($res) {
                echo "suc";
            } else {
                echo "fail";
            }
            exit();
        }

        /**
         * 后台管理页面
         */
        public function adminAction()
        {
            //检查登录
            if (!$_SESSION['drift_admin']) {
                 $this->_redirect('/drift/login');
            }
            $todrift = new Application_Model_TodriftMapper();
            $drifting = new Application_Model_DriftingMapper();
            $global = new Application_Model_Global();
            $type = $this->getRequest()->getParam('type');
            $page = $this->getRequest()->getParam('page');
            if ($type == null || ($type != 1 && $type != 2)) {
                $type = '1';
            }
            if (!$page || !is_numeric($page)) {
                $page = 1;
            }
            $count = '6';
            $sum = $todrift->theSum($type);
            $pages = $global->thePages($sum, $count);
            if ($page > $pages) {
                $page = $pages;
            }
            $offset = ($page-1)*$count;
            if ($page == $pages) {
                $count = $sum-($page-1)*$count;
            }
            if (!$sum) {
                $offset =0;
            }
            switch ($type) {
                case '1':
                    $this->view->inStyle = "active";
                    $pageNav = $global->pageNav($page, $pages, 'listorder1');
                    break;
                
                case '2':
                    $this->view->outStyle = "active";
                    $pageNav = $global->pageNav($page, $pages, 'listorder2');
                    break;
            }
            $todriftInfo = $todrift->listAll($offset, $count, $type);
            for ($i=0; $i < count($todriftInfo); $i++) {
                $driftInfo[$i] = $drifting->findOne($todriftInfo[$i]['d_id']);
            }
            $this->view->todriftInfo = $todriftInfo;
            $this->view->driftInfo = $driftInfo;
            $this->view->pageNav = $pageNav;
            $this->view->page = $page;
            $this->view->pages = $pages;
        }
 
        /**
         * 添加漂流书籍信息表单页面
         */
        public function todriftingAction()
        {
            //登录检查
            if (!$_SESSION['drift_admin']) {
                 $this->_redirect('/drift/login');
            }
        }

        /**
         * 增加漂流书籍表单检查
         */
        public function todriftingchkAction()
        {
            $image = new Application_Model_Imaged("drift/", "drift_");
            $drifting = new Application_Model_DriftingMapper();
            $bookName = $this->getRequest()->getParam('bookname');
            $bookOwner = $this->getRequest()->getParam('owner');
            $file = $this->getRequest()->getParam('book-cover');
            $content = $this->getRequest()->getParam('book-content');
            $addResult = $drifting->addOne($bookName, $content, $bookOwner);
            if ($addResult) {
                $imageRes = $image->fileUpload($file, $addResult);
                if ($imageRes) {
                    $updateResult = $drifting->updatePath($addResult, $imageRes);
                    if ($updateResult) {
                        $alertValue = array('a' => "漂流书籍信息发布成功~~~", 'b'=> 'todrifting');
                        $this->_forward('ok', 'global', null, $alertValue);
                    } else {
                        $failInfo = "Bad!木有发布成功...";
                        $alertValue = array('a' => $failInfo);
                        $this->_forward('bad', 'global', null, $alertValue);
                    }
                } else {
                    $failInfo = "Bad!木有发布成功...";
                    $alertValue = array('a' => $failInfo);
                    $this->_forward('bad', 'global', null, $alertValue);
                }
            } else {
                $failInfo = "Bad!木有发布成功...";
                $alertValue = array('a' => $failInfo);
                $this->_forward('bad', 'global', null, $alertValue);
            }
        }

        /**
         * 输出增加漂流轨迹表单页面
         */
        public function todriftpathAction()
        {
            //登录检查
            if (!$_SESSION['drift_admin']) {
                $this->_redirect('/drift/login');
            }
            $drifting = new Application_Model_DriftingMapper();
            $driftInfo = $drifting->listAll('0', '100');
            $this->view->driftInfo = $driftInfo;
        }

        /**
         * 检查处理增加漂流轨迹表单数据
         */
        public function todriftpathchkAction()
        {
            $driftPath = new Application_Model_DriftpathMapper();
            $id = $this->getRequest()->getParam('drift-id');
            $name = $this->getRequest()->getParam('user');
            $res = $driftPath->addOne($id, $name);
            if ($res) {
                echo "suc";
            } else {
                echo "fail";
            }
            exit();
        }

        /**
         * 输出后台登陆页面
         */
        public function loginAction()
        {
            # code...
        }

        /**
         * 登录检查
         */
        public function loginchkAction()
        {
            $admin = new Application_Model_AdminMapper();
            $name = $this->getRequest()->getParam('user');
            $pass = $this->getRequest()->getParam('password');
            $res = $admin->findOne($name, $pass);
            if ($res) {
                if ($res['degree'] == 'drift') {
                    $_SESSION['drift_admin'] = $res['name'];
                    $this->_redirect('/drift/admin');
                } else {
                    $this->_redirect('/drift/login');
                }
            } else {
                $this->_redirect('/drift/login');
            }
        }

        /**
         * 注销登录
         */
        public function logoutAction()
        {
            unset($_SESSION['drift_admin']);
            $this->_redirect('/drift');
        }
    }
?>