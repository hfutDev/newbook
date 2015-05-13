<?php

    /**
    *  处理求书的相关方法
    */
    class Application_Model_Buy
    {
    	protected $buy;
    	protected $user;
    	protected $global;
    	
    	function __construct()
    	{
    		$this->buy = new Application_Model_BuyMapper();
    		$this->user = new Application_Model_UserMapper();
    		$this->global = new Application_Model_Global();
    	}

    	/**
    	 * 列出有关求书的信息，包括用户信息
    	 * @param  string $count  求书信息数量
    	 * @param  string $offset 数据开始位置
    	 * @return [three-dim array]       
    	 */
    	public function listHot($count='9', $offset='0', $state='1')
    	{
    		$buyInfo = $this->buy->listBuy($count, $offset, $state);
    		for ($i=0; $i < count($buyInfo); $i++) {
                $buyInfo[$i]['buy_bookname'] = str_replace('\'', '', $buyInfo[$i]['buy_bookname']);
                // $buyInfo[$i]['buy_bookname'] = substr($buyInfo[$i]['buy_bookname'], 1, strlen($buyInfo[$i]['buy_bookname'])-1);
    			$userInfo[$i] = $this->user->findOne($buyInfo[$i]['buy_userid']);
    			$userInfo[$i] = $this->global->addUrl($userInfo[$i], 'user');
    		}
    		$res['buy'] = $buyInfo;
    		$res['user'] = $userInfo;
    		return $res;
     	}

        /**
         * 根据用户id和求书状态查询信息
         * @param  [num] $uid     用户id
         * @param  [num] $offset  数据开始位置
         * @param  string $state  求书状态
         * @param  string $count  数据量
         * @return [two-dim array]         
         */
        public function findByUserState($uid, $offset='0', $state='1', $count='5')
        {
            $buyInfo = $this->buy->listByUserState($uid, $offset, $state, $count);
            return $buyInfo;
        }

        /**
         * 增加求书记录
         * @param [string] $bname   书名
         * @param [string] $content 喊话内容
         * @param [string] $uname   用户名称
         * @param [num] $uid     用户id
         */
        public function addOne($bname, $content, $uname, $uid)
        {
            $bname = $this->global->checkHtml($bname);
            $content = $this->global->checkHtml($content);
            $res = $this->buy->insertOne($bname, $content, $uname, $uid);
            return $res;
        }

        /**
         * 列出求书信息总数
         */
        public function theSum($state='1')
        {
            $buyInfo = $this->buy->listAll($state);
            $sum = count($buyInfo);
            return $sum;
        }

        public function userSum($uid, $state='1')
        {
            $sum = $this->buy->listAllUserState($uid, $state);
            return $sum;
        }
    }
?>