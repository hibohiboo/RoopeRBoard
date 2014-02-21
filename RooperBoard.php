<?PHP
	define('IMAGEPATH','../../PHP/rooperPHP/tragedy_commons_kai/');
	if(isset($_GET['m'])==true){
		define('ZOOM',intval($_GET['m'])/100);
	}else{
		define('ZOOM',30/100);
	}
	define('CARDZOOM',90/100);
	define('CHIPZOOM',60/100);
	define('BOARDWIDTH',1519);
	define('BOARDHEIGHT',1076);
	define('DATAWIDTH',591);
	define('DATAHEIGHT',2150);
	define('FILLGAP',1);
	define('CHIPWIDTH',200);
	define('CHIPHEIGHT',200);
	define('CHIPMARGINLEFT',30);
	define('CHIPMARGINTOP',50);
	define('CARDWIDTH',373);
	define('CARDHEIGHT',526);
	define('CARDPADDINGLEFT',30);
	define('CARDPADDINGTOP',50);
	define('HANDMARGINLEFT',CARDWIDTH*1/9);
	define('HANDMARGINTOP',CARDHEIGHT*1/5/8);
	define('OPENHANDMARGINLEFT',CARDWIDTH*1/9);
	define('OPENHANDMARGINTOP',CARDHEIGHT*1/2);
	
	
	
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
	$canvas_rotate='';
	$canvas_rotate_x=0;
	$canvas_rotate_y=0;
	$flg_canvas_rotate=0;
	
	//データボード
	$data=Board::make_data();
	//データボードのチップ

	if(isset($_GET['d'])==true)$chip=Chip::make_data('day',intval($_GET['d']));
	if(isset($_GET['l'])==true)$chip=Chip::make_data('loop',intval($_GET['l']));
	if(isset($_GET['e'])==true)$chip=Chip::make_data('extra',intval($_GET['e']));
	for($i=1;$i<9;$i++){
		if(isset($_GET['j'.$i])==true)$chip=Chip::make_data('affair',intval($_GET['j'.$i]));
	}

	//４箇所のボード
	$boards=Board::make_boards();
//	var_dump($data);
	for($i=0;$i<4;$i++){
		//if(isset($_GET['b'.$i])===false){$_GET['b'.$i]=0;}
		$boards[$i]->set_anyaku($_GET['b'.$i]);
	}
	for($i=0;$i<20;$i++){
		if(isset($_GET['u'.$i])===true){
			if(isset($_GET['d'.$i])===true){
				$char=$boards[$_GET['p'.$i]]->set_character($i,'die');
			}else{
				$char=$boards[$_GET['p'.$i]]->set_character($i);
			}
			foreach(array('w','r','i') as $w){
				if($_GET['r'.$w]==$i){
					$char->set_hand('writer',$_GET[$w]);
				}
			}
			foreach(array('A','B','C') as $w){
				if($_GET['r'.$w]==$i){
					$char->set_hand('hero'.$w,$_GET[$w]);
				}
			}
			$char->set_counters($_GET['y'.$i],$_GET['h'.$i],$_GET['a'.$i]);
			if(isset($_GET['d'.$i])===true){
				$char->end_g();
			}
		}
	}
	/*
	$boards[1]->set_character(0,'')->set_hand('writer','0b')->set_hand('heroB','0b')->set_counters(9,1,1)->end_g();
	$boards[0]->set_character(1)->set_hand('heroC','0b')->set_counters(0,3,1);
	$boards[2]->set_character(2)->set_counters(0,0,2);
	$boards[0]->set_character(3,'g')->set_counters(1,4,0)->end_g();
	$boards[0]->set_character(4)->set_counters(2,3,0);
	$boards[0]->set_character(5)->set_hand('heroA','02')->set_counters(0,3,0);
	$boards[0]->set_character(6)->set_counters(0,2,0);
	$boards[0]->set_character('神格','g');
	*/
	$svg= HTML::wrap_tag('svg',$svg_inner,array('xmlns'=>"http://www.w3.org/2000/svg",'version'=>"1.1",'width'=>$CANVASWIDTH,'height'=>$CANVASHEIGHT));
	$body.=$svg;
	
	if(isset($_GET['o'])===true&&$_GET['o']==='1'){
		//$script='';
		$head='';
	}else{
		$script=make_javascript($canvas_images);$head='<script>'.$script.'</script>';
		$body.="\n<canvas";
		$body .=" style='display:none'";
		$body .=" id='canvas' width='".$CANVASWIDTH."' height='".$CANVASHEIGHT."'></canvas>";
		$body.="<br><textarea style='display:none' id='text'></textarea>";
		$body.="<button onclick='makePng();'>png画像を作成</button><br>";
		$body.="<progress id='progress' value='80' max='100'>現在、画像をダウンロード中です。</progress><span id='imgmessage'>ただいまpng画像準備中です。</span><br>";
		$body.="<div style='width:$CANVASWIDTH"."px;height:$CANVASHEIGHT"."px;border:solid 3px #000000;'><img width='$CANVASWIDTH' height='$CANVASHEIGHT' src='' alt='png' id='png'></div>";
	}
