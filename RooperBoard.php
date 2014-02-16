<?PHP
	define('IMAGEPATH','../../PHP/rooperPHP/tragedy_commons_kai/');
	define('ZOOM',30/100);
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
	
	
	
	$data=new Image('boards/data.png',DATAWIDTH,DATAHEIGHT,0,0);
	$svg_inner=$data->svg_image;
	$boards=Board::make_boards();
	foreach($boards as $value){
		$svg_inner.=$value->svg_image;
//		$canvas_images.=$value->canvas_image;
	}
//	var_dump($data);
	$script=make_javascript($canvas_images);
	$head='<script>'.$script.'</script>';
	
	
	$svg= HTML::wrap_tag('svg',$svg_inner,array('xmlns'=>"http://www.w3.org/2000/svg",'version'=>"1.1",'width'=>$CANVASWIDTH,'height'=>$CANVASHEIGHT));
	$body.=$svg;
//	$body.="\n<canvas";
//	$body .=" style='display:none'";
//	$body .=" id='canvas' width='".((int)$boards[0]->width*2)."' height='".((int)$boards[0]->height*2)."'></canvas>";
	$body.="<br><textarea id='text'></textarea><button onclick='makePng();'>png画像を作成</button><br><img src='' alt='png' id='png'>";

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
	
	function __construct($src,$width,$height,$position_x,$position_y){
		$this->width=$width*ZOOM;
		$this->height=$height*ZOOM;
		$this->position_x=$position_x*ZOOM;
		$this->position_y=$position_y*ZOOM;
		$this->src=IMAGEPATH.$src;
		$this->svg_image=$this->makeSvgImage();
//		$this->canvas_image=$this->makeSvgImage();
	}

	 function makeSvgImage(){
		return HTML::single_tag('image',array('x'=>$this->position_x,'y'=>$this->position_y,'width'=>$this->width,'height'=>$this->height,'xlink:href'=>$this->src));
	}
	function makeCanvasImage(){
		return "makeImage('".$this->src."',".$this->position_x.",".$this->position_y.",".$this->width.",".$this->height.",context);\n	";
	}
}

class Board extends Image{
	function __construct($name,$width,$height,$position_x,$position_y){
		$src='boards/'.$name.'.png';
		parent::__construct($src,$width,$height,$position_x,$position_y);
	}
	public static function make_boards(){
		$boardsName=array('hospital','city','shrine','school');
		$boards[]=new Board($boardsName[0],BOARDWIDTH,BOARDHEIGHT,DATAWIDTH,0);
		$boards[]=new Board($boardsName[1],BOARDWIDTH,BOARDHEIGHT,DATAWIDTH,BOARDHEIGHT-FILLGAP);
		$boards[]=new Board($boardsName[2],BOARDWIDTH,BOARDHEIGHT,DATAWIDTH+BOARDWIDTH-FILLGAP,0);
		$boards[]=new Board($boardsName[3],BOARDWIDTH,BOARDHEIGHT,DATAWIDTH+BOARDWIDTH-FILLGAP,BOARDHEIGHT-FILLGAP);
		return $boards;
	}
}

class Card extends Image{
	function __construct($name,$position_x,$position_y,$width,$height,$zoom=100){
		$src='boards/'.$name.'.png';
		parent::__construct($src,$width,$height);
		$this->position_x=$position_x*ZOOM;
		$this->position_y=$position_y*ZOOM;
		$this->svg_image=HTML::single_tag('image',array('x'=>$this->position_x,'y'=>$this->position_y,'width'=>$this->width,'height'=>$this->height,'xlink:href'=>$this->src));
		$this->canvas_image="makeImage('".$this->src."',".$this->position_x.",".$this->position_y.",".$this->width.",".$this->height.",context);\n	";
	}
}


class Chips extends Image{
	function __construct($name,$position_x,$position_y,$width,$height,$zoom=100){
		$src='chips/'.$name.'.png';
		parent::__construct($src,$width,$height);
		$this->position_x=$position_x*ZOOM;
		$this->position_y=$position_y*ZOOM;
		$this->svg_image=HTML::single_tag('image',array('x'=>$this->position_x,'y'=>$this->position_y,'width'=>$this->width,'height'=>$this->height,'xlink:href'=>$this->src));
		$this->canvas_image="makeImage('".$this->src."',".$this->position_x.",".$this->position_y.",".$this->width.",".$this->height.",context);\n	";
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
		makePng();
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
