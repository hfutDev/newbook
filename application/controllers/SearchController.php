<?php

    /**
    *  有关搜索的所有操作Action
    */
    class SearchController extends Zend_Controller_Action
    {

    	public function init()
    	{
    		//输出layout共同部分
    		$commen = new Application_Model_Commen($this->view);
    		$commen->theCommen();
    	}

    	/**
    	 * 根据学院搜索
    	 */
    	public function colsearchAction()
    	{
    		session_start();
    		$search = new Application_Model_Search();
    		$global = new Application_Model_Global();
    		$col = $this->getRequest()->getParam('col');
    		if ($col) {
    			$_SESSION['colsearch'] = $col;
    		} else {
    			$col = $_SESSION['colsearch'];
    		}
    		$page = $this->getRequest()->getParam('page');
    		if (!$page) {
    			$page = 1;
    		}
    		$bookInfo = $search->addAllInfo($col, '3', $page);
    		if (!$bookInfo['sum']) {
    			$page = 0;
    		}
    		$pages = $global->thePages($bookInfo['sum'], 6);
    		$pageNav = $global->pageNav($page, $pages, 'colsearch');
    		$this->view->bookInfo = $bookInfo;
    		$this->view->pageNav = $pageNav;
    		$this->view->page = $page;
    		$this->view->pages = $pages;
    	}

    	/**
    	 * 标签搜索
    	 */
    	public function labelsearchAction()
    	{
    		session_start();
    		$search = new Application_Model_Search();
    		$global = new Application_Model_Global();
    		$label = $this->getRequest()->getParam('label');
    		if ($label) {
    			$_SESSION['labelsearch'] = $label;
    		} else {
    			$label = $_SESSION['labelsearch'];
    		}
    		$page = $this->getRequest()->getParam('page');
    		if (!$page) {
    			$page = 1;
    		}
    		$bookInfo = $search->labelSearch($label, $page);
    		if (!$bookInfo['sum']) {
    			$page = 0;
    		}
    		$pages = $global->thePages($bookInfo['sum'], 6);
    		$pageNav = $global->pageNav($page, $pages, 'labelsearch');
    		$this->view->bookInfo = $bookInfo;
    		$this->view->pageNav = $pageNav;
    		$this->view->page = $page;
    		$this->view->pages = $pages;
    	}

    	public function keywordAction()
    	{
    		session_start();
    		$search = new Application_Model_Search();
    		$global = new Application_Model_Global();
    		$word = $this->getRequest()->getParam('word');
    		if ($word) {
    			$_SESSION['keyword'] = $word;
    		} else {
    			$word = $_SESSION['keyword'];
    		}
    		$page = $this->getRequest()->getParam('page');
    		if (!$page) {
    			$page = 1;
    		}
    		$bookInfo = $search->addAllInfo($word, '2', $page);
    		if (!$bookInfo['sum']) {
    			$page = 0;
    		}
    		$pages = $global->thePages($bookInfo['sum'], 6);
    		$pageNav = $global->pageNav($page, $pages, 'keyword');
    		$this->view->bookInfo = $bookInfo;
    		$this->view->pageNav = $pageNav;
    		$this->view->page = $page;
    		$this->view->pages = $pages;
    	}

        /**
         * 输出搜索书籍页面，包括学院、标签、关键字搜索
         */
        public function dosearchAction()
        {
            session_start();
            $search = new Application_Model_Search();
            $global = new Application_Model_Global();
            $type = $this->getRequest()->getParam('type');
            $page = $this->getRequest()->getParam('page');
            if (!$page) {
                $page = 1;
            }
            switch ($type) {
                case 'label':
                    $label = $this->getRequest()->getParam('label');
                    if ($label) {
                        $_SESSION['label'] = $label;
                    } else {
                        $label = $_SESSION['label'];
                    }
                    $bookInfo = $search->labelSearch($label, $page);
                    $pages = $global->thePages($bookInfo['sum'], 6);
                    $pageNav = $global->pageNav($page, $pages, 'labelsearch');
                    break;

                case 'col':
                    $col = $this->getRequest()->getParam('col');
                    if ($col) {
                        $_SESSION['col'] = $col;
                    } else {
                        $col = $_SESSION['col'];
                    }
                    $bookInfo = $search->addAllInfo($col, '3', $page);
                    $pages = $global->thePages($bookInfo['sum'], 6);
                    $pageNav = $global->pageNav($page, $pages, 'colsearch');
                    break;

                case 'cate':
                    $cate = $this->getRequest()->getParam('cate');
                    if ($cate) {
                        $_SESSION['cate'] = $cate;
                    } else {
                        $cate = $_SESSION['cate'];
                    }
                    $bookInfo = $search->addAllInfo($cate, '4', $page);
                    $pages = $global->thePages($bookInfo['sum'], 6);
                    $pageNav = $global->pageNav($page, $pages, 'catesearch');
                    break;

                case 'keyword':
                    $word = $this->getRequest()->getParam('word');
                    if ($word) {
                        $_SESSION['keyword'] = $word;
                    } else {
                        $word = $_SESSION['keyword'];
                    }
                    $bookInfo = $search->addAllInfo($word, '2', $page);
                    $pages = $global->thePages($bookInfo['sum'], 6);
                    $pageNav = $global->pageNav($page, $pages, 'keyword');
                    break;
                
                default:
                    $alertValue = array('a' => '抱歉，暂时不支持你所需的搜索方式！');
                    $this->_forward('bad', 'global', null, $alertValue);
                    break;
            }
            if (!$bookInfo['sum']) {
                $page = 0;
            }
            //增加Url
            for ($i=0; $i < count($bookInfo['book']); $i++) { 
                $bookInfo['book'][$i] = $global->addUrl($bookInfo['book'][$i], 'book');
                $bookInfo['user'][$i] = $global->addUrl($bookInfo['user'][$i], 'user');
            }
            $this->view->bookInfo = $bookInfo;
            $this->view->pageNav = $pageNav;
            $this->view->page = $page;
            $this->view->pages = $pages;
        }
    }
?>