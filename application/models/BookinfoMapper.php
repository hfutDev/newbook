<?php

    /**
    * 操作书籍信息的具体方法
    */
    class Application_Model_BookinfoMapper
    {
    	protected $book;
        protected $bookDb;
        protected $searchColumns = array("book_name", "publish", "author", "infor");

    	function __construct()
    	{
            $this->book = new Application_Model_DbTable_Bookinfo();
            $this->bookDb = $this->book->getAdapter();

    	}

        /**
         * 取出单本书籍信息 By id号  
         * @param id  书籍id
         * @return  [two-dim array]
         */
        public function findOneById($id)
        {
            $where = $this->bookDb->quoteInto('book_id = ?', $id);
            $res = $this->book->fetchRow($where);
            if ($res) {
                $res = $res->toArray();
            }
            return $res;
        }

        /**
         * 取出单本书籍信息  By 书名
         * @param [string] $name 书名
         * @return  [two-dim array]
         */
        public function findOneByName($name)
        {
            $where = $this->bookDb->quoteInto('book_name = ?', $name);
            $res = $this->book->fetchAll($where)->toArray();
            return $res;
        }

        /**
         * 取出多条书本信息记录
         * @param count 信息条数
         * @param offset 数据开始点
         * @return  [two-dim array]
         */
        public function findMore($count='6', $offset='0')
        {
            $order = 'book_id DESC';
            $res = $this->book->fetchAll($where=null, $order, $count, $offset)->toArray();
            return $res;
        }

        /**
         * 插入书籍信息
         * @param data 书籍的详细信息
         */
        public function insertOne($data)
        {
            $data['book_name'] = $this->bookDb->quote($data['book_name']);
            $data['publish'] = $this->bookDb->quote($data['publish']);
            $data['infor'] = $this->bookDb->quote($data['infor']);
            $res = $this->book->insert($data);
            return $res;
        }

        /**
         * 跟新书籍信息
         * @param data 更新的书籍信息
         * @param id 需更新书籍的id号
         * @return  [数据表受影响的行数]
         */
        public function updateOne($data, $id)
        {
            $where = $this->bookDb->quoteInto('book_id = ?', $id);
            $res = $this->book->update($data, $where);
            return $res;
        }

        /**
         * 删除书籍信息
         * @param  [num] $id 书籍id号
         * @return [num]     成功则返回1
         */
        public function delOne($id)
        {
            $where = $this->bookDb->quoteInto('book_id = ?', $id);
            $res = $this->book->delete($where);
            return $res;
        }

        /**
         * 列出bookinfo表中所有字段
         * @return [one-dim array] 所有字段名称数组
         */
        public function showColums()
        {
            $sql = "show columns from bookinfo;";
            $queryRes = $this->bookDb->query($sql)->fetchAll();
            for ($i=0; $i < count($queryRes); $i++) { 
                $columnRes[$i] = $queryRes[$i]['Field'];
            }
            return $columnRes;
        }

        /**
         * 模糊查询
         * @param  [string] $keyWord 检索词
         * @param  string $column  检索字段
         * @return [two-dim array]     
         */
        public function blurSearch($keyWord, $column='book_name')
        {
            $word = "%" . $keyWord . "%";
            $sql = "SELECT * FROM bookinfo WHERE " . $column . " LIKE ?";
            $sql = $this->bookDb->quoteInto($sql, $word);
            $res = $this->bookDb->query($sql)->fetchAll();
            return $res;
        }

        /**
         * 针对书籍信息字段进行模糊搜索
         * @param  [string] $keyWord 搜索词
         * @return [two-dim array]   书籍信息
         */
        public function multiBlurSearch($keyWord)
        {
            $res = array();
            $global = new Application_Model_Global();
            $word = "%" . $keyWord . "%";
            foreach ($this->searchColumns as $searchKey => $searchValue) {
                $sql = "SELECT * FROM bookinfo WHERE " . $searchValue . " LIKE ?";
                $sql = $this->bookDb->quoteInto($sql, $word);
                $sqlRes = $this->bookDb->query($sql)->fetchAll();
                $res = array_merge($res, $sqlRes);
                // $res = $global->clearRepeat($res);
            }
            return $res;
        }

        /**
         * 多词查询
         * @param  [string] $keyWord 多个词 空格分开
         * @return [two-dim array]          
         */
        public function multiWordSearch($keyWord)
        {
            $res = array();
            $global = new Application_Model_Global();
            $words = explode(' ', $keyWord);
            //去除空格
            $words = array_diff($words, array(null));
            foreach ($words as $k => $v) {
                $wordArgs[$k] = $global->truncateWord($v);
            }
            $wordArgs = $global->rebuildArray($wordArgs);
            for ($i=0; $i < count($wordArgs); $i++) { 
                $blurRes = $this->multiBlurSearch($wordArgs[$i]);
                $res = array_merge($res, $blurRes); 
            }
            $res = $global->clearRepeat($res);
            return $res;
        }

        /**
         * 通过学院搜索
         * @param  string $col 学院代号
         * @return [two-dim array]    详细的书籍信息 
         */
        public function colSearch($col='1')
        {
            $order = "book_id DESC";
            $where = $this->bookDb->quoteInto('book_col = ?', $col);
            $res = $this->book->fetchAll($where, $order)->toArray();
            return $res;
        }

        /**
         * 通过分类搜索
         * @param  string $cate 分类代号
         * @return [two-dim array]    详细的书籍信息 
         */
        public function cateSearch($col='1')
        {
            $order = "book_id DESC";
            $where = $this->bookDb->quoteInto('book_category = ?', $col);
            $res = $this->book->fetchAll($where, $order)->toArray();
            return $res;
        }
    }
?>
