<?php

    /**
    * 操作读书漂流书籍信息的所有方法
    */
    class Application_Model_DriftingMapper
    {
    	protected $drift;
    	protected $driftDb;
    	
    	function __construct()
    	{
    		$this->drift = new Application_Model_DbTable_Drifting();
    		$this->driftDb = $this->drift->getAdapter();
    	}

    	/**
    	 * 列出所有漂流书籍信息
    	 * @param  string $offset 数据开始位置
    	 * @param  string $count  数据量
    	 * @param  string $state  漂流状态
    	 * @return [two-dim array]       
    	 */
    	public function listDrifting($offset='0', $count='6', $state='1')
    	{
    		$order = "drift_id DESC";
    		$where = $this->driftDb->quoteInto('drift_state = ?', $state);
    		$res = $this->drift->fetchAll($where, $order, $count, $offset)->toArray();
    		return $res;
    	}

        /**
         * 列出所有漂流信息
         * @param  string $offset 数据开始位置
         * @param  string $count  数据量
         * @return [two-dim array]        
         */
        public function listAll($offset='0', $count='9')
        {
            $order = "time ASC";
            $res = $this->drift->fetchAll($where=null, $order, $count, $offset)->toArray();
            return $res;
        }

        /**
         * 通过漂流id查找漂流信息
         * @param  [num] $id 漂流信息id
         * @return [one-dim array]     
         */
        public function findOne($id)
        {
            $where = $this->driftDb->quoteInto('drift_id = ?', $id);
            $res = $this->drift->fetchRow();
            if ($res) {
                $res = $res->toArray();
            }
            return $res;
        }

    	/**
    	 * 增加漂流书籍
    	 * @param [string] $name  书名
    	 * @param [string] $path  书籍封面
    	 * @param [string] $info  书籍介绍
    	 * @param [string] $owner 书籍拥有者
    	 */
    	public function addOne($name, $info, $owner)
    	{
    		$date = new Zend_Date();
            $date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Y-m-d H:i:s');
            $data = array(
            	'book_name' => $name,
            	'book_content' => $info,
            	'book_owner' => $owner,
            	'time' => $time, 
            	);
            $res = $this->drift->insert($data);
            return $res;
    	}

        public function updatePath($id, $name)
        {
            $path = "/photo/drift/" . $name;
            $where = $this->driftDb->quoteInto('drift_id = ?', $id);
            $data = array('book_cover' => $path);
            $res = $this->drift->update($data, $where);
            return $res;
        }

        /**
         * 计算数据总量
         * @param  string $state 漂流状态
         * @return [num]        总数
         */
        public function theSum($state='1')
        {
            if ($state) {
                $where = $this->driftDb->quoteInto('drift_state = ?', $state);
                $res = $this->drift->fetchAll($where)->toArray();
                return count($res);
            } else {
                $res = $this->drift->fetchAll()->toArray();
                return count($res);
            }
        }

    	/**
    	 * 删除漂流书籍
    	 * @param  [num] $id  漂流书籍id
    	 * @return [num]      删除数据的行数
    	 */
    	public function delOne($id)
    	{
    		$where = $this->driftDb->quoteInto("drift_id = ?", $id);
    		$res = $this->drift->delete($where);
    		return $res;
    	}

        /**
         * 根据漂流状态增加漂流轨迹信息
         * @param [array] $drift 漂流书籍信息
         * @return [two-dim array] 
         */
        public function addPath($driftBook)
        {
            $driftPath = new Application_Model_DriftpathMapper();
            for ($i=0; $i < count($driftBook); $i++) { 
                $driftInfo = $this->findOne($driftBook[$i]['drift_id']);
                $driftPathInfo = $driftPath->findByDrift($driftBook[$i]['drift_id']);
                if ($driftBook[$i]['drift_state'] == 2 && $driftPathInfo) {
                        $lastTime = $driftPathInfo['0']['time'];
                        if (substr($lastTime, 4, 5) == 12) {
                            $nextTime = substr($lastTime, 0, 4)+1 . "01" . substr($lastTime, 6);
                        } else {
                            $nextTime = substr($lastTime, 0, 4) . substr($lastTime, 4, 2)+1 . substr($lastTime, 6);
                        }
                        $lastTime = substr($lastTime, 0, 4) . "年" . substr($lastTime, 4, 2) . "月" . substr($lastTime, 6, 2) . "日";
                        $nextTime = substr($nextTime, 0, 4) . "年" . substr($nextTime, 4, 2) . "月" . substr($nextTime, 6, 2) . "日";
                        $res[$i] = array(
                            'lastTime' => $lastTime,
                            'nextTime' => $nextTime,
                            'user' => $driftPathInfo['0']['user']
                            );
                } else {
                    $res[$i] = $driftPathInfo;
                }
            }
            return $res;
        }
    }
?>