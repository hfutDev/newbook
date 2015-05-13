<?php

    /**
    *  一些基本的，公用的action
    */
    class GlobalController extends Zend_Controller_Action
    {

        /**
         * 输出验证码
         */
    	public function vcodeAction()
    	{ 
    		$width = 70;
    		$height = 28;
    		$size = 30;
    		session_start();
    		//验证码
    		$str = "23456789abcdefghijkmnpqrstuvwxyz";
    		$code = "";
    		for ($i=0; $i < 4; $i++) { 
    			$code .= $str[mt_rand(0, strlen($str)-1)];
    		}
    		//创建图像
    		$img = imagecreatetruecolor($width, $height);
    		//定义要用到的颜色
    		$backColor = imagecolorallocate($img, 235, 236, 237);
    		$borderColor = imagecolorallocate($img, 118, 151, 199);
		    $textColor = imagecolorallocate($img, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
		    //画背景
		    imagefilledrectangle($img, 0, 0, $width, $width, $backColor);
		    //画边框
		    imagerectangle($img, 0, 0, $width-1, $height-1, $borderColor);
		    // 画干扰线
			for($i = 0;$i < 5; $i++) {
				$fontColor = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
				imagearc($img, mt_rand(-$width, $width), mt_rand(-$height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $fontColor);
			}
			// 画干扰点
			for($i = 0;$i < 50;$i++) {
				$fontColor = imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
				imagesetpixel($img, mt_rand(0, $width), mt_rand(0, $height), $fontColor);
			}
			//画验证码
			imagestring($img, $size, 10, 8, $code, $textColor);
			$_SESSION['vCode'] = $code;
			header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
			header("Content-type: image/jpeg;charset=utf8");
			imagejpeg($img);
			imagedestroy($img);
			exit();
    	}

        /**
         * 验证码验证
         */
        public function vcodechkAction()
        {
            session_start();
            $vcode = $this->getRequest()->getParam('valCode');
            if ($vcode == $_SESSION['vCode']) {
                $judge = 'sucess';
            } else {
                $judge = 'fail';
            }
            echo $judge;
            exit();
        }

        /**
         * 没有登录转向页面
         */
        public function logbadAction()
        {
            $this->_helper->layout->setlayout('layout');
            $url = $this->getRequest()->getParam('url');
            $changeUrl = "http://user.hfutonline.net/login?from=http://book.hfutonline.net" . $url;
            $this->view->changeUrl = $changeUrl;
        }

        public function personbadAction()
        {
            # code...
        }

        /**
         *  信息发布成功后跳转提示
         */
        public function okAction()
        {
            $alertValue = $this->getRequest()->getParam('a');
            $change = $this->getRequest()->getParam('b');
            $this->view->alertValue = $alertValue;
            if ($change == "publish") {
                $this->view->nextPosition = "发布区";
                $this->view->nextUrl = "/book/publish";
                $this->view->nextDo = "发布";
                $this->view->continue = "/book/topublish";
            } elseif ($change == "seek") {
                $this->view->nextPosition = "求书区";
                $this->view->nextUrl = "/book/seek";
                $this->view->nextDo = "求书";
                $this->view->continue = "/book/toseek";
            } elseif ($change == "todonate") {
                $this->view->nextPosition = "捐赠区";
                $this->view->nextUrl = "/donate";
                $this->view->nextDo = "继续添加";
                $this->view->continue = "/donate/todonate";
            } elseif ($change == "donee") {
                $this->view->nextPosition = "捐赠信息管理";
                $this->view->nextUrl = "/donate/admin/type/3";
                $this->view->nextDo = "继续添加";
                $this->view->continue = "/donate/admin/type/3";
            } elseif ($change == "todrifting") {
                $this->view->nextPosition = "漂流书籍信息管理";
                $this->view->nextUrl = "/drift/admin";
                $this->view->nextDo = "继续添加";
                $this->view->continue = "/drift/todrifting";
            }
        }

        /**
         * 信息发布失败是时页面跳转
         * @return [type] [description]
         */
        public function badAction()
        {
            $alertValue = $this->getRequest()->getParam('a');
            $this->view->alertValue = $alertValue;
        }
    }

?>
