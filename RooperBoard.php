<?PHP
	define('IMAGEPATH','../../PHP/rooperPHP/tragedy_commons_kai/');
	define('ZOOM',100/100);
	define('BOARDWIDTH',1519);
	define('BOARDHEIGHT',1076);
	define('DATAWIDTH',591);
	define('DATAHEIGHT',2150);
	define('FILLGAP',1);
	define('CHIPWIDTH',200);
	define('CHIPHEIGHT',200);

	
	$CANVASWIDTH=(DATAWIDTH+BOARDWIDTH*2)*ZOOM;
	$CANVASHEIGHT=BOARDHEIGHT*2*ZOOM;
	$css='';
	$javascript=HTML::header_link(
				'http://code.jquery.com/jquery-latest.js'
				);
	$title='惨劇RoopeRボード';
	$body= "";
	$svg_inner='';
	$canvas_images='';
	
	
	//データボード
	$data=Board::make_data();
	//データボードのチップ
	$chip=Chip::make_data('day',1);
	$chip=Chip::make_data('affair',1);
	$chip=Chip::make_data('loop',7);
	$chip=Chip::make_data('extra',0);
	//４箇所のボード
	$boards=Board::make_boards();
//	var_dump($data);
	$boards[3]->anyaku(0);
	
	
	$svg= HTML::wrap_tag('svg',$svg_inner,array('xmlns'=>"http://www.w3.org/2000/svg",'version'=>"1.1",'width'=>$CANVASWIDTH,'height'=>$CANVASHEIGHT));
	$body.=$svg;

	$script=make_javascript($canvas_images);$head='<script>'.$script.'</script>';
/*
	$body.="\n<canvas";
//	$body .=" style='display:none'";
	$body .=" id='canvas' width='".$CANVASWIDTH."' height='".$CANVASHEIGHT."'></canvas>";
	$body.="<br><textarea id='text'></textarea><button onclick='makePng();'>png画像を作成</button><br><img src='' alt='png' id='png'>";
//*/
include "template.html";

/**********************************************************************/

class Image{
	public $width;
	public $height;
	public $src;
	public $path;
	public $position_x;
	public $position_y;
	public $svg_image;
	public $canvas_image;
	public $name;
	
	function __construct($src,$width,$height,$position_x,$position_y,$zoom=ZOOM){
		$this->width=$width*$zoom;
		$this->height=$height*$zoom;
		$this->position_x=$position_x*ZOOM;
		$this->position_y=$position_y*ZOOM;
		$this->src=IMAGEPATH.$src;
		$this->svg_image=$this->makeSvgImage();
		$this->canvas_image=$this->makeCanvasImage();
	}

	 function makeSvgImage(){
		return HTML::single_tag('image',array('x'=>$this->position_x,'y'=>$this->position_y,'width'=>$this->width,'height'=>$this->height,'xlink:href'=>$this->src));
	}
	function makeCanvasImage(){
		return "makeImage('".$this->src."',".$this->position_x.",".$this->position_y.",".$this->width.",".$this->height.",context);\n	";
	}
}

class Board extends Image{
	function __construct($name,$position_x,$position_y,$width=BOARDWIDTH,$height=BOARDHEIGHT){
		$src='boards/'.$name.'.png';
		$this->name=$name;
		parent::__construct($src,$width,$height,$position_x,$position_y);
	}
	public static function make_boards(){
		global $svg_inner,$canvas_images;
		$boardsName=array('hospital','city','shrine','school');
		$boards[]=new Board($boardsName[0],DATAWIDTH,0);
		$boards[]=new Board($boardsName[1],DATAWIDTH,BOARDHEIGHT-FILLGAP);
		$boards[]=new Board($boardsName[2],DATAWIDTH+BOARDWIDTH-FILLGAP,0);
		$boards[]=new Board($boardsName[3],DATAWIDTH+BOARDWIDTH-FILLGAP,BOARDHEIGHT-FILLGAP);
		foreach($boards as $value){
			$svg_inner.=$value->svg_image;
			$canvas_images.=$value->canvas_image;
		}
		return $boards;
	}

	public static function make_data(){
		global $svg_inner,$canvas_images;
		$data=new Image('boards/data.png',DATAWIDTH,DATAHEIGHT,0,0);
		$canvas_images.=$data->canvas_image;
		$svg_inner=$data->svg_image;
		return $data;
	}

