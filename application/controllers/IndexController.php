<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $commen = new Application_Model_Commen($this->view);
        //输出layout共同内容
        $commen->theCommen();
    }

    public function indexAction()
    {
        //导航栏反白处理
        $this->view->indexClass = "nav-a-current";
        //输出最近正在交易
        $book = new Application_Model_Book();
        $hotBook = $book->listHot();
        $this->view->hotBook = $hotBook;
        //输出最近完成交易
        $pastBook = $book->listHot('2', '8');
        $this->view->pastBook = $pastBook;
        //输出活跃用户
        $user = new Application_Model_UserMapper();
        $hotUser = $user->listHot();
        $this->view->hotUser = $hotUser;
        //输出正在求书
        $buy = new Application_Model_Buy();
        $hotBuy = $buy->listHot('9');
        $this->view->hotBuy = $hotBuy;
         //输出最近漂流
        $drift = new Application_Model_DriftingMapper();
        $driftingInfo = $drift->listDrifting();
        $this->view->driftingInfo = $driftingInfo;
        //获取ip地址
        if(getenv('HTTP_CLIENT_IP')) { 
        $onlineip = getenv('HTTP_CLIENT_IP'); 
        } elseif(getenv('HTTP_X_FORWARDED_FOR')) { 
        $onlineip = getenv('HTTP_X_FORWARDED_FOR'); 
        } elseif(getenv('REMOTE_ADDR')) { 
        $onlineip = getenv('REMOTE_ADDR'); 
        } else { 
        $onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR']; 
        } 
//        echo $onlineip; 
    }

    /**
     * 输出分享区
     */
    public function buildingAction()
    {
        //导航栏反白处理
        $this->view->buildClass = "nav-a-current";
    }

    public function introductionAction()
    {
        
    }

    public function feedbackAction()
    {
        # code...
    }

    public function dbtestAction()
    {
        $this->_helper->layout->disableLayout();

    	// $col = new Application_Model_CollegeMapper();
    	// $res = $col->findAll();
    	// print_r($res);

        // $book = new Application_Model_BookinfoMapper();
        // $res = $book->findOneByNames('aa');
        // $res = $book->findMore();
        // $res = $book->showColums();
        // $res = $book->blurSearch("好");
        // $res = $book->multiBlurSearch("高数");
        // $res = $book->truncateWord("这是一本好书");
        // $res = $book->multiWordSearch("高数");
        // $res = $book->colSearch('2');
        // $res = $book->delOne('173');
        // $this->view->out = $res;

        // $buy = new Application_Model_BuyMapper();
        // //$res = $buy->listBuy();
        // // $res = $buy->updateState('1');
        // $res = $buy->insertOne('bnm', 'bcont', 'stranger', '123');
        // $this->view->out = $res;

        // $label = new Application_Model_LabelMapper();
        // $res = $label->listLabel();
        // // $res = $label->findOne('驾驶');
        // $res = $label->addLabel('a 你的 你好 ');
        // $res = $label->delLabel(array('72', '73'));
        // $this->view->out = $res;

        // $sbl = new Application_Model_SalebooklabelMapper();
        // // $res = $sbl->findMore('1');
        // $res = $sbl->insertOne('1', '4');
        // $res = $sbl->delsbl(array('508', '507'));
        // $this->view->out = $res;

        // $sale = new Application_Model_SaleMapper();
        // // $res = $sale->findByState('2');
        // $res = $sale->updateState('1');
        // $res = $sale->delOne('171');
        // $res = $sale->findByUserstate('1', '0');
        // $res = $sale->findAllUserState('1');
        // $this->view->out = $res;

        // $user = new Application_Model_UserMapper();
        // // $res = $user->findOne('1');
        // $res = $user->listHot();
        // $res = $user->updateState('2');
        // $this->view->out = $res;
        
        // $visitor = new Application_Model_VisitorMapper();
        // $date = date("Y-m-d H:i:s");
        // echo $date;
        // // $res = $visitor->('1');
        // $res = $visitor->addOne('1', '2');
        // $res = $visitor->userSum('1');
        // $this->view->out = $res;
        
        // $message = new Application_Model_MessageMapper();
        // //$res = $message->listMessage('1');
        // $res = $message->addOne('1', '2', 'haha');
        // $res = $message->userSum('1');
        // $res = $message->delOne('53');
        // $this->view->out = $res;
        
        // $reply = new Application_Model_ReplyMapper();
        // //$res = $reply->listReply('1');
        // $res = $reply->addOne('1', '2', 'ahhh');
        // $res = $reply->delOne('1');
        // $res = $reply->delOneByMesId('50');
        // $this->view->out = $res;
        
        // $feedback = new Application_Model_FeedbackMapper();
        // // $res = $feedback->addOne('hahh');
        // $res = $feedback->listFeedback();
        // $this->view->out = $res;
        
        // $book = new Application_Model_Book();
        // $res = $book->listHot();
        // // $res = $book->listLast();
        // // $res = $book->listOne('1313213');
        // $res = $book->addOne('bname', 'publish', 'author', 'iinfo', '20', '10', '2', '2013-9-10', '555', 'ab dd');
        // // $res = $book->addOne('2013-9-10', '555', 'ab dd');
        // $res = $book->theSum();
        // $res = $book->delInfo('177', '175', '75', '513');
        // $res = $book->listByUser('104', '0');
        // $res = $book->userSum('1');
        // $res = $book->setHistoryBook('2');
        // $res = $book->getHistoryBook();
        // $this->view->out = $res;
        
        // $search = new Application_Model_Search();
        // $res = $search->addAllInfo('1', '3');
        // $res = $search->labelSearch('6', '1');
        // $this->view->out = $res;
        
        // $global = new Application_Model_Global();
        // $res = $global->pageNav('6', '5', 'seek');
        // $this->view->out = $res;
        
        // $buy = new Application_Model_Buy();
        // $res = $buy->listHot();
        // $res = $buy->userSum('1');
        // $res = $buy->findByUserState('1');
        // $this->view->out = $res;
        
        // $image = new Application_Model_Image();
        // $image->delImage('2222.jpg');
        
        // $person = new Application_Model_Person();
        // $res = $person->showVisitor('1', '4');
        // $res = $person->showMessage('1');
        // $res['mes'] = $person->showMessage('1', '0');
        // $res['reply'] = $person->showReply($res['mes']['mes'], '0');
        // $this->view->out = $res;
        
        // $admin = new Application_Model_AdminMapper();
        // $res = $admin->delOne('3');
        // $this->view->out = $res;
        
		// $userCheck = new Application_Model_UserCheck();
		// $test = $useCheck->getBaseInfo('34');
		// var_dump('11');exit;
		
  //       $image = new Application_Model_Image();
  //       $res = $image->delImage("Koala.jpg");
  //       $this->view->out = $res;
    }
}

