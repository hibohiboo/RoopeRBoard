<?php

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
	private $num_chars=0;
	private $num;
	const CHIPPOSITIONRIGHT= 1100;
	const CHARMARGINTOP = 250;
	
	function __construct($num,$position_x,$position_y,$width=BOARDWIDTH,$height=BOARDHEIGHT){
		global $boardsName;
		$this->num=$num;
		$name=$boardsName[$num];
		$src='boards/'.$name.'.png';
		$this->name=$name;
		$this->charList=array();
		parent::__construct($src,$width,$height,$position_x,$position_y);
		$this->num_chars=$this->count_chars();
	}
	
	private function count_chars(){
		$return=0;
		for($i=0;$i<CHARNUM;$i++){
			if($_GET['u'.$i]=== null || $_GET['p'.$i] != $this->num){continue;}
			$return++;
		}
		return $return;
	}
	
	public static function make_boards(){

		$boards[]=new Board(0,DATAWIDTH,0);
		$boards[]=new Board(1,DATAWIDTH,BOARDHEIGHT-FILLGAP);
		$boards[]=new Board(2,DATAWIDTH+BOARDWIDTH-FILLGAP,0);
		$boards[]=new Board(3,DATAWIDTH+BOARDWIDTH-FILLGAP,BOARDHEIGHT-FILLGAP);
		return $boards;
	}

	public static function make_data(){
		$data=new Image('boards/data.png',DATAWIDTH,DATAHEIGHT,0,0);
		return $data;
	}

	function set_anyaku($quantity=0){
		//global $svg_inner,$canvas_images;
		if($quantity==0)return;
		$chip_png='chip_03';
		$bias_y=$this->position_y/ZOOM;
		if($this->name=='shrine'||$this->name=='school')
			$bias_x=$this->position_x/ZOOM+self::CHIPPOSITIONRIGHT;
		else
			$bias_x=$this->position_x/ZOOM;
			
		if($quantity!=3){
			$position_x=$bias_x+CHIP1X;
			$position_y=$bias_y+CHIP1Y;
			Chip::make($chip_png,$position_x,$position_y);
		}
		if($quantity==2||4<$quantity){
			$position_x=$bias_x+CHIP2X;
			$position_y=$bias_y+CHIP2Y;
			Chip::make($chip_png,$position_x,$position_y);
		}
		if(2<$quantity){
			$chip_png='chip_06';
			$position_x=$bias_x+CHIP3X;
			$position_y=$bias_y+CHIP3Y;
			Chip::make($chip_png,$position_x,$position_y);
		}
	}
	function set_character($name,$g=false){//,$yuko=0,$huan=0,$anyaku=0){
		global $svg_inner,$canvas_rotate_x,$canvas_rotate_y,$flg_canvas_rotate;
		$capacity=intval($this->width/(CARDWIDTH*ZOOM*CARDZOOM));

		$charnumber=count($this->charList);
//var_dump($this->num_chars);
		if((($capacity-1)*2+1)<$this->num_chars){
			$bias_y_step=CARDHEIGHT*CARDZOOM/2;
		}else{
			$bias_y_step=CARDHEIGHT*CARDZOOM;
		}
		if($charnumber==0){$bias_y=self::CHARMARGINTOP;
		}elseif(0<$charnumber&&$charnumber<$capacity){$bias_y=CARDPADDINGTOP;
		}elseif($capacity<=$charnumber&&$charnumber<(($capacity-1)*2+1)){$bias_y=CARDPADDINGTOP*2+$bias_y_step;
		}elseif((($capacity-1)*2+1)<=$charnumber){$bias_y=CARDPADDINGTOP*3+$bias_y_step*2;
		}
		
		if($capacity<=$charnumber&&$charnumber<(($capacity-1)*2+1)){$charnumber-=$capacity-1;
		}elseif((($capacity-1)*2+1)<=$charnumber){$charnumber-=($capacity-1)*2;
		}
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
		return $char;
	}
	
	function set_rumor(){
		global $svg_inner,$canvas_rotate_x,$canvas_rotate_y,$flg_canvas_rotate;
		$position_x=$this->position_x/ZOOM+BOARDWIDTH-CARDWIDTH;
		$position_y=$this->position_y/ZOOM+BOARDHEIGHT-CARDHEIGHT/2;
		$flg_canvas_rotate=1;
		$canvas_rotate_x=($position_x+CARDWIDTH*CARDZOOM/2)*ZOOM;
		$canvas_rotate_y=($position_y+CARDHEIGHT*CARDZOOM/2)*ZOOM;
		$svg_inner .= '<g transform="rotate(-90,'.$canvas_rotate_x.','.$canvas_rotate_y.') translate(0,0) ">';
		$src='extra/ex_card_d.png';
		Card::make($src,$position_x,$position_y);
		return $this;
	}
	
	public function set_hand($player='',$hand_number=''){
		global $svg_inner,$canvas_rotate_x,$canvas_rotate_y,$flg_canvas_rotate;
		if($player=='' || $hand_number=='' || $hand_number=='-1'|| $hand_number=='0')return $this;
		$src='action_cards/'.'a_'.$player.'_cards_'.$hand_number.'.png';
		$bias_x= $player==='writer' ? 0 : HANDMARGINLEFT;
		$bias_y= $player==='writer' ? 0 : HANDMARGINTOP;
		
		$position_x=$this->position_x/ZOOM+HANDMARGINLEFT+$bias_x;
		$position_y=$this->position_y/ZOOM+HANDMARGINTOP+self::CHARMARGINTOP*2.3+$bias_y;
		if($player!='writer'&&$hand_number!='0b'){
			$position_x+=OPENHANDMARGINLEFT;
			$position_y+=OPENHANDMARGINTOP;
		}

		$canvas_rotate_x=($position_x+CARDWIDTH*CARDZOOM/2)*ZOOM;
		$canvas_rotate_y=($position_y+CARDHEIGHT*CARDZOOM/2)*ZOOM;
		$flg_canvas_rotate=1;
		$svg_inner .= '<g transform="rotate(-90,'.$canvas_rotate_x.','.$canvas_rotate_y.') translate(0,0) ">';
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

class Card extends Image{
	function __construct($src,$position_x=0,$position_y=0,$width=CARDWIDTH,$height=CARDHEIGHT){
		parent::__construct($src,$width*CARDZOOM,$height*CARDZOOM,$position_x,$position_y);
	}
	public static function make($src,$position_x=0,$position_y=0){
		$card=new Card($src,$position_x,$position_y);
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
		global $char_list,$char_card_list;
		$check_name=array_search($name_or_number,$char_list);
		if($check_name===false)$char_png=$char_card_list[$name_or_number];
		else $char_png=$char_card_list[$check_name];
		
		$char=new Character($char_png,$position_x,$position_y);
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
	public function set_kasi(){
		$src='extra/ex_card_a.png';
		$bias_x=HANDMARGINLEFT;
		$bias_y=HANDMARGINTOP;
		
		$position_x=$this->position_x/ZOOM+HANDMARGINLEFT+$bias_x;
		$position_y=$this->position_y/ZOOM+HANDMARGINTOP+$bias_y;
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
		if($name==''||$position==null)return;
		$position=intval($position);
		$list_position_y=array(540,670,810,950,1080,1220,1350,1490);
		if($name=='day'){			$chip_png='chip_07';$position_x=15; $position_y=$list_position_y[$position-1];}
		elseif($name==='affair'){	$chip_png='chip_08';$position_x=160;$position_y=$list_position_y[$position-1];}
		elseif($name==='loop'){		$chip_png='chip_09';$position_x=310;$position_y=$list_position_y[7-$position];}
		elseif($name==='extra'){	$chip_png='chip_10';$position_x=460;$position_y=$list_position_y[$position];}
		$chip=new Chip($chip_png,$position_x,$position_y);
		return $chip;
	}
	
	public static function make($chip_png,$position_x,$position_y){
		$chip=new Chip($chip_png,$position_x,$position_y);
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
	$text_y=(BOARDHEIGHT*2+CARDHEIGHT/2)*ZOOM;
	$text_x=50*ZOOM;
	$font_size= (80*ZOOM)."px メイリオ";
	$text="惨劇コモンズΧ(http://bakafire.main.jp/rooper/sr_dl_04_sozai.htm):“BakaFire”“紺ノ玲”";
$return  = <<< EOF

	$(document).ready(function(){
		setBoard();
	});
	function setBoard() {
		//描画コンテキストの取得
		var text='$text';
		var canvas = document.getElementById('canvas');
		if ( ! canvas || ! canvas.getContext ) { return false; }
		var context = canvas.getContext('2d');
		var canvas_images=[$canvas_images];
		var canvas_rotate=[$canvas_rotate];
		context.fillStyle = "red";
		context.font ="$font_size";
		context.strokeText(text, $text_x, $text_y);
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
			$('#progress').val(100*i/length);
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
function make_query($t){
	// ファイルの内容を配列に取り込みます。
	$lines = file(FILE);
	// 配列から文字列を取り出す
	$line=$lines[$t];
	$keywords = preg_split("/&/",$line);
	var_dump($keywords);
	foreach($keywords as $key => $value){
		$q=preg_split("/=/",$value);
		if($q[0]=='id' || $q[0]=='key'){continue;}
		$_GET[$q[0]]=$q[1];
	}
}
function save_query($query,$id=null,$key=null){

$link = mysql_connect('localhost', 'php', 'bakafire');
if (!$link) {
    die('接続失敗です。'.mysql_error());
}
//print('<p>接続に成功しました。</p>');
$db_selected = mysql_select_db('roop', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}
//print('<p>データベースを選択しました。</p>');
mysql_set_charset('utf8');
/*
$result = mysql_query('SELECT * FROM log ');
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}

while ($row = mysql_fetch_assoc($result)) {
    print('<p>');
    foreach($row as $value)print(','.$value);
 //   print(',number='.$row['key']);
    print('</p>');
}
*/

//print('<p>データを追加します。</p>');
//$q=mysql_escape_string ($query);
$id='i';
$key='k';
$sql = "INSERT INTO `roop`.`log` (`query`, `number`, `id`, `key`, `time`) VALUES ('".$query."', NULL, '".$id."', '".$key."', CURRENT_TIMESTAMP);";
//$sql=mysql_escape_string ($sql);
$result_flag = mysql_query($sql);

if (!$result_flag) {
    die('INSERTクエリーが失敗しました。'.mysql_error());
}
//print('<p>追加後のデータを取得します。</p>');

$result = mysql_query('SELECT * FROM log');
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}
/*
while ($row = mysql_fetch_assoc($result)) {
    print('<p>');
    print('id='.$row['id']);
    print(',number='.$row['key']);
    print('</p>');
}
*/
$close_flag = mysql_close($link);

if ($close_flag){
  //  print('<p>切断に成功しました。</p>');
}




/*
	$file = FILE;
	$fp = fopen( $file, "r+" );
	flock($fp,LOCK_EX);
	$buffer = array();
	while (!feof($fp)) {
		$buffer[] = fgets($fp);
	}
	rewind($fp);
	$tmp=count($buffer);
	$tmp= $tmp<5 ? $tmp : 5;
	for($i=0;$i<$tmp; $i++){fputs( $fp,$buffer[$i]);}
	fputs( $fp,$query."\n");
	fclose( $fp ); //ロックは自動的に解除
	*/
}