//
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
		global $svg_inner;
		$return=HTML::single_tag('image',array('x'=>intval($this->position_x),'y'=>intval($this->position_y),'width'=>intval($this->width),'height'=>intval($this->height),'xlink:href'=>$this->src));
		$svg_inner.=$return;
		return $return;
	}
	function makeCanvasImage(){
		global $canvas_images,$canvas_rotate,$canvas_rotate_x,$canvas_rotate_y,$flg_canvas_rotate;
		$return = "['".$this->src."',".intval($this->position_x).",".intval($this->position_y).",".intval($this->width).",".intval($this->height)."],";
		$canvas_images.=$return;
		$canvas_rotate.='['.$flg_canvas_rotate.','.$canvas_rotate_x.','.$canvas_rotate_y.'],';
		return $return;
	}
}
/*==============================*/
class Board extends Image{
	public $charList;
	const CHIPPOSITIONRIGHT= 1100;
	const CHARMARGINTOP = 250;
	
	function __construct($name,$position_x,$position_y,$width=BOARDWIDTH,$height=BOARDHEIGHT){
		$src='boards/'.$name.'.png';
		$this->name=$name;
		$this->charList=array();
		parent::__construct($src,$width,$height,$position_x,$position_y);
	}
	public static function make_boards(){
		//global $svg_inner,$canvas_images;
		$boardsName=array('hospital','city','shrine','school');
		$boards[]=new Board($boardsName[0],DATAWIDTH,0);
		$boards[]=new Board($boardsName[1],DATAWIDTH,BOARDHEIGHT-FILLGAP);
		$boards[]=new Board($boardsName[2],DATAWIDTH+BOARDWIDTH-FILLGAP,0);
		$boards[]=new Board($boardsName[3],DATAWIDTH+BOARDWIDTH-FILLGAP,BOARDHEIGHT-FILLGAP);
//		foreach($boards as $value){
			//$svg_inner.=$value->svg_image;
			//$canvas_images.=$value->canvas_image;
//		}
		return $boards;
	}

	public static function make_data(){
		//global $svg_inner,$canvas_images;
		$data=new Image('boards/data.png',DATAWIDTH,DATAHEIGHT,0,0);
		//$canvas_images.=$data->canvas_image;
		//$svg_inner=$data->svg_image;
		return $data;
	}

