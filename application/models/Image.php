<?php

   /**
   * 处理图片文件上传和图片剪切
   */
   class Application_Model_Image
   {

   	   protected $myFile;
   	
	   	function __construct()
	   	{
	   		$this->myFile = new Zend_File_Transfer_Adapter_Http();
	   	}

	   	/**
	   	 * 图片上传及剪裁处理
	   	 * @param  [string] $file  文件名
	   	 * @param  [string] $name  新的文件名
	   	 * @return [boole]  
	   	 */
	   	public function fileUpload($file, $name) 
	   	{
	   		$path = APPLICATION_PATH . '/../public/photo/book/';
	   		$this->myFile->setDestination($path);
	   		$this->myFile->addValidator('Extension', false, array('jpg', 'jpeg', 'png', 'gif'));
	   		$this->myFile->addValidator('FilesSize', false, array('min' => '10kb', 'max' => '1MB'));
	   		$fileInfo = $this->myFile->getFileInfo($file);
	   		$extName = $this->getExtension($fileInfo);
	   		$fileName = $name . "." . $extName;
	   		$this->myFile->addFilter('Rename', array('target' => $fileName, 'overwrite' => true));
	   		if ($this->myFile->receive()) {
	   			if ($this->thumbImage($fileName)) {
	   				return $fileName;
	   			} else {
	   				return false;
	   			}
	   		} else {
	   			return false;
	   		}
	   	}

	   	/**
	   	 * 删除某个文件
	   	 * @param  [string] $name 带后缀的文件名
	   	 */
	   	public function delImage($name)
	   	{
	   		$uploadPath = APPLICATION_PATH . '/../public/photo/book/' . $name;
	   		$thumbPath = APPLICATION_PATH . '/../public/photo/book/thumb/' . $name;
	   		if (file_exists($uploadPath)) {
	   			// chmod($uploadPath, 0777);
	   			unlink($uploadPath);
	   		}
	   		if (file_exists($thumbPath)) {
	   			unlink($thumbPath);
	   		}
	   	}

	   	/**
	   	 * 获取文件名扩展名
	   	 * @param  [string or array] $name 文件名或者zf获取到的文件信息
	   	 * @return [string]   扩展名
 	   	 */
	   	public function getExtension($name)
	   	{
	   		$fName = '';
	   		if ($name) {
	   			if (is_array($name)) {
	   				foreach ($name as $k => $v) {
		   				$fName = $v['name'];
		   			}
	   			} else {
	   				$fName = $name;
	   			}
	   			$nameWords = @split("[\.]", $fName);
	   			$n = count($nameWords)-1;
	   			$exts = $nameWords[$n];
	   			return $exts;
	   		}
	   	}

	   	/**
	   	 * 剪裁图片
	   	 * @param  [string] $name  文件名
	   	 * @return [string]  文件名
	   	 */
	   	public function thumbImage($name)
	   	{
	   		$imgInfo = array();
	   		$width = '285';
	   		$height = '385';
	   		$newSize = array();
	   		$path = APPLICATION_PATH . '/../public/photo/book/';
	   		$newPath = APPLICATION_PATH . '/../public/photo/book/thumb/' . $name;
	   		$imgPath = $path . $name;
	   		list($imgInfo['width'], $imgInfo['height'], $imgInfo['type']) = getimagesize($imgPath);
	   		switch ($imgInfo['type']) {
	   			case '1':
	   				$temImg = imagecreatefromgif($imgPath);
	   				break;
	   			case '2':
	   				$temImg = imagecreatefromjpeg($imgPath);
	   				break;
	   			case '3':
	   				$temImg = imagecreatefrompng($imgPath);
	   				break;
	   			default:
	   				return false;
	   				break;
	   		}
	   		$col = imagecolortransparent($temImg);
	   		if ($col > 0 && $col < imagecolorstotal($temImg)) {
	   			$tranCol = imagecolorsforindex($temImg, $col);
	   			$newCol = imagecolorallocate($temImg, $tranCol['red'], $tranCol['green'], $tranCol['blue']);
	   			imagecolortransparent($temImg, $newCol);
	   		}
	   		if ($imgInfo['width'] < $imgInfo['height']) {
	    		$newSize['width'] = ceil($imgInfo['width']*$height/$imgInfo['height']);
	    		$newSize['height'] = $height;
	    	} else {
	    		$newSize['height'] = ceil($imgInfo['height']*$width/$imgInfo['width']);
	    		$newSize['width'] = $width;
	    	}
	    	$thumbImg = imagecreatetruecolor($newSize['width'], $newSize['height']);
	    	imagecopyresized($thumbImg, $temImg, 0, 0, 0, 0, $newSize['width'], $newSize['height'], $imgInfo['width'], $imgInfo['height']);
	    	switch ($imgInfo['type']) {
	    		case '1':
	    			imagegif($thumbImg, $newPath);
	    			break;
	    		case '2':
	    			imagejpeg($thumbImg, $newPath);
	    		case '3':
	    			imagepng($thumbImg, $newPath);
	    			break;
	    	}
	    	if (file_exists($newPath)) {
	    		return $name;
	    	} else {
	    		return false;
	    	}
	   	}
   }

?>