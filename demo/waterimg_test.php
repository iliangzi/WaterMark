		<?php 
		include "./../WaterMark.class.php";

		// 水印对象
		$water = new WaterMark();


		//公共参数 
		/**
		初始化参照点参数	默认为随机
		setLocation('下面的参数'); 
		left_up		左上角
		left_down	左上角
		right_up	右上角
		right_down	右下角
		*/
		$water->setLocation('right_up');//左下角

		/**
		设置水印与参照点距离	默认(0,0)
		setMargin(X,Y);
		*/
		 $water->setMargin(100,100);




		// 文字水印参数
		/**
		设置文字颜色	默认(0,0,0)黑色
		setTextColor(arra(R,G,B));
		*/
		$water->setTextColor(array(0,0,0));
		/**
		设置文字大小	默认20
		setFontSize('文字大小');
		*/
		$water->setFontSize(16);

		/**
		设置字体	默认宋体
		setFontFile('字体路径');
		*/
		$water->setFontFile('./simsun.ttc');

		/**
		设置旋转角度	默认0	逆时针转	未考虑旋转时定位像素偏差
		setAngle('角度');
		*/
		$water->setAngle(0);

		/**
		设置水印文字	默认test
		setText("水印文字");
		*/
		$water->setText("hello world");

		/**
		打文字水印
		TextWater('被打水印图片路径','水印文字[可选]')
		*/
		$water->TextWater('./image/1.jpg');
		// 或
		// $water->TextWater('./image/1.jpg','test textwater');



		//图片水印参数
		/**
		设置水印图片
		setWaterImg('水印图片路径');
		*/
		$water->setWaterImg('./image/2.jpg');

		//打水印
		/**
		ImgWater('被打水印图片路径','水印图片[可选]');
		*/
		$water->ImgWater('./image/1.jpg');
		// 或
		// $water->ImgWater('./image/1.jpg','./image/2.jpg');
		?>