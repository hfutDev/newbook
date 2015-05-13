<?php

    /**
    *  个人中心Model所有操作
    */
    class Application_Model_Person
    {
    	
    	function __construct()
    	{
    		# code...
    	}

        /**
         * 根据visitor表信息查找访客信息
         * @param  [num] $uid    用户id
         * @param  [num] $count  记录数
         * @return [two-dim array]       
         */
        public function showVisitor($uid, $count)
        {
            $visitor = new Application_Model_VisitorMapper();
            $user = new Application_Model_UserMapper();
            $global = new Application_Model_Global();
            $visitorInfo = $visitor->listVisitor($uid, $count);
            foreach ($visitorInfo as $k => $v) {
                $userInfo[$k] = $user->findOne($v['guest_id']);
                $userInfo[$k] = $global->addUrl($userInfo[$k], 'user');
            }
            return $userInfo;
        }

        /**
         * 列出所有留言信息，加入用户信息
         * @param  string $offset 数据开始位置
         * @param  string $count  数据量
         * @return [three-dim array]  
         */
        public function showAllMessage($offset='0', $count='12')
        {
            $message = new Application_Model_MessageMapper();
            $user = new Application_Model_UserMapper();
            $global = new Application_Model_Global();
            $mesInfo = $message->listAll($offset, $count);
            foreach ($mesInfo as $k => $v) {
                $guserInfo[$k] = $user->findOne($v['guest_id']);
                $guserInfo[$k] = $global->addUrl($guserInfo[$k], 'user');
                $peruserInfo[$k] = $user->findOne($v['uid']);
                $peruserInfo[$k] = $global->addUrl($peruserInfo[$k], 'user');
            }
            $res['mes'] = $this->emotion($mesInfo, 'mes');
            $res['muser'] = $guserInfo;
            $res['peruser'] = $peruserInfo;
            return $res;
        }
        
        /**
         * 根据message表中信息查询留言者信息
         * @param  [num] $uid     用户id
         * @param  string $offset 数据开始位置
         * @return [three-dim array]        
         */
        public function showMessage($uid, $offset='0', $count='3')
        {
            $message = new Application_Model_MessageMapper();
            $user = new Application_Model_UserMapper();
            $global = new Application_Model_Global();
            $mesInfo = $message->listMessage($uid, $offset, $count);
            foreach ($mesInfo as $k => $v) {
                $guserInfo[$k] = $user->findOne($v['guest_id']);
                $guserInfo[$k] = $global->addUrl($guserInfo[$k], 'user');
                $peruserInfo[$k] = $user->findOne($v['uid']);
                $peruserInfo[$k] = $global->addUrl($peruserInfo[$k], 'user');
            }
            $res['mes'] = $this->emotion($mesInfo, 'mes');
            $res['muser'] = $guserInfo;
            $res['peruser'] = $peruserInfo;
            return $res;
        }

        public function showMessageByReply($uid, $offset='0', $count='12')
        {
            $message = new Application_Model_MessageMapper();
            $reply = new Application_Model_ReplyMapper();
            $user = new Application_Model_UserMapper();
            $global = new Application_Model_Global();
            $userInfo = $user->findOne($uid);
            $replyInfo = $reply->findByUser($uid, $offset, $count);
            foreach ($replyInfo as $rk => $rv) {
                $messageInfo[$rk] = $message->findOne($rv['mid']);
                $peruserInfo[$rk] = $user->findOne($messageInfo[$rk]['uid']);
                $peruserInfo[$k] = $global->addUrl($peruserInfo[$k], 'user');
                $guserInfo[$rk] = $user->findOne($messageInfo[$rk]['guest_id']);
                $guserInfo[$k] = $global->addUrl($guserInfo[$k], 'user');
            }
            $res['searchuser'] = $userInfo;
            $res['mes'] = $this->emotion($messageInfo, 'mes');
            $res['reply'] = $this->emotion($replyInfo, 'reply');
            $res['peruser'] = $peruserInfo;
            $res['guser'] = $guserInfo;
            return $res;
        }

        /**
         * 全部留言总数
         * @return [num] 留言总数 
         */
        public function mesSum()
        {
            $message = new Application_Model_MessageMapper();
            $sum = $message->theSum();
            return $sum;
        }

        /** 
         * 计算用户留言总数
         * @param  [num] $uid  用户id
         * @return [num]      
         */
        public function userMesSum($uid)
        {
            $message = new Application_Model_MessageMapper();
            $sum = $message->userSum($uid);
            return $sum;
        }

        /**
         * 列出评论总数
         * @return [type] [description]
         */
        public function replySum()
        {
            $reply = new Application_Model_ReplyMapper();
            $sum = $reply->theSum();
            return $sum;
        }

        /**
         * 某用户评论总数
         * @param  [num] $id  
         * @return [num]    
         */
        public function userReplySum($id)
        {
            $reply = new Application_Model_ReplyMapper();
            $sum = $reply->userSum($id);
            return $sum;
        }

        
        /**
         * 根据留言取出评论内容
         * @param  [two-dim array] $mes   留言内容
         * @param  string $offset  数据开始位置   
         * @return [three-dim array]         
         */
        public function showReply($mes, $offset='0', $count='10')
        {
            $reply = new Application_Model_ReplyMapper();
            $user = new Application_Model_UserMapper();
            $global = new Application_Model_Global();
            foreach ($mes as $k => $v) {
                $replyInfo[$k] = $reply->listReply($v['mes_id'], $offset);
                $replyInfo[$k] = $this->emotion($replyInfo[$k], 'reply');
                foreach ($replyInfo[$k] as $rk => $rv) {
                    $addUser[$rk] = $user->findOne($rv['gid']);
                    $addUser[$rk] = $global->addUrl($addUser[$rk], 'user');
                }
                $userInfo[$k] = $addUser;
            }
            $res['reply'] = $replyInfo;
            $res['ruser'] = $userInfo;
            return $res;
        }

        public function emotion($mes, $type)
        {
            switch ($type) {
                case 'mes':
                    $title = "mes_content";
                    break;
                
                case 'reply':
                    $title = "reply_content";
                    break;
            }
            //表情替换
            $emAddress = "<img src=\"/images/emotion/" . "99" . ".gif\" height=\"24\" width=\"24\" />";
            $emArgs = array('[微笑]','[撇嘴]','[色]','[发呆]','[得意]','[流泪]','[害羞]','[闭嘴]','[睡觉]','[大哭]','[尴尬]','[发怒]','[调皮]','[龇牙]','[惊讶]','[难过]','[酷]','[冷汗]','[抓狂]','[吐]','[偷笑]','[可爱]','[白眼]','[傲慢]','[饥饿]','[困]','[惊恐]','[流汗]','[憨笑]','[大兵]','[奋斗]','[咒骂]','[疑问]','[嘘..]','[晕]','[折磨]','[衰]','[骷髅]','[敲打]','[再见]','[擦汗]','[抠鼻]','[鼓掌]','[糗大了]','[坏笑]','[左哼哼]','[右哼哼]','[哈欠]','[鄙视]','[委屈]','[快哭了]','[阴险]','[亲亲]','[吓]','[可怜]','[菜刀]','[西瓜]','[啤酒]','[篮球]','[乒乓]','[咖啡]','[米饭]','[猪头]','[玫瑰]','[凋谢]','[示爱]');
            for ($i=0; $i < count($mes); $i++) { 
                foreach ($emArgs as $emKey => $emValue) {
                    if (strstr($mes[$i][$title], $emValue)) {
                        //取出表情标记
                        $firstChange = strstr($mes[$i][$title], $emValue);
                        $secondChange = strstr($mes[$i][$title], "]");
                        $changeResult = str_replace($secondChange, "]", $firstChange);
                        //获取表情文件代号
                        $emNum = $emKey+1;
                        $reAddress = str_replace("99", $emNum, $emAddress);
                        $mes[$i][$title] = str_replace($changeResult, $reAddress, $mes[$i][$title]);
                    }
                }
            }
            return $mes;
        }
    }

?>