	function anyaku($quantity){
		global $svg_inner,$canvas_images;
		if($quantity==0)return;
		$chip_png='chip_03';
		$bias_y=$this->position_y;
		if($this->name=='shrine'||$this->name=='school')
			$bias_x=$this->position_x+1100;
		else
			$bias_x=$this->position_x;
			
		if($quantity!=3){
			$position_x=$bias_x+190;
			$position_y=$bias_y+45;
			Chip::make($chip_png,$position_x,$position_y);
		}
		if($quantity==2||4<$quantity){
			$position_x=$bias_x+285;
			$position_y=$bias_y+100;
			Chip::make($chip_png,$position_x,$position_y);
		}
		if(2<$quantity){
			$chip_png='chip_06';
			$position_x=$bias_x+85;
			$position_y=$bias_y+125;
			Chip::make($chip_png,$position_x,$position_y);
		}
	}
}


class Chip extends Image{
	function __construct($name,$position_x=0,$position_y=0,$width=CHIPWIDTH,$height=CHIPHEIGHT){
		$src='chips/'.$name.'.png';
		parent::__construct($src,$width,$height,$position_x,$position_y,$zoom=ZOOM*0.6);
	}
	
	public static function make_data($name,$position){
		global $svg_inner,$canvas_images;
		$list_position_y=array(540,670,810,950,1080,1220,1350,1490);
		if($name==='day'){			$chip_png='chip_07';$position_x=15; $position_y=$list_position_y[$position-1];}
		elseif($name==='affair'){	$chip_png='chip_08';$position_x=160;$position_y=$list_position_y[$position-1];}
		elseif($name==='loop'){	$chip_png='chip_09';$position_x=310;$position_y=$list_position_y[7-$position];}
		elseif($name==='extra'){	$chip_png='chip_10';$position_x=460;$position_y=$list_position_y[$position];}
		$chip=new Chip($chip_png,$position_x,$position_y);
		$svg_inner.=$chip->svg_image;
		$canvas_images.=$chip->canvas_image;
		return $chip;
	}
	
	public static function make($chip_png,$position_x,$position_y){
		global $svg_inner,$canvas_images;
		$chip=new Chip($chip_png,$position_x,$position_y);
		$svg_inner.=$chip->svg_image;
		$canvas_images.=$chip->canvas_image;
		return $chip;
	}
}


class Card extends Image{
	function __construct($src,$position_x=0,$position_y=0,$width=CARDWIDTH,$height=CARDWIDTH){
		parent::__construct($src,$width,$height,$position_x,$position_y);
	}
}





class HTML {
	public static function header_link(){
		if(preg_match("/\.css$/",func_get_arg(0))==true){$tag='link';$rel='stylesheet';}
		elseif(preg_match("/\.js$/",func_get_arg(0))==true){$tag='script';$rel='';}
		$funcNumArgs = func_num_args();
		$return='';
		if($funcNumArgs==1)$return=HTML::wrap_header_link($tag,func_get_arg(0),$rel);
		else foreach( func_get_args() as $value)$return .= HTML::wrap_header_link($tag,$value,$rel);
		return $return;
	}

	private static function wrap_header_link($tag,$src,$rel){
		if($tag=='script')return '<'.$tag.' src="'.$src.'"></'.$tag.'>';
		if($tag=='link')return '<'.$tag.' rel="'.$rel.'" href="'.$src.'"></'.$tag.'>';
	}
	
	public static function wrap_tag($wrapper,$inner,$element=''){
		$funcNumArgs = func_num_args();
		$wrapper_front=$wrapper;
		if($element=='')return '<'.$wrapper_front.'>'.$inner.'</'.$wrapper.'>';
		if($funcNumArgs==3)
			foreach($element as $key => $value )$wrapper_front .= ' '.$key.'="'.$value.'"';


		return '<'.$wrapper_front.'>'.$inner.'</'.$wrapper.'>';
	}
	
	public static function single_tag($tag,$element){
		$funcNumArgs = func_num_args();
		if($funcNumArgs==2)
			foreach($element as $key => $value )$tag .= ' '.$key.'="'.$value.'"';
		return '<'.$tag.' />';
	}

}
/****************************************************************************/
function make_javascript($makeimages){
$return  = <<< EOF

	$(document).ready(function(){
		setBoard();
	});
	function setBoard() {
		//描画コンテキストの取得
		var canvas = document.getElementById('canvas');
		if ( ! canvas || ! canvas.getContext ) { return false; }
		var context = canvas.getContext('2d');
		$makeimages
	}

	function makeImage(src,position_x,position_y,width,height,ctx){
		/* Imageオブジェクトを生成 */
		var img = new Image();
		img.src = src+'?' + new Date().getTime();
		/* 画像が読み込まれるのを待ってから処理を続行 */
		$(img).load(function() {
			ctx.drawImage(img,position_x,position_y,width,height);
		});
	}

	function makePng(){
		var canvas = document.getElementById('canvas');
		var url=canvas.toDataURL();
		$('#text').val(url);
		document.getElementById('png').src=url;
	}

EOF
;
	return $return;
}
/**********************************************************************************************/
