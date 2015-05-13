<?php

   /**
   * 处理卖书标签的具体方法
   */
   class Application_Model_SalebooklabelMapper
   {

      protected $sbl;
      protected $sblDb;
   	
     	function __construct()
     	{
     		$this->sbl = new Application_Model_DbTable_Salebooklabel();
           $this->sblDb = $this->sbl->getAdapter();
     	}

        /**
         * 取出某个标签相关的书籍id
         * @param  [num] $id  标签id号
         * @return [two-dim array]
         */
        public function findMore($id)
        {
           $where = $this->sblDb->quoteInto('l_id = ?', $id);
           $res = $this->sbl->fetchAll($where)->toArray();
           return $res;
        }

        /**
         * 插入书籍标签数据
         * @param  [num] $bookId 书籍id
         * @param  [num] $labelId 标签id
         * @return [num] 记录id
         */
        public function insertOne($bookId, $labelId)
        {
           $data = array(
              'b_id' => $bookId,
              'l_id' => $labelId
               );
           $res = $this->sbl->insert($data);
           return $res;
        }

        /**
         * 删除交易书籍标签
         * @param  [num or array] $id  卖书书籍标签id号
         * @return [num or array]    
         */
        public function delsbl($id)
        {
          if (is_array($id)) {
            foreach ($id as $k => $v) {
              $where = $this->sblDb->quoteInto('sbl_id = ?', $v);
              $delRes[$k] = $this->sbl->delete($where);
            }
          } else {
            $where = $this->sblDb->quoteInto('sbl_id = ?', $id);
            $delRes = $this->sbl->delete($where);
          }
          return $delRes;
        }

        public function delsblByBook($id)
        {
          $where = $this->sblDb->quoteInto('b_id = ?', $id);
          $delRes = $this->sbl->delete($where);
          return $delRes;
        }
    }
?>