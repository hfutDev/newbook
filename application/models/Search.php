<?php

   /**
   * 处理搜索功能：精确查询、模糊查询
   */
   class Application_Model_Search
   {
      protected $book;
      protected $sale;
      protected $user;
      // protected $global;
   	
   	function __construct()
   	{
         $this->book = new Application_Model_BookinfoMapper();
         $this->sale = new Application_Model_SaleMapper();
         $this->user = new Application_Model_UserMapper();
         $this->global = new Application_Model_Global();
         // $this->global = new Application_Model_Application_Model_Global();
   	}

      /**
       * 通过书籍信息取得书籍发布者信息，返回两者综合信息
       * @param [string] $value 检索内容
       * @param [num] $type  检索方式代号
       * @return [three-dim array] 三维数组
       */
      public function addAllInfo($value, $type, $page)
      {
         switch ($type) {
            case '1': //单个检索词检索
               $bookInfo = $this->book->multiBlurSearch($value);
               break;

            case '2': //多个检索词检索
               $bookInfo = $this->book->multiWordSearch($value);
               break;

            case '3': //通过学院检索
               $bookInfo = $this->book->colSearch($value);
               break;

            case '4': //通过分类检索
               $bookInfo = $this->book->cateSearch($value);
               break;
             
            default:
               return false;
               break;
          }
         $res['book'] = $bookInfo;
         $res['user'] = $this->addUserInfo($bookInfo);
         $res['sum'] = count($bookInfo);
         
         //增加详情页链接
         for ($i=0; $i < $res['sum']; $i++) {
           $res['book'][$i] = $this->global->addUrl($res['book'][$i], 'book');
           $res['user'][$i] = $this->global->addUrl($res['user'][$i], 'user');
         }

         if ($res['sum']) {
           $res = $this->doPage($res, $page);
         }
         return $res;
      }   

      /**
       * 通过标签搜索书籍信息
       * @param  [num] $id 标签id
       * @return [three-dim array]      
       */
      public function labelSearch($id, $page)
      {
         $saleLabel = new Application_Model_SalebooklabelMapper();
         $saleLabelInfo = $saleLabel->findMore($id);
         foreach ($saleLabelInfo as $k => $v) {
            $bookInfo[$k] = $this->book->findOneById($v['b_id']);
         }
         $res['book'] = $bookInfo;
         $res['user'] = $this->addUserInfo($bookInfo);
         $res['sum'] = count($bookInfo);
         $res = $this->doPage($res, $page);
         return $res;
      }

      /**
       * 根据书籍id，取出用户信息
       * @param [two-dim array] $info 书籍信息
       * @return [two-dim array] 用户信息
       */
      public function addUserInfo($info)
       {
         foreach ($info as $k => $v) {
            $saleInfo = $this->sale->findByBook($v['book_id']);
            $userInfo[$k] = $this->user->findOne($saleInfo['sale_userid']);
         }
         return $userInfo;
       }

       /**
        * 根据分页输出结果
        * @param  [three-dim array] $value 搜索到的结果
        * @param  [num] $page  第几页
        * @return [three-dim array]      
        */
       public function doPage($value, $page, $count='6')
        {
           // $count = 6;
           $offset = $count*($page-1);
           $endCount = $count*$page;
           $k = 0;
           for ($i = $offset; $i < $endCount; $i++) { 
             if (!$value['book'][$i]) {
               break;
             } else {
               $res['book'][$k] = $value['book'][$i];
               $res['user'][$k] = $value['user'][$i];
               $k++;
             }
           }
           $res['sum'] = $value['sum'];
           return $res;
        } 
   }

?>