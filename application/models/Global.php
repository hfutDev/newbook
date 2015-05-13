<?php

   /**
   * 网站公用的一些方法
   */
   class Application_Model_Global
   {

        /**
         * 输出分页导航条
         * @param  string $page  当前页
         * @param  [string] $pages 总页数
         * @param  [string] $type  分页导航类型
         * @return [one-dim array]     
         */
        public function pageNav($page='1', $pages, $type)
        {
            switch ($type) {
                case 'publish':
                    $address = "/book/publish/page/";
                    break;

                case 'seek':
                    $address = "/book/seek/page/";
                    break;

                case 'colsearch':
                    $address = "/search/dosearch/type/col/page/";
                    break;

                case 'catesearch':
                    $address = "/search/dosearch/type/cate/page/";
                    break;

                case 'labelsearch':
                    $address = "/search/dosearch/type/label/page/";
                    break;

                case 'keyword':
                    $address = "/search/dosearch/type/keyword/page/";
                    break;

                case 'perpublishing':
                    $address = "/person/perpublish/type/publishing/page/";
                    break;

                case 'perseeking':
                    $address = "/person/perseek/type/seeking/page/";
                    break;

                case 'perpublished':
                    $address = "/person/perpublish/type/published/page/";
                    break;

                case 'perseeked':
                    $address = "/person/perseek/type/seeked/page/";
                    break;

                case 'permes':
                    $address = "/person/mes/type/per/page/";
                    break;

                case 'guestpublish':
                    $address = "/person/guestpublish/page/";
                    break;

                case 'guestseek':
                    $address = "/person/guestseek/page";
                    break;

                case 'guestmes':
                    $address = "/person/mes/type/guest/page/";
                    break;

                case 'adminpubing':
                    $address = "/admin/publish/type/1/page/";
                    break;

                case 'adminpubed':
                    $address = "/admin/publish/type/2/page/";
                    break;

                case 'adminuserpubing':
                    $address = "/admin/userpublish/type/3/page/";
                    break;

                case 'adminuserpubed':
                    $address = "/admin/userpublish/type/4/page/";
                    break;

                case 'adminseeking':
                    $address = "/admin/seek/type/1/page/";
                    break;

                case 'adminseeked':
                    $address = "/admin/seek/type/2/page/";
                    break;

                case 'adminuserseeking':
                    $address = "/admin/userseek/type/3/page";
                    break;

                case 'adminuserseeked':
                    $address = "/admin/userseek/type/4/page";
                    break;

                case 'adminmes':
                    $address = "/admin/messagereply/type/mes/page/";
                    break;

                case 'adminreply':
                    $address = "/admin/messagereply/type/reply/page/";
                    break;

                case 'adminusermes':
                    $address = "/admin/usermessagereply/type/mes/page/";
                    break;

                case 'adminureply':
                    $address = "/admin/usermessagereply/type/reply/page/";
                    break;

                case 'adminuser':
                    $address = "/admin/user/page/";
                    break;

                case 'drift':
                    $address = "/drift/index/page/";
                    break;

                case 'driftin':
                    $address = "/drift/index/order/in/page/";
                    break;

                case 'driftout':
                    $address = "/drift/index/order/out/page/";
                    break;

                case 'listorder1':
                    $address = "/drift/listorder/type/1/page/";
                    break;

                case 'listorder2':
                    $address = "/drift/listorder/type/2/page/";
                    break;

                case 'donateadmin1':
                    $address = "/donate/admin/type/1/page/";
                    break;

                case 'donateadmin2':
                    $address = "/donate/admin/type/2/page/";
                    break;

                case 'donateadmin3':
                    $address = "/donate/admin/type/3/page/";
                    break;

                case 'donateadmin4':
                    $address = "/donate/admin/type/4/page/";
                    break;
                
                default:
                    return false;
                    break;
            }
            $disableStyle = "cursor:not-allowed;";
            $disableUrl = "javascript:void(0)";
            $enableSyle = "";
            $prePage = $page - 1;
            $nextPage = $page + 1;
            $firstUrl = $address . "1";
            $lastUrl = $address . $pages;
            $preUrl = $address . $prePage;
            $nextUrl = $address . $nextPage;
            if (($page == 1 && $pages == 1) || $pages == 0) {
                $pageRes['firstStyle'] = $disableStyle;
                $pageRes['firstUrl'] = $disableUrl;
                $pageRes['preStyle'] = $disableStyle;
                $pageRes['preUrl'] = $disableUrl;
                $pageRes['nextStyle'] = $disableStyle;
                $pageRes['nextUrl'] = $disableUrl;
                $pageRes['lastStyle'] = $disableStyle;
                $pageRes['lastUrl'] = $disableUrl;
            } elseif ($page == 1) {
                $pageRes['firstStyle'] = $disableStyle;
                $pageRes['firstUrl'] = $disableUrl;
                $pageRes['preStyle'] = $disableStyle;
                $pageRes['preUrl'] = $disableUrl;
                $pageRes['nextStyle'] = $enableSyle;
                $pageRes['nextUrl'] = $nextUrl;
                $pageRes['lastStyle'] = $enableSyle;
                $pageRes['lastUrl'] = $lastUrl;
            } elseif (($page == $pages) || ($page > $pages)) {
                $pageRes['firstStyle'] = $enableSyle;
                $pageRes['firstUrl'] = $firstUrl;
                $pageRes['preStyle'] = $enableSyle;
                $pageRes['preUrl'] = $preUrl;
                $pageRes['nextStyle'] = $disableStyle;
                $pageRes['nextUrl'] = $disableUrl;
                $pageRes['lastStyle'] = $disableStyle;
                $pageRes['lastUrl'] = $disableUrl;
            } else {
                $pageRes['firstStyle'] = $enableSyle;
                $pageRes['firstUrl'] = $firstUrl;
                $pageRes['preStyle'] = $enableSyle;
                $pageRes['preUrl'] = $preUrl;
                $pageRes['nextStyle'] = $nextStyle;
                $pageRes['nextUrl'] = $nextUrl;
                $pageRes['lastStyle'] = $lastStyle;
                $pageRes['lastUrl'] = $lastUrl;
            }
            return $pageRes;
        }

        /**
         * 计算总页数
         * @param  [num] $sum   总数据量
         * @param  [num] $count 每页数据量
         * @return [num]        
         */
        public function thePages($sum, $count)
        {
            $pages = intval($sum / $count);
            if ($sum % $count) {
                $pages++;
            }
            return $pages;
        }

        /**
         * 添加链接
         * @param [one-dim array] $args 单本书籍信息或者个人信息数组
         *        [two-dim array] $args 标签搜索和学院搜索
         * @param [string] $type 判断添加类型
         * @return [onde-dim array] 
         */
        public function addUrl($args, $type)
        {
            switch ($type) {
                case 'book':  //给书籍添加详细书籍展示链接
                    $address = "/book/detail/book/";
                    $args['bookUrl'] = $address . $args['book_id'];
                    break;

                case 'user':  //给用户添加个人中心链接
                    $address = "/person/index/person/";
                    $args['userUrl'] = $address . $args['user_id'];
                    break;

                case 'twoUser':  //给用户添加个人中心链接
                    $address = "/person/index/person/";
                    for ($i=0; $i < count($args); $i++) { 
                        $args[$i]['userUrl'] = $address . $args[$i]['user_id'];
                    }
                    break;

                case 'label':  //给标签搜索添加链接
                    $address = "/search/dosearch/type/label/label/";
                    for ($i=0; $i < count($args); $i++) { 
                        $args[$i]['labelUrl'] = $address . $args[$i]['label_id'];
                    }
                    break;

                case 'college':  //给标签搜索添加链接
                    $address = "/search/dosearch/type/col/col/";
                    for ($i=0; $i < count($args); $i++) { 
                        $args[$i]['collegeUrl'] = $address . $args[$i]['col_id'];
                    }
                    break;

                case 'category':  //给标签搜索添加链接
                    $address = "/search/dosearch/type/cate/cate/";
                    for ($i=0; $i < count($args); $i++) { 
                        $args[$i]['categoryUrl'] = $address . $args[$i]['cat_id'];
                    }
                    break;
                
                default:
                    return false;
                    break;
            }
            return $args;
        }

        /**
         * 过滤html敏感字符字符
         * @param  [strin] $html
         * @return [string]  转换后的字符
         */
        public function checkHtml($html) {
            $html = stripslashes($html);

            preg_match_all("/<([^<]+)>/is", $html, $ms);

            $searchs[] = '<';
            $replaces[] = '<';
            $searchs[] = '>';
            $replaces[] = '>';

            if($ms[1]) {
                $allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br
                            |p|b|strong|i|u|em|span|ol|ul|li|blockquote
                            |object|param|embed';//允许的标签
                $ms[1] = array_unique($ms[1]);
                foreach ($ms[1] as $value) {
                    $searchs[] = "<".$value.">";
                    $value = htmlspecialchars($value);
                    $value = str_replace(array('\\','/*'), array('.','/.'), $value);
                    $skipkeys = array(
                            'onabort','onactivate','onafterprint','onafterupdate',
                            'onbeforeactivate','onbeforecopy','onbeforecut',
                            'onbeforedeactivate','onbeforeeditfocus','onbeforepaste',
                            'onbeforeprint','onbeforeunload','onbeforeupdate',
                            'onblur','onbounce','oncellchange','onchange',
                            'onclick','oncontextmenu','oncontrolselect',
                            'oncopy','oncut','ondataavailable',
                            'ondatasetchanged','ondatasetcomplete','ondblclick',
                            'ondeactivate','ondrag','ondragend',
                            'ondragenter','ondragleave','ondragover',
                            'ondragstart','ondrop','onerror','onerrorupdate',
                            'onfilterchange','onfinish','onfocus','onfocusin',
                            'onfocusout','onhelp','onkeydown','onkeypress',
                            'onkeyup','onlayoutcomplete','onload',
                            'onlosecapture','onmousedown','onmouseenter',
                            'onmouseleave','onmousemove','onmouseout',
                            'onmouseover','onmouseup','onmousewheel',
                            'onmove','onmoveend','onmovestart','onpaste',
                            'onpropertychange','onreadystatechange','onreset',
                            'onresize','onresizeend','onresizestart',
                            'onrowenter','onrowexit','onrowsdelete',
                            'onrowsinserted','onscroll','onselect',
                            'onselectionchange','onselectstart','onstart',
                            'onstop','onsubmit','onunload','javascript',
                            'script','eval','behaviour','expression',
                            'style','class'
                        );
                    $skipstr = implode('|', $skipkeys);
                    $value = preg_replace(array("/($skipstr)/i"), '.', $value);
                    if(!preg_match("/^[/|s]?($allowtags)(s+|$)/is", $value)) {
                        $value = '';
                    }
                    $replaces[] = empty($value)?'':"<".str_replace('"', '"', $value).">";
                }
            }
            $html = str_replace($searchs, $replaces, $html);
            $html = addslashes($html);

            return $html;
        }

	   	/**
         * 截词
         * @param  [string] $keyWord 关键字
         * @return [one-dim array]  
         */
        public function truncateWord($keyWord)
        {
            $k = 0;
            $lengh = mb_strlen($keyWord, 'utf-8');
            for ($i=0; $i < $lengh ; $i++) { 
                for ($j=1; $j <= $lengh-$i ; $j++) { 
                   $wordArgs[$k] = mb_substr($keyWord, $i, $j, 'utf-8');
                   $k++; 
                }
            }
            return $wordArgs;
        }

        /**
         * 多维数据转换成一维数组并去掉重复值
         * @param  [array] $args 多维数组
         * @return [one-dim array]    
         */
        public function rebuildArray($args)
        {
        	static $res = array();
        	for ($i=0; $i < count($args) ; $i++) { 
                if (is_array($args[$i])) {
                    $this->rebuildArray($args[$i]);
                } else {
                    $res[$i] = $args[$i];
                }
            }
            $res = $this->clearRepeat($res);
            return $res;
        }

        /**
         * 取出一维数组重复值
         * @param  [one-dim array] $arg 
         * @return [ond-dim array]      
         */
        public function clearRepeat($args)
        {
        	$tempArgs = array_map('serialize', $args);
            $tempArgs = array_unique($tempArgs);
            $tempArgs = array_map('unserialize', $tempArgs);
            $k = 0;
            if ($tempArgs) {
                foreach ($tempArgs as $v) {
                    $res[$k] = $v;
                    $k++; 
                }
            }
            return $res;
        }
   }
?>