	function set_anyaku($quantity){
		//global $svg_inner,$canvas_images;
		if($quantity==0)return;
		$chip_png='chip_03';
		$bias_y=$this->position_y/ZOOM;
		if($this->name=='shrine'||$this->name=='school')
			$bias_x=$this->position_x/ZOOM+self::CHIPPOSITIONRIGHT;
		else
			$bias_x=$this->position_x/ZOOM;
			
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
	function set_character($name,$g=false){//,$yuko=0,$huan=0,$anyaku=0){
		global $svg_inner,$canvas_rotate_x,$canvas_rotate_y,$flg_canvas_rotate;
		$charnumber=count($this->charList);
		if($charnumber==0)$bias_y=self::CHARMARGINTOP;
		elseif(0<$charnumber&&$charnumber<4)$bias_y=CARDPADDINGTOP;
		elseif(3<$charnumber)$bias_y=CARDPADDINGTOP*2+CARDHEIGHT*CARDZOOM;
		if($charnumber>3)$charnumber-=3;
		$position_x=$this->position_x/ZOOM+(CARDPADDINGLEFT+CARDWIDTH*CARDZOOM)*$charnumber+30;
		$position_y=$this->position_y/ZOOM+$bias_y;
		if($g!==false){
			$canvas_rotate_x=($position_x+CARDWIDTH*CARDZOOM/2)*ZOOM;
			$canvas_rotate_y=($position_y+CARDHEIGHT*CARDZOOM/2)*ZOOM;
			$flg_canvas_rotate=1;
			$svg_inner .= '<g transform="rotate(-90,'.$canvas_rotate_x.','.$canvas_rotate_y.') translate(0,0) ">';
		}
		array_push($this->charList,$name);
		$char=Character::make($name,$position_x,$position_y);
//		$char->set_counters($yuko,$huan,$anyaku);
		return $char;
	}
}

/*==============================*/

class Card extends Image{
	function __construct($src,$position_x=0,$position_y=0,$width=CARDWIDTH,$height=CARDHEIGHT){
		parent::__construct($src,$width*CARDZOOM,$height*CARDZOOM,$position_x,$position_y);
	}
	public static function make($src,$position_x=0,$position_y=0){
		//global $svg_inner,$canvas_images;
		$card=new Card($src,$position_x,$position_y);
		//$svg_inner.=$card->svg_image;
		//$canvas_images.=$card->canvas_image;
		return $card;
	}
}
/*==============================*/
class Character extends Card{
	public $counter;
	public $hand=0;
	function __construct($src,$position_x=0,$position_y=0){
		$src='chara_cards/'.$src;
		$this->counter=array();
		parent::__construct($src,$position_x,$position_y);
	}
	public static function make($name_or_number,$position_x=0,$position_y=0){
		//global $svg_inner,$canvas_images;
		$char_card_list=array('chara_cards_01_00.png','chara_cards_02_00.png','chara_cards_03_00.png','chara_cards_04_00.png','chara_cards_05_00.png','chara_cards_06_00.png','chara_cards_07_00.png','chara_cards_08_00.png','chara_cards_09_00.png','chara_cards_10_00.png','chara_cards_11_00.png','chara_cards_12_00.png','chara_cards_13_00.png','chara_cards_14_00.png','chara_cards_15_00.png','chara_cards_16_00.png','chara_cards_17_00.png','chara_cards_18_00.png','chara_cards_19_00.png','chara_cards_20_00.png');
		$char_list=array("男子学生","女子学生","お嬢様","巫女","刑事","サラリーマン","情報屋","医者","患者","委員長","イレギュラー","異世界人","神格","アイドル","マスコミ","大物","ナース","手先","学者","幻想");
		$check_name=array_search($name_or_number,$char_list);
		if($check_name===false)$char_png=$char_card_list[$name_or_number];
		else $char_png=$char_card_list[$check_name];
		
		$char=new Character($char_png,$position_x,$position_y);
		//$svg_inner.=$char->svg_image;
		//$canvas_images.=$char->canvas_image;
		return $char;
	}
	public function set_counters($yuko=0,$huan=0,$anyaku=0){
		$step=0;
		if(0<$yuko)		$this->pre_set_counter('友好',$yuko,$step++);
		if(0<$huan)		$this->pre_set_counter('不安',$huan,$step++);
		if(0<$anyaku)	$this->pre_set_counter('暗躍',$anyaku,$step++);
		return $this;
	}
	private function pre_set_counter($kind,$counter,$step){
		if($kind==='友好'){		$small_chip='chip_01';$big_chip='chip_04';}
		elseif($kind==='不安'){	$small_chip='chip_02';$big_chip='chip_05';}
		elseif($kind==='暗躍'){	$small_chip='chip_03';$big_chip='chip_06';}
		$big_number=floor($counter/3);
		$small_number=$counter%3;
		for($i=0;$i<$big_number;$i++)$this->set_counter($big_chip,$i,$step);
		for($i=0;$i<$small_number;$i++)$this->set_counter($small_chip,$i+$big_number,$step);
	}
	private function set_counter($chip,$quantity,$step){//$quantity
		$position_x=$this->position_x/ZOOM+CHIPMARGINLEFT+$quantity*CHIPWIDTH*CHIPZOOM/2;
		$position_y=$this->position_y/ZOOM+CHIPMARGINTOP+CHIPHEIGHT*CHIPZOOM*$step/1.3;
		Chip::make($chip,$position_x,$position_y);
	}
	
	public function set_hand_name($player='',$hand_name=''){
		if($player=='' || $hand_name=='')return $this;
		if($player=='writer')	$hand_list=array('裏'=>'0b','不安+1'=>'01','不安+1'=>'02','不安-1'=>'03','不安禁止'=>'04','友好禁止'=>'05','暗躍+1'=>'06','暗躍+2'=>'07','移動上下'=>'08','移動左右'=>'09','移動斜め'=>'10');
		else 					$hand_list=array('裏'=>'0b','不安+1'=>'01','不安-1'=>'02','友好+1'=>'03','友好+2'=>'04','暗躍禁止'=>'05','移動上下'=>'06','移動左右'=>'07','移動禁止'=>'08');
		$this->set_hand($player,$hand_list[$hand_name]);
		return $this;
	}
	
