<?php 

class WaterMark{
	public $imgPath ;						//原图片路径
	private $imgInfo;						//原图片信息
	private $imgW;							//原图片宽
	private $imgH;							//原图片高
	private $img;							//原图片变量
	private $waterImgPath;					//水印图片路径
	private $waterImg;						//水印图片
	private $waterImgInfo;					//水印图片信息
	private $textColor = array(0,0,0);		//文字颜色	RGB
	private $angle = 0;						//角度
	private $fontSize = 20;					//文字大小
	private $margin_x=0;					//X边距
	private $margin_y=0;					//Y边距
	private $fontFile;						//字体
	private $text="test";					//文字
	private $location='random';				//参照位置
	private $x=0;							//打水印X坐标
	private $y=0;							//打水印Y坐标
	private $error = array(
			'IMG_NOT_EXISTS'=>'图片不存在',
			'IMG_FORMAT_NOT_ALLOW'=>'图片格式不对',
			'NOT_SET_WATERIMG'=>'没有设置水印图片',
			'WATERIMG_NOT_EXISTS'=>'水印图片不存在',
			'FONTfILE_NOT_EXISTS'=>'字体不存在',
			'WATERIMG_FAILURE'=>'水印图片失败',

		);


	function __construct(){

	}

	// 获取原图宽高格式信息
	private function imgInfo($img){
		$this->imgPath = file_exists($img)?$img:die($this->error['IMG_NOT_EXISTS']);
		$this->imgInfo = getimagesize($this->imgPath);
		$this->imgW = $this->imgInfo[0];
		$this->imgH = $this->imgInfo[1];
		switch ($this->imgInfo[2]) {
			case 3:
				$this->img = imagecreatefrompng($this->imgPath);
				break;
			case 2:
				$this->img = imagecreatefromjpeg($this->imgPath);
				break;
			case 1:
				$this->img = imagecreatefromgif($this->imgPath);
				break;
			default:
				die($this->error['IMG_FORMAT_NOT_ALLOW']);
				break;
		}
	}

	
	/**
	*文字水印的参照方向
	*@param 	$w 文字所占宽度
	*@param 	$h 文字所占高度
	*/
	public function textWaterLocation($w,$h){
		switch ($this->location) {
			case 'left_up':
				$this->x = $this->margin_x;
				$this->y = $h+$this->margin_y;
				break;
			case 'right_up':
				$this->x = $this->imgW-$w-$this->margin_x;
				$this->y = $h+$this->margin_y;
				
				break;
			case 'right_down':
				$this->x = $this->imgW-($this->margin_x+$w);
				$this->y = $this->imgH-$this->margin_y;
				break;
			case 'left_down':
				$this->x = $this->margin_x;
				$this->y = $this->imgH-($this->margin_y);
				
				break;
			default://随机
				$this->x = rand(0,$this->imgW-$w);
				$this->y = rand(0,$this->imgH-$h);
				break;
		}
	}

	// 图片水印的参照方向
	/**
	*图片水印的参照方向
	*@param 	$w 图片所占宽度
	*@param 	$h 图片所占高度
	*/
	public function imgWaterLocation($w,$h){
		switch ($this->location) {
			case 'left_up':
				$this->x = $this->margin_x;
				$this->y =$this->margin_y;
				break;
			case 'right_up':
				$this->x = $this->imgW-($this->margin_x+$w);
				$this->y = $this->margin_y;
				break;
			case 'right_down':
				$this->x = $this->imgW-($this->margin_x+$w);
				$this->y = $this->imgH-($this->margin_y+$h);
				break;
			case 'left_down':
				$this->x = $this->margin_x;
				$this->y = $this->imgH-($this->margin_y+$h);
				
				break;
			default://随机
				$this->x = rand(0,$this->imgW-$w);
				$this->y = rand(0,$this->imgH-$h);
				break;
		}
	}

	

	/**
	*返回水印图片信息
	*/
	private function getWaterImgInfo()
	{
		$this->waterImgPath = file_exists($this->waterImgPath)?$this->waterImgPath:die($this->error['WATERIMG_NOT_EXISTS']);
		$this->waterImgInfo = getimagesize($this->waterImgPath);
		switch ($this->waterImgInfo[2]) {
			case 1:	$this->waterImg = imagecreatefromgif($this->waterImgPath);break;
			case 2: $this->waterImg = imagecreatefromjpeg($this->waterImgPath);break;
			case 3: $this->waterImg = imagecreatefrompng($this->waterImgPath);break;
			default: die("图片类型不支持");break;
		}
	}

	/**
	*文字水印
	*@param 	$img 	待添加水印图片
	*@param 	$text 	水印文字
	*/
	public function TextWater($img,$text=""){
		$this->imgInfo($img);
		if(!empty($text))
			$this->text = $text;
		$color = imagecolorallocate($this->img, $this->textColor[0], $this->textColor[1],$this->textColor[2]);
		$rect = imagettfbbox($this->fontSize,$this->angle,$this->fontFile,$this->text);
		$w = abs($rect[2]-$rect[6]); 
		$h = abs($rect[3]-$rect[7]);
		$this->textWaterLocation($w,$h);
		$re = ImageTTFText($this->img,$this->fontSize,$this->angle,$this->x,$this->y,$color,$this->fontFile,$this->text);
		$this->writeInImg();
		
	}


	/**
	*图片水印
	*@param 	$img 		待添加水印图片
	*@param 	$waterImg 	水印图片
	*/
	public function ImgWater($img,$waterImg=""){
		$this->imgInfo($img);
		if(!empty($waterImg)){
			$this->waterImgPath = $waterImg;
			$this->getWaterImgInfo();
		}
		$waterImgW = $this->waterImgInfo[0];
		$waterImgH = $this->waterImgInfo[1];
		$this->imgWaterLocation($waterImgW,$waterImgH);
		if(!isset($this->waterImg))
			die($this->error['NOT_SET_WATERIMG']);
		$re= imagecopy($this->img,$this->waterImg,$this->x,$this->y,0,0,$waterImgW,$waterImgH);
		if(!$re)	die($this->error['WATERIMG_FAILURE']);
		$this->writeInImg();
	}

	/**
	*打完水印的图片保存
	*/
	public function writeInImg()
	{
		@unlink($this->imgPath);//删除图片
        switch($this->imgInfo[2]) {//取得背景图片的格式 
          case 1:imagegif($this->img,$this->imgPath);break; 
          case 2:imagejpeg($this->img,$this->imgPath);break; 
          case 3:imagepng($this->img,$this->imgPath);break; 
          default: exit($this->error['WATERIMG_FAILURE']); 
        } 
        // echo '<img src="'.$this->imgPath.'" border="0" />';
	}


	public function setTextColor($textColor){
		$this->textColor = $textColor;
	}

	public function setFontSize($fontSize){
		$this->fontSize = $fontSize;
	}
	public function setLocation($location){
		$this->location = $location;
	}
	public function setAngle($angle){
		$this->angle = $angle;
	}

	public function setFontFile($ttf){
		$this->fontFile = file_exists($ttf)?$ttf:die($this->error['FONTfILE_NOT_EXISTS']);
	}
	public function setText($text){
		$this->text = $text;
	}
	public function setMargin($margin_x,$margin_y){
		$this->margin_x = $margin_x;
		$this->margin_y = $margin_y;
	}
	public function setWaterImg($waterImgPath){
		$this->waterImgPath = $waterImgPath;
		$this->getWaterImgInfo();
	}



}



 ?>