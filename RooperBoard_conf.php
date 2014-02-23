<?PHP
	//$getkey=array('m','l','d','e','b0','b2','b1','b3','w','rw','r','rr','i','ri','A','rA','B','rB','C','rC','p0','y0','h0','a0','p1','y1','h1','a1','p2','y2','h2','a2','p3','y3','h3','a3','p4','y4','h4','a4','p5','y5','h5','a5','p6','y6','h6','a6','p7','y7','h7','a7','p8','y8','h8','a8','p9','y9','h9','a9','p10','y10','h10','a10','p11','y11','h11','a11','p12','y12','h12','a12','p13','y13','h13','a13','p14','y14','h14','a14','p15','y15','h15','a15','p16','y16','h16','a16','p17','y17','h17','a17','p18','y18','h18','a18','p19','y19','h19','a19','p20','y20','h20','a20','p21','y21','h21','a21','a22','a23','a24','a25','rumor');
	$getkey=array('m','l','d','e','j1','j2','j3','j4','j5','j6','j7','j8','b0','b2','b1','b3','w','rw','r','rr','i','ri','A','rA','B','rB','C','rC','u0','d0','p0','y0','h0','a0','u1','d1','p1','y1','h1','a1','u2','d2','p2','y2','h2','a2','u3','d3','p3','y3','h3','a3','u4','p4','y4','h4','a4','u5','d5','p5','y5','h5','a5','u6','d6','p6','y6','h6','a6','u7','d7','p7','y7','h7','a7','u8','d8','p8','y8','h8','a8','u9','d9','p9','y9','h9','a9','u10','d10','p10','y10','h10','a10','u11','d11','p11','y11','h11','a11','u12','d12','p12','y12','h12','a12','u13','d13','p13','y13','h13','a13','u14','d14','p14','y14','h14','a14','u15','d15','p15','y15','h15','a15','u16','d16','p16','y16','h16','a16','u17','d17','p17','y17','h17','a17','u18','d18','p18','y18','h18','a18','u19','d19','p19','y19','h19','a19','u20','d20','p20','y20','h20','a20','u21','d21','p21','y21','h21','a21','a22','a23','a24','a25','rumor','o','mo','k','key','id','t','save');


#クッキーの読み出し
if(isset($_COOKIE["key"])===true){$cookiekey=$_COOKIE["key"];}else{$cookiekey=null;}
#クッキーの書き出し
if(isset($_GET['key'])===true){	$cookiekey=$_GET['key'];setcookie('key',$cookiekey);}
#クッキーの読み出し
if(isset($_COOKIE["id"])===true){$id=$_COOKIE["id"];}else{$id=null;}
#クッキーの書き出し
if(isset($_GET['id'])===true){$id=$_GET['id'];setcookie('id',$id);}
	define('FILE','log/log.txt');
	if(isset($_GET['save'])===true){
		save_query($_SERVER['QUERY_STRING']);
	}
	if(isset($_GET['t'])===true){
		make_query($_GET['t']);
	}
	foreach($getkey as $key){
		$_GET[$key]= isset($_GET[$key]) ? htmlspecialchars($_GET[$key]) : null;
	}
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
	define('CHIP1X',190);
	define('CHIP1Y',45);
	define('CHIP2X',285);
	define('CHIP2Y',100);
	define('CHIP3X',85);
	define('CHIP3Y',125);
	
	
	$char_card_list=array('chara_cards_01_00.png','chara_cards_02_00.png','chara_cards_03_00.png','chara_cards_04_00.png','chara_cards_05_00.png','chara_cards_06_00.png','chara_cards_07_00.png','chara_cards_08_00.png','chara_cards_09_00.png','chara_cards_10_00.png','chara_cards_11_00.png','chara_cards_12_00.png','chara_cards_13_00.png','chara_cards_14_00.png','chara_cards_15_00.png','chara_cards_16_00.png','chara_cards_17_00.png','chara_cards_18_00.png','chara_cards_19_00.png','chara_cards_20_00.png','ex_card_b.png','ex_card_c.png');
	$char_list=array("男子学生","女子学生","お嬢様","巫女","刑事","サラリーマン","情報屋","医者","患者","委員長","イレギュラー","異世界人","神格","アイドル","マスコミ","大物","ナース","手先","学者","幻想","魔獣","魔獣");
	define('CHARNUM',count($char_list));
	$boardsName=array('hospital','city','shrine','school');
	define('BOARDNUM',count($boardsName));
	
	$CANVASWIDTH=(DATAWIDTH+BOARDWIDTH*2)*ZOOM;
	$CANVASHEIGHT=(BOARDHEIGHT*2+CARDWIDTH)*ZOOM;
	$javascript=HTML::header_link(
				'http://code.jquery.com/jquery-latest.js',
				'RooperBoard.js'
				);
/*
	$css='<style>table{border:solid 1px #000000;border-collapse:collapse;}th,td{border-top:solid 1px #000000;'
		.'border-bottom:solid 1px #000000;}'
		.'input[type="number"]{ime-mode:disabled;}</style>';
*/
	$css= '<link rel="stylesheet" href="RooperBoard.css">';
	$title='惨劇RoopeRボード';
	$body="";
	$svg_inner='';
	$canvas_images='';
	$canvas_rotate='';
	$canvas_rotate_x=0;
	$canvas_rotate_y=0;
	$flg_canvas_rotate=0;
	$head='';