	public function set_hand($player='',$hand_number=''){
		if($player=='' || $hand_number=='')return $this;
		$src='action_cards/'.'a_'.$player.'_cards_'.$hand_number.'.png';
		$bias_x= $player==='writer' ? 0 : HANDMARGINLEFT;
		$bias_y= $player==='writer' ? 0 : HANDMARGINTOP;
		
		$position_x=$this->position_x/ZOOM+HANDMARGINLEFT+$bias_x;
		$position_y=$this->position_y/ZOOM+HANDMARGINTOP+$bias_y;
		if($player!='writer'&&$hand_number!='0b'){
			$position_x+=OPENHANDMARGINLEFT;
			$position_y+=OPENHANDMARGINTOP;
		}
		Card::make($src,$position_x,$position_y);
		return $this;
	}
	public function end_g(){
		global $svg_inner,$flg_canvas_rotate;
		$flg_canvas_rotate=0;
		$svg_inner.='</g>';
		return $this;
	}
}
/*==============================*/

class Chip extends Image{
	function __construct($name,$position_x=0,$position_y=0,$width=CHIPWIDTH,$height=CHIPHEIGHT){
		$src='chips/'.$name.'.png';
		parent::__construct($src,$width*CHIPZOOM,$height*CHIPZOOM,$position_x,$position_y);
	}
	
	public static function make_data($name=1,$position=1){
		if($name==''||$position=='')return;
		$position=intval($position);
		//global $svg_inner,$canvas_images;
		$list_position_y=array(540,670,810,950,1080,1220,1350,1490);
		if($name=='day'){			$chip_png='chip_07';$position_x=15; $position_y=$list_position_y[$position-1];}
		elseif($name==='affair'){	$chip_png='chip_08';$position_x=160;$position_y=$list_position_y[$position-1];}
		elseif($name==='loop'){	$chip_png='chip_09';$position_x=310;$position_y=$list_position_y[7-$position];}
		elseif($name==='extra'){	$chip_png='chip_10';$position_x=460;$position_y=$list_position_y[$position];}
		$chip=new Chip($chip_png,$position_x,$position_y);
		//$svg_inner.=$chip->svg_image;
		//$canvas_images.=$chip->canvas_image;
		return $chip;
	}
	
	public static function make($chip_png,$position_x,$position_y){
		//global $svg_inner,$canvas_images;
		$chip=new Chip($chip_png,$position_x,$position_y);
		//$svg_inner.=$chip->svg_image;
		//$canvas_images.=$chip->canvas_image;
		return $chip;
	}
}

/*==============================*/
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
/*==============================*/
/****************************************************************************/
function make_javascript($canvas_images){
	global $canvas_rotate;
	$canvas_rotate=rtrim($canvas_rotate, ",");//最後のコンマを削除
	$canvas_images=rtrim($canvas_images, ",");//最後のコンマを削除
$return  = <<< EOF

	$(document).ready(function(){
		setBoard();
	});
	function setBoard() {
		//描画コンテキストの取得
		var canvas = document.getElementById('canvas');
		if ( ! canvas || ! canvas.getContext ) { return false; }
		var context = canvas.getContext('2d');
		var canvas_images=[$canvas_images];
		var canvas_rotate=[$canvas_rotate];

		recursion_makeImages(canvas_images[0][0],canvas_images[0][1],canvas_images[0][2],canvas_images[0][3],canvas_images[0][4],context,canvas_images,canvas_images.length,0,canvas_rotate);
	}
	function recursion_makeImages(src,position_x,position_y,width,height,ctx,arr,length,i,rotate){
		//* Imageオブジェクトを生成 

		var img = new Image();
		img.src = src+'?' + new Date().getTime();
		//* 画像が読み込まれるのを待ってから処理を続行 
		$(img).load(function() {
			// 保存しておく
			ctx.save();
			if(rotate[i][0]==1){
				ctx.translate(rotate[i][1],rotate[i][2]);
				ctx.rotate(-90 * Math.PI / 180);
				ctx.translate(-rotate[i][1],-rotate[i][2]);
			}
			ctx.drawImage(img,position_x,position_y,width,height);
			i++;
			// 戻す
			ctx.restore();
			if(i==length){
				$('#progress').val(100);
				$('#button').attr("disabled",false);
				$('#imgmessage').html("png画像を作成する準備ができました。");
				return;
			}
			recursion_makeImages(arr[i][0],arr[i][1],arr[i][2],arr[i][3],arr[i][4],ctx,arr,length,i,rotate);
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
