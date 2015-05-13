<?php

    /**
    * 捐赠板块所有操作方法
    */
    class DonateController extends Zend_Controller_Action
    {

        public function init()
        {
            $commen = new Application_Model_Commen($this->view);
            //输出layout共同内容
            $commen->theCommen();
            $this->view->donateClass = "nav-a-current";
        }
    	
        /**
         * 捐赠页面首页
         */
    	public function indexAction()
    	{
            $college = new Application_Model_CollegeMapper();
            $global = new Application_Model_Global();
            $donate = new Application_Model_DonateMapper();
            $count = '6';
            $page = $this->getRequest()->getParam('page');
            if (!$page || !is_numeric($page)) {
                $page = 1;
            }
            $sum = $donate->theSum();
            $pages = $global->thePages($sum, $count);
            if ($page > $pages) {
                $page = 1;
            }
            $offset = ($page-1)*$count;
            if ($page == $pages) {
                $count = $sum-($page-1)*$count;
            }
            if (!$sum) {
                $offset =0;
            }
            $donateInfo = $donate->listDonate($offset, $count);
            for ($i=0; $i < count($donateInfo); $i++) { 
                $colName = $college->findOne($donateInfo[$i]['college']);
                $donateInfo[$i]['col'] = $colName['college'];
            }
            $this->view->donateInfo = $donateInfo;
            $this->view->page = $page;
    	}

        /**
         * 输出增加捐赠书籍信息页面
         */
    	public function todonateAction()
    	{
    		//登录检查
            if (!$_SESSION['donate_admin']) {
                $this->_redirect('/donate/login');
            }
    	}

        /**
         * 增加捐赠书籍信息表单检查
         */
        public function todonatechkAction()
        {
            $donate = new Application_Model_DonateMapper();
            $image = new Application_Model_Imaged("donate/", "donate_");
            $donator = $this->getRequest()->getParam('donatename');
            $bookName = $this->getRequest()->getParam('bookname');
            $college = $this->getRequest()->getParam('college');
            $file = $this->getRequest()->getParam('book-cover');
            $donatorWish = $this->getRequest()->getParam('donator-wish');
            $volunteerWish = $this->getRequest()->getParam('volunteer_wish');
            $addResult = $donate->addOne($donator, $college, $bookName, $donatorWish, $volunteerWish);
            if ($addResult) {
                $imageRes = $image->fileUpload($file, $addResult);
                if ($imageRes) {
                    $updateRes = $donate->updatePath($addResult, $imageRes);
                    if ($updateRes) {
                        $alertValue = array('a' => "捐赠书籍信息发布成功~~~", 'b'=> 'todrifting');
                        $this->_forward('ok', 'global', null, $alertValue);
                    } else {
                        $failInfo = "Bad!木有发布成功...";
                        $alertValue = array('a' => $failInfo);
                        $this->_forward('bad', 'global', null, $alertValue);
                    }
                } else {
                    $donate->delOne($addResult);
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
         * 输出完善受赠者信息页面
         */
        public function doneeAction()
        {
            //登录检查
            if (!$_SESSION['donate_admin']) {
                $this->_redirect('/donate/login');
            }
            $donate = new Application_Model_DonateMapper();
            $donateId = $this->getRequest()->getParam('id');
            $donateInfo = $donate->findOne($donateId);
            $this->view->donateInfo = $donateInfo;
        }

        /**
         * 完善捐赠信息 表单检查
         */
        public function doneechkAction()
        {
            $donate = new Application_Model_DonateMapper();
            $id = $this->getRequest()->getParam('drift-id');
            $donee = $this->getRequest()->getParam('doneename');
            $sex = $this->getRequest()->getParam('sex');
            $address = $this->getRequest()->getParam('address');
            $birth = $this->getRequest()->getParam('expiration-date');
            $wish = $this->getRequest()->getParam('wish');
            $res = $donate->updateOne($id, $donee, $sex, $birth, $address, $wish);
            if ($res) {
                $alertValue = array('a' => "捐赠书籍信息发布成功~~~", 'b'=> 'donee');
                $this->_forward('ok', 'global', null, $alertValue);
            } else {
                $failInfo = "Bad!木有发布成功...";
                $alertValue = array('a' => $failInfo);
                $this->_forward('bad', 'global', null, $alertValue);
            }
        }

        /**
         * 增加我要捐赠信息  ajax
         */
        public function idonatechkAction()
        {
            $iDonate = new Application_Model_TodonateMapper();
            $toName = $this->getRequest()->getParam('uName');
            $book = $this->getRequest()->getParam('bName');
            $col = $this->getRequest()->getParam('col');
            $tel = $this->getRequest()->getParam('tel');
            $res = $iDonate->addOne($toName, $col, $tel, $book);
            if ($res) {
                echo "suc";
            } else {
                echo "fail";
            }
            exit();
        }

        /**
         * 捐赠板块后台管理页面
         */
        public function adminAction()
        {
            //登录检查
            if (!$_SESSION['donate_admin']) {
                $this->_redirect('/donate/login');
            }
            $donate = new Application_Model_DonateMapper();
            $toDonate = new Application_Model_TodonateMapper();
            $global = new Application_Model_Global();
            $college = new Application_Model_CollegeMapper();
            $type = $this->getRequest()->getParam('type');
            $page = $this->getRequest()->getParam('page');
            if (!$page || !is_numeric($page)) {
                $page = 1;
            }
            if ($type == null || ($type != 1 && $type != 2 && $type != 3 && $type != 4)) {
                $type = '1';
            }
            $count = '6';
            switch ($type) {
                case '1':
                    $sum = $toDonate->theSum('2');
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
                    $toDonateInfo = $toDonate->listToDonate($offset, $count, '2');
                    for ($i=0; $i < count($toDonateInfo); $i++) { 
                        $colInfo = $college->findOne($toDonateInfo[$i]['college']);
                        $toDonateInfo[$i]['college'] = $colInfo['college'];
                    }
                    $this->view->toDonateInfo = $toDonateInfo;
                    $pageNav = $global->pageNav($page, $pages, 'donateadmin1');
                    $this->view->pageNav = $pageNav;
                    $this->view->page = $page;
                    $this->view->pages = $pages;
                    $this->view->inStyle = "active";
                    $this->render('todonating');
                    break;

                case '2':
                    $sum = $toDonate->theSum('1');
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
                    $toDonateInfo = $toDonate->listToDonate($offset, $count, '1');
                    for ($i=0; $i < count($toDonateInfo); $i++) { 
                        $colInfo = $college->findOne($toDonateInfo[$i]['college']);
                        $toDonateInfo[$i]['college'] = $colInfo['college'];
                    }
                    $this->view->toDonateInfo = $toDonateInfo;
                    $pageNav = $global->pageNav($page, $pages, 'donateadmin2');
                    $this->view->pageNav = $pageNav;
                    $this->view->page = $page;
                    $this->view->pages = $pages;
                    $this->view->outStyle = "active";
                    $this->render('todonating');
                    break;

                case '3':
                    $sum = $donate->theSum('2');
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
                    $donateInfo = $donate->listDonate($offset, $count, '2');
                    for ($i=0; $i < count($donateInfo); $i++) { 
                        $colInfo = $college->findOne($donateInfo[$i]['college']);
                        $donateInfo[$i]['college'] = $colInfo['college'];
                    }
                    $this->view->donateInfo = $donateInfo;
                    $pageNav = $global->pageNav($page, $pages, 'donateadmin3');
                    $this->view->pageNav = $pageNav;
                    $this->view->page = $page;
                    $this->view->pages = $pages;
                    $this->view->inStyle = "active";
                    $this->render('donating');
                    break;

                case '4':
                    $sum = $donate->theSum('1');
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
                    $donateInfo = $donate->listDonate($offset, $count, '1');
                    for ($i=0; $i < count($donateInfo); $i++) { 
                        $colInfo = $college->findOne($donateInfo[$i]['college']);
                        $donateInfo[$i]['college'] = $colInfo['college'];
                    }
                    $this->view->donateInfo = $donateInfo;
                    $pageNav = $global->pageNav($page, $pages, 'donateadmin4');
                    $this->view->pageNav = $pageNav;
                    $this->view->page = $page;
                    $this->view->pages = $pages;
                    $this->view->outStyle = "active";
                    $this->render('donating');
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        /**
         * 改变我要捐赠预约信息处理状态 ajax
         */
        public function updatetodonateAction()
        {
            $todonate = new Application_Model_TodonateMapper();
            $id = $this->getRequest()->getParam('delID');
            $res = $todonate->updateState($id);
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
                if ($res['degree'] == 'donate') {
                    $_SESSION['donate_admin'] = $res['name'];
                    $this->_redirect('/donate/admin');
                } else {
                    $this->_redirect('/donate/login');
                }
            } else {
                $this->_redirect('/donate/login');
            }
        }

        /**
         * 注销登录
         */
        public function logoutAction()
        {
            unset($_SESSION['donate_admin']);
            $this->_redirect('/donate');
        }

        public function tableAction()
        {
            # code...
        }
    }

?>
