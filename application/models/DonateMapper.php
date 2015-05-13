<?php

     /**
     *  操作捐赠表的具体方法
     */
     class Application_Model_DonateMapper
     {

     	protected $donate;
     	protected $donateDb;
     	
     	function __construct()
     	{
     		$this->donate = new Application_Model_DbTable_Donate();
     		$this->donateDb = $this->donate->getAdapter();
     	}

     	/**
     	 * 列出最近的捐书信息
     	 * @param  string $offset 数据开始位置
     	 * @param  string $count  数据量
     	 * @param  string $state  捐书状态
     	 * @return [two-dim array]         
     	 */
     	public function listDonate($offset='0', $count='6', $state='1')
     	{
     		$order = "donate_time DESC";
     		$where = $this->donateDb->quoteInto('donate_state = ?', $state);
     		$res = $this->donate->fetchAll($where, $order, $count, $offset)->toArray();
     		return $res;
     	}

        /**
         * 根据id查找捐赠信息
         * @param  [num] $id 捐赠记录id
         * @return [one-dim array]     
         */
        public function findOne($id)
        {
            $where = $this->donateDb->quoteInto('donate_id = ?', $id);
            $res = $this->donate->fetchRow($where);
            if ($res) {
                $res = $res->toArray();
            }
            return $res;
        }

     	/**
     	 * 增加捐书信息
     	 * @param [string] $donator  捐书者
     	 * @param [num] $col      捐书者学院
     	 * @param [string] $book     书名
     	 * @param [string] $bookPath  书籍封面
     	 * @param [string] $wish     捐书者寄语
     	 * @return [num]  新增书籍在数据表的id
     	 */
     	public function addOne($donator, $col, $book, $dwish, $vwish)
     	{
     		$date = new Zend_Date();
            $date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Y-m-d');
            $data = array(
            	'donator' => $donator,
            	'college' => $col,
            	'book_name' => $book,
            	'donator_wish' => $dwish,
                'volunteer_wish' => $vwish,
            	'donate_time' => $time
            	);
            $res = $this->donate->insert($data);
            return $res;
     	}

        /**
         * 更新啊图片地址
         * @param  [num] $id   捐赠记录id
         * @param  [string] $name 图片名称
         * @return [num]       数据表影响的行数
         */
        public function updatePath($id, $name)
        {
            $path = "/photo/donate/" . $name;
            $where = $this->donateDb->quoteInto('donate_id = ?', $id);
            $data = array('book_path' => $path);
            $res = $this->donate->update($data, $where);
            return $res;
        }

     	/**
     	 * 修改捐赠书籍信息
     	 * @param  [num] $id       修改的捐赠信息id
     	 * @param  [string] $donee    受赠者
     	 * @param  [string] $sex      性别 boy or girl
     	 * @param  [string] $birthday  受赠者生日
     	 * @param  [string] $address   受赠者地址
     	 * @param  [string] $dwish     受赠者祝福
     	 * @param  [string] $vwish     青志联寄语
     	 * @return [num]            受影响的数据表行数
     	 */
     	public function updateOne($id, $donee, $sex, $birthday, $address, $dwish)
     	{
     		$date = new Zend_Date();
            $date->setOptions(array('format_type' => 'php'));
            $time = $date->toString('Y-m-d');
     		$where = $this->donateDb->quoteInto('donate_id = ?', $id);
     		$data = array(
     			// 'donate_time' => $time,
     			'donee' => $donee,
     			'donee_sex' => $sex,
     			'donee_birth' => $birthday,
     			'donee_address' => $address,
     			'donee_wish' => $dwish,
     			'donate_state' => '1' 
     			);
     		$res = $this->donate->update($data, $where);
     		return $res;
     	}

        public function theSum($state='1')
        {
            $where = $this->donateDb->quoteInto('donate_state = ?', $state);
            $res = $this->donate->fetchAll($where)->toArray();
            $sum = count($res);
            return $sum;
        }

     	/**
     	 * 删除捐赠信息
     	 * @param  [num] $id 删除的捐赠信息id
     	 * @return [num]     删除数据的数据表行数
     	 */
     	public function delOne($id)
     	{
     		$where = $this->donateDb->quoteInto('donate_id = ?', $id);
     		$res = $this->donate->delete($where);
     		return $res;
     	}
     }
?>