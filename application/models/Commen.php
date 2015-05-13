<?php

    /**
    * 网站共同内容输出部分
    */
    class Application_Model_Commen 
    {
    	
    	function __construct($view)
	   	{
	   		$this->view = $view;
	   	}

        public function theCommen()
        {
            // 判断是否登录
            $userCheck = new Application_Model_UserCheck();
            $userInfo = $userCheck->checklogin();
           // var_dump($userInfo);
            session_start();
            if ($userInfo['bool'] == 1) {
                //更新登录时间
                $user = new Application_Model_UserMapper();
                //检查用户是否初始化到user表中
                $userJudge = $user->findOne($userInfo['info']['uid']);
                if ($userJudge) {
                    $user->updateTime($userInfo['info']['uid']);
                } else {
                    $user->addOne($userInfo['info']['uid'], $userInfo['info']['username']);
                    $user->updateTime($userInfo['info']['uid']);
                }
                $_SESSION['uid'] = $userInfo['info']['uid'];
                $_SESSION['username'] = $userInfo['info']['username'];
                $headerInfo['user'] = $userInfo['info']['username'];
                $headerInfo['personUrl'] = "/person/index/person/" . $_SESSION['uid'];
                $headerInfo['logoutName'] = "注销";
                $headerInfo['logoutUrl'] = "http://user.hfutonline.net/login/logout?from=http://book.hfutonline.net";
            } else {
                $headerInfo['user'] = "登录";
                $headerInfo['personUrl'] = "http://user.hfutonline.net/login?from=http://book.hfutonline.net";
                $headerInfo['logoutName'] = "注册";
                $headerInfo['logoutUrl'] = "http://user.hfutonline.net/register/basic";
            }
            $this->view->headerInfo = $headerInfo;
            //学院输出模块
            $college = new Application_Model_CollegeMapper();
            $col = $college->findAll();
            $this->view->col = $col;
            //输出模块
            $category = new Application_Model_CategoryMapper();
            $category = $category->findAll();
            $this->view->category = $category;
            //热门标签输出模块
            $label = new Application_Model_LabelMapper();
            $hotLabel = $label->listLabel();
            $this->view->hotLabel = $hotLabel;
        }

        public function thePerson($uid)
        {
            $user = new Application_Model_UserMapper();
            $sale = new Application_Model_SaleMapper();
            $book = new Application_Model_Book();
            $buy = new Application_Model_Buy();
            $message = new Application_Model_MessageMapper();
            $visitor = new Application_Model_VisitorMapper();
            if ($uid == $_SESSION['uid']) {
                $person['detail'] = $user->findOne($uid);
                $person['info'] = "个人信息";
                $person['infoUrl'] = "";
                $person['book'] = "我的易书";
                $person['bookUrl'] = "/person/perguestbook/type/per";
                $person['publishing'] = "发布中";
                $person['publishingUrl'] = "/person/perpublish/type/publishing";
                $person['publishingSum'] = $sale->userSum($uid, '1');
                $person['seeking'] = "求书中";
                $person['seekingSum'] = $buy->userSum($uid, '1');
                $person['seekingUrl'] = "/person/perseek/type/seeking";
                $person['published'] = "已完成的卖书";
                $person['publishedUrl'] = "/person/perpublish/type/published";
                $person['publishedSum'] = $sale->userSum($uid, '2');
                $person['seeked'] = "已完成的求书";
                $person['seekedUrl'] = "/person/perseek/type/seeked";
                $person['seekedSum'] = $buy->userSum($uid, '2');
                $person['message'] = "留言板";
                $person['messageUrl'] = "/person/mes/type/per";
                $person['messageSum'] = $message->userSum($uid);
                $person['visitor'] = "最近访客";
                $person['visitorUrl'] = "/person/visitor/type/per";
                $person['visitorSum'] = $visitor->userSum($uid);
                $person['indetity'] = "master";
            } else {
                $person['detail'] = $user->findOne($uid);
                $person['info'] = "TA的信息";
                $person['infoUrl'] = "";
                $person['book'] = "TA的易书";
                $person['bookUrl'] = "/person/perguestbook/type/guest";
                $person['publishing'] = "TA的发布";
                $person['publishingUrl'] = "/person/guestpublish";
                $person['publishingSum'] = $sale->userSum($uid, '1');
                $person['seeking'] = "TA的求书";
                $person['seekingUrl'] = "/person/guestseek";
                $person['seekingSum'] = $buy->userSum($uid, '1');
                $person['message'] = "TA的留言板";
                $person['messageUrl'] = "/person/mes/type/guest";
                $person['messageSum'] = $message->userSum($uid);
                $person['visitor'] = "TA的访客";
                $person['visitorUrl'] = "/person/visitor/type/guest";
                $person['visitorSum'] = $visitor->userSum($uid);
                $person['indetity'] = "guest";
            }
            $this->view->person = $person;
            $hotBook = $book->listHot('1', '4', '0');
            $this->view->recentBook = $hotBook['book'];
        }
    }

?>
