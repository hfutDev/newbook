<?php

    /**
    * 整合处理页面有关书籍信息输出的处理方法
    */
    class Application_Model_Book
    {
        protected $sale;
        protected $bookInfo;
        protected $user;
        protected $label;
        protected $saleBookLabel;
        protected $global;
    	
    	function __construct()
    	{
    		$this->sale = new Application_Model_SaleMapper();
            $this->bookInfo = new Application_Model_BookinfoMapper();
            $this->label = new Application_Model_LabelMapper();
            $this->saleBookLabel = new Application_Model_SalebooklabelMapper();
            $this->user = new Application_Model_UserMapper();  
            $this->global = new Application_Model_Global();
    	}

        /**
         * 通过交易状态信息取出书籍信息
         * @param  string $count  书籍信息数量
         * @param  string $offset 数据开始位置
         * @return [two-dim array] 
         */
        public function listHot($state='1', $count='12', $offset='0')
        {
            $saleRes = $this->sale->findByState($state, $count, $offset);
            for ($i=0; $i < count($saleRes); $i++) { 
                $bookRes[$i] = $this->bookInfo->findOneById($saleRes[$i]['sale_bookid']);
                $bookRes[$i] = $this->global->addUrl($bookRes[$i], 'book');
                $userRes[$i] = $this->user->findOne($saleRes[$i]['sale_userid']);
                $userRes[$i] = $this->global->addUrl($userRes[$i], 'user');
            }
            $res['sale'] = $saleRes;
            $res['book'] = $bookRes;
            $res['user'] = $userRes;
            return $res;
        }

        /**
         * 根据用户id和交易状态查询书籍交易信息
         * @param  [num] $uid     用户id
         * @param  string $offset 数据开始位置
         * @param  string $state  交易状态
         * @param  string $count  数据量
         * @return [three-dim array]      
         */
        public function listByUser($uid, $offset='0', $state='1', $count='5')
        {
            $saleRes = $this->sale->findByUserState($uid, $offset, $state, $count);
            for ($i=0; $i < count($saleRes); $i++) { 
                $bookRes[$i] = $this->bookInfo->findOneById($saleRes[$i]['sale_bookid']);
                $bookRes[$i] = $this->global->addUrl($bookRes[$i], 'book');
            }
            $res['sale'] = $saleRes;
            $res['book'] = $bookRes;
            return $res;
        }

        /**
         * 列出一本书的详细信息
         * @param  [num] $id 书籍id
         * @return [two-dim array]     书籍的详细信息
         */
        public function listOne($id)
        {
            $lastId = $this->listLast();
            if (!$id || !is_numeric($id) || $id>$lastId ) {
                $id = $lastId;
            }
            $saleRes = $this->sale->findByBook($id);
            $res['sale'] = $saleRes;
            $res['book'] = $this->bookInfo->findOneById($saleRes['sale_bookid']);
            $res['user'] = $this->user->findOne($saleRes['sale_userid']);
            return $res;
        }

        /**
         * 发布卖书信息  **待测试**
         * @param [string] $bName   书名
         * @param [string] $publish 出版社
         * @param [string] $author  作者
         * @param [string] $infor   卖书信息
         * @param [num] $fPrice  原价
         * @param [num] $nPrice  现价
         * @param [num] $college 学院代码//取消
         * @param [num] $category 分类
         * @param [num] $eTime   结束时间
         * @param [num] $uid     用户id
         * @param [string] $label   标签
         * @return [string] 发布结果信息
         */
        public function addOne($bName, $publish, $infor, $fPrice, $nPrice, $category, $uid, $label, $file)
        {
            $image = new Application_Model_Image();
            $errInfo = false;
            $sucInfo = true;
            $bName = $this->global->checkHtml($bName);
            $publish = $this->global->checkHtml($publish);
            $infor = $this->global->checkHtml($infor);
            $bookDate = array(
                'book_name' => $bName,
                'publish' => $publish,
                'infor' => $infor,
                'former_price' => $fPrice,
                'now_price' => $nPrice,
                'book_category' => $category
                //'book_col' => $college
                );
            $bInsertRes = $this->bookInfo->insertOne($bookDate); //return book_id
            //写入sale表，记录记录交易
            if ($bInsertRes) {
                $sInsertRes = $this->sale->insertOne($bInsertRes, $uid);
                if ($sInsertRes) {
                    //处理标签
                    $labelRes = $this->label->addLabel($label);
                    if ($labelRes) {
                        foreach ($labelRes as $k => $v) {
                           $saleBookLabelRes[$k] = $this->saleBookLabel->insertOne($bInsertRes, $v);
                        }
                        if ($saleBookLabelRes) {
                            $imageRes = $image->fileUpload($file, $bInsertRes);
                            //修改照片地址
                            $imagePath = "/photo/book/thumb/" . $imageRes;
                            $imageData = array('photo_path' => $imagePath);
                            $this->bookInfo->updateOne($imageData, $bInsertRes);
                            if ($imageRes) {
                                return $sucInfo;
                            } else {
                                //回滚事务
                                $delRes = $this->delInfo($bInsertRes, $sInsertRes, $labelRes, $saleBookLabelRes, $imageRes); 
                                return $errInfo;
                            }
                        } else {
                            $this->label->delLabel($labelRes);
                            return $errInfo;
                        }
                    } else {
                        $this->sale->delOne($saleId);
                        return $errInfo;
                    }
                } else {
                    $this->bookInfo->delOne($bInsertRes);
                    return $errInfo;
                }
            } else {
                return $errInfo;
            }
        }

        /**
         * ISBN发布
         * @param [string] $bName   书名
         * @param [string] $publish 出版社
         * @param [string] $author  作者
         * @param [string] $infor   卖书信息
         * @param [num] $fPrice  原价
         * @param [num] $nPrice  现价
         * @param [num] $category 分类
         * @param [num] $uid     用户id
         * @param [string] $label   标签
         * @return [string] 发布结果信息
         */
        public function addISBN($bName, $publish, $infor, $fPrice, $nPrice, $category, $uid, $label, $file)
        {
            $errInfo = false;
            $sucInfo = true;
            $bName = $this->global->checkHtml($bName);
            $publish = $this->global->checkHtml($publish);
            $infor = $this->global->checkHtml($infor);
            $bookDate = array(
                'book_name' => $bName,
                'publish' => $publish,
                'infor' => $infor,
                'former_price' => $fPrice,
                'now_price' => $nPrice,
                'book_category' => $category
                //'book_col' => $college
                );
            $bInsertRes = $this->bookInfo->insertOne($bookDate); //return book_id
            //写入sale表，记录记录交易
            if ($bInsertRes) {
                $sInsertRes = $this->sale->insertOne($bInsertRes, $uid,'');
                // return $bookDate;
                if ($sInsertRes) {
                    //处理标签
                    $labelRes = $this->label->addLabel($label);
                    if ($labelRes) {
                        foreach ($labelRes as $k => $v) {
                           $saleBookLabelRes[$k] = $this->saleBookLabel->insertOne($bInsertRes, $v);
                        }
                        if ($saleBookLabelRes) {
                            //修改照片地址
                            $imageData = array('photo_path' => $file);
                            $imageRes = $this->bookInfo->updateOne($imageData, $bInsertRes);
                            if ($imageRes) {
                                return $sucInfo;
                            } else {
                                //回滚事务
                                $imageRes = "";
                                $delRes = $this->delInfo($bInsertRes, $sInsertRes, $labelRes, $saleBookLabelRes, $imageRes);
                                return $errInfo;
                            }
                        } else {
                            $this->label->delLabel($labelRes);
                            return $errInfo;
                        }
                    } else {
                        $this->sale->delOne($saleId);
                        return $errInfo;
                    }
                } else {
                    $this->bookInfo->delOne($bInsertRes);
                    return $errInfo;
                }
            } else {
                return $errInfo;
            }
        }

        /**
         * 删除求书信息
         * @param  [num] $bookId   书籍id
         * @param  [num] $saleId   交易id
         * @param  [num] $labelId  标签id
         * @param  [num] $sblId    卖书书籍标签
         * @param  [string] $images  图片名称
         * @return [one-dim array]       
         */
        public function delInfo($bookId, $saleId, $labelId, $sblId, $images)
        {
            $image = new Application_Model_Image();
            $res['book'] = $this->bookInfo->delOne($bookId);
            $res['sale'] = $this->sale->delOne($saleId);
            $res['label'] = $this->label->delLabel($labelId);
            $res['sbl'] = $this->saleBookLabel->delsbl($sblId);
            $res['image'] = $image->delImage($images);
            return $res;
        }

        /**
         * 列出最后一次卖书交易的书籍id号
         * @return [num] 书籍id号
         */
        public function listLast()
        {
            $res = $this->sale->findByState('1', '0');
            return $res['0']['sale_bookid'];
        }

        /**
         * 计算总的数据量
         * @param  string $state 交易状态
         * @return [num]      
         */
        public function theSum($state='1')
        {
            $saleInfo = $this->sale->listAll($state);
            $sum = count($saleInfo);
            return $sum;
        }

        /**
         * 查询用户书籍交易总数
         * @param  [num] $uid    用户id
         * @param  string $state 
         * @return [unm]        
         */
        public function userSum($uid, $state='1')
        {
            $res = $this->sale->userSum($uid, $state);
            return $res;
        }

        /**
         * 记录用户浏览历史
         * @param [num] $id 书籍id
         * @return  [string] 
         */
        public function setHistoryBook($id)
        {
            if (!empty($_COOKIE['hisBook'])) {
                $history = explode(',', $_COOKIE['hisBook']);
                //将书籍id插入到数组开头
                array_unshift($history, $id);
                $history = array_unique($history);
                while (count($history) > 5) {
                    //将数组最后一个字段弹出，知道长度为5
                    array_pop($history);
                }
                setcookie('hisBook', implode(',', $history), time()+3600*24*30);
            } else {
                setcookie('hisBook', $id, time()+3600*24*30);
            }
            return $_COOKIE['hisBook'];
        }

        public function getHistoryBook()
        {
            $historyBookId = explode(',', $_COOKIE['hisBook']);
            foreach ($historyBookId as $k => $v) {
                $hisBook[$k] = $this->bookInfo->findOneById($v);
                $hisBook[$k]['bookUrl'] = "/book/detail/book/" . $hisBook[$k]['book_id'];
            }
            return $hisBook;
        }
    }
?>
