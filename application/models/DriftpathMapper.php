<?php

    /**
    * 操作读书漂流轨迹的所有方法
    */
    class Application_Model_DriftpathMapper
    {
    	protected $driftpath;
    	protected $driftpathDb;
    	
    	function __construct()
    	{
    		$this->driftpath = new Application_Model_DbTable_Driftpath();
    		$this->driftpathDb = $this->driftpath->getAdapter();
    	}

    	/**
    	 * 根据漂流书籍找出漂流轨迹
    	 * @param  [num] $id 漂流书籍id
    	 * @return [two-dim array]     
    	 */
    	public function findByDrift($id)
    	{
    		$order = "time DESC";
    		$where = $this->driftpathDb->quoteInto("d_id = ?", $id);
    		$res = $this->driftpath->fetchAll($where, $order)->toArray();
    		return $res;
    	}

    	/**
    	 * 增加读书漂流轨迹
    	 * @param [num] $id   漂流书籍id
    	 * @param [string] $user 读书者
    	 */
    	public function addOne($id, $user)
    	{
    		$date = new Zend_Date();
            $date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Ymd');
            $data = array(
            	'd_id' => $id,
            	'user' => $user,
            	'time' => $time 
            	);
            $res = $this->driftpath->insert($data);
            return $res;
    	}

    	/**
    	 * 删除漂流轨迹
    	 * @param  [num] $id 漂流记录id
    	 * @return [num]     受影响的行数
    	 */
    	public function delOne($id)
    	{
    		$where = $this->driftpathDb->quoteInto('driftpath_id = ?', $id);
    		$res = $this->driftpath->delete($where);
    		return $res;
    	}
    }

?>