<?php

    /**
    * 处理标签数据表的具体方法
    */
    class Application_Model_LabelMapper
    {
    	protected $label;
    	protected $labelDb;
        protected $global;
    	
    	function __construct()
    	{
    		$this->label = new Application_Model_DbTable_Label();
    		$this->labelDb = $this->label->getAdapter();
            $this->global = new Application_Model_Global();
    	}

    	/**
    	 * 列出使用最多的标签
    	 * @return 二维数组
    	 */
    	public function listLabel()
    	{
    		$order = "label_count DESC";
    		$count = '15';
    		$offset = '0';
    		$res = $this->label->fetchAll($where=null, $order, $count, $offset)->toArray();
            $res = $this->global->addUrl($res, 'label');
    		return $res;
    	}

        /**
         * 增加标签 空格分隔标签字符串   存在则加一 不存在则添加
         * @param [string] $label 标签字符串
         * @return [one-dim array] 各标签在数据表中的id号
         */
        public function addLabel($label)
        {
            $labelRes = $this->doLabel($label);
            foreach ($labelRes as $k => $v) {
               $res[$k] = $this->findOne($v);
            }
            return $res;
        }

        /**
         * 删除标签处理（如果计数为0则删除标签，大于1则计数减一）
         * @param  [num or array] $id 标签id
         * @return [array or num]  成功则为1
         */
        public function delLabel($id)
        {
            if (is_array($id)) {
                foreach ($id as $k => $v) {
                    $where = $this->labelDb->quoteInto('label_id = ?', $v);
                    $findRes = $this->label->fetchRow($where);
                    if ($findRes) {
                        $findRes = $findRes->toArray();
                    }
                    if ($findRes['label_count'] == 0) {
                        $delRes[$k] = $this->label->delete($where);
                    } else {
                        $updatArgs = array('label_count' => $findRes['label_count']-1);
                        $delRes[$k] = $this->label->update($updatArgs, $where);
                    }
                }
            } else {
                $where = $this->labelDb->quoteInto('label_id = ?', $id);
                $findRes = $this->label->fetchRow($where);
                if ($findRes) {
                    $findRes = $findRes->toArray();
                }
                if ($findRes['label_count'] == 0) {
                    $delRes = $this->label->delete($where);
                } else {
                    $updatArgs = array('label_count' => $findRes['label_count']-1);
                    $delRes = $this->label->update($updatArgs, $where);
                }
            }
            return $delRes;
        }

    	/**
    	 * 查找是否存在某个标签, 如果存在则加一，不存在则插入数据
    	 * @param  [string] $name 标签名
    	 * @return [num] 标签的label_id
    	 */
    	public function findOne($name)
    	{
    		$where = $this->labelDb->quoteInto('label_name = ?', $name);
    		$search = $this->label->fetchRow($where);
    		if ($search) {
    			$search = $search->toArray();
    			//计数加一
    			$data = array('label_count' => $search['label_count']+1);
    			$res = $this->label->update($data, $where);
    			return $search['label_id'];
    		} else {
    			$data = array('label_name' => $name);
    			$res = $this->label->insert($data);
    			return $res;
    		}
    	}

        /**
         * 利用空格分隔标签
         * @param  [string] $label 标签字符串
         * @return [one-dim array]       分隔后的字符串数组
         */
        public function doLabel($label)
        {
            $labelWords = explode(' ', $label);
            foreach ($labelWords as $k => $v) {
                if (!$v) {
                    unset($labelWords[$k]);
                }
            }
            return $labelWords;
        }
    }

?>