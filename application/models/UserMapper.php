<?php

    /**
    *  操作user数据表的具体方法
    */
    class Application_Model_UserMapper  
    {
    	protected $user;
    	protected $userDb;
        protected $global;
    	
    	function __construct()
    	{
    		$this->user = new Application_Model_DbTable_User();
    		$this->userDb = $this->user->getAdapter();
            $this->global = new Application_Model_Global();
    	}

    	/**
    	 * 查找用户信息
    	 * @param  [num] $id  用户id
    	 * @return [one-dim array]
    	 */
    	public function findOne($id)
    	{
    		$where = $this->userDb->quoteInto('user_id = ?', $id);
    		$res = $this->user->fetchRow($where);
    		if ($res) {
    			$res = $res->toArray();
    		}
    		return $res;
    	}

        /**
         * 用过用户名查找用户信息
         * @param  [string] $name 用户名
         * @return [two-dim array]       
         */
        public function findByName($name)
        {
            $where = $this->userDb->quoteInto('user_name = ?', $name);
            $res = $this->user->fetchRow($where);
            if ($res) {
                $res = $res->toArray();
            }
            return $res;
        }

        /**
         * 列出最近登录的用户信息
         * @return [two-dim array]
         */
        public function listHot($offset = '0', $count = '10')
        {
            $order = "last_time DESC";
            $res = $this->user->fetchAll($where=null, $order, $count, $offset)->toArray();
            $res = $this->global->addUrl($res, 'twoUser');
            return $res;
        }

        /**
         * 增加用户到易书网
         * @param [num] $id    用户id
         * @param [string] $name 用户名
         */
        public function addOne($id, $name)
        {
            $facePath = "http://ucenter.hfutonline.net/avatar.php?uid=" . $id;
            $date = new Zend_Date();
            $date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Y-m-d H:i:s');
            $data = array(
                'user_id' => $id,
                'user_name' => $name,
                'face_path' => $facePath,
                'reg_time' => $time
                );
            $res = $this->user->insert($data);
            return $res;
        }

        /**
         * 修改用户状态
         * @param  [num] $id    用户id
         * @param  string $state 状态
         * @return [num]        受影响的行数
         */
        public function updateState($id, $state='2')
        {
            $where = $this->userDb->quoteInto('user_id = ?', $id);
            $data = array('state' => $state);
            $res = $this->user->update($data, $where);
            return $res;
        }

        /**
         * 更新用户电话
         * @param  [num] $id   用户id
         * @param  [string] $tel  电话号码
         * @return [num]      受影响的行数
         */
        public function updateTel($id, $tel)
        {
            $where = $this->userDb->quoteInto('user_id = ?', $id);
            $data = array('tel' => $tel);
            $res = $this->user->update($data, $where);
            return $res;
        }

        /**
         * 更新用户最后登录时间
         * @param  [num] $id  用户id
         * @return [num]      用户id
         */
        public function updateTime($id)
        {
            $where = $this->userDb->quoteInto('user_id = ?', $id);
            $date = new Zend_Date();
            $date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Y-m-d H:i:s');
            $data = array('last_time' => $time);
            $res = $this->user->update($data, $where);
            return $res;
        }

        /**
         * 计算用户总数
         * @return [num] 
         */
        public function theSum()
        {
            $sum = $this->user->fetchAll()->count();
            return $sum;
        }
    }

?>
