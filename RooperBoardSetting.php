<?PHP
$javascript="<script src='http://code.jquery.com/jquery-latest.js'></script>";
$css='<style>table{border:solid 1px #000000;border-collapse:collapse;}th,td{border-top:solid 1px #000000;'
		.'border-bottom:solid 1px #000000;}'
		
		.'input[type="number"]{ime-mode:disabled;}</style>';
$head='';$title='ボード入力';$body='';

//	$body.='<form action="RooperBoard.php" method="get">';
$body.='<form action="b.php" method="get">';
	$body.='残りループ回数:<input name="l" size="3" pattern="^[0-7]$" type="number">';
	$body.='　　現在日数:<input name="d" size="3" pattern="^[1-8]$" type="number">';
	$body.='　　エクストラゲージ:<input name="e" size="3" pattern="^[0-7]$" type="number">';
	$body.='　　拡大率:<input name="m" size="3" pattern="^[0-9]+$" type="number" value="30" max="500" required>';
	$body.='<div style="float:left">';
	$body.='<table><caption>事件予定</caption>';
	for($i=1;$i<=8;$i++){
		$body.='<tr><th>'.$i.'日目:</th><td>'.'<input type="checkbox" name="j'.$i.'" value="'.$i.'"></td></tr>';
	}
	$body.='</table></div>';
	$body.='<div>';
	$hand_list_writer=array('裏'=>'0b','不安+1'=>'01','不安+1'=>'02','不安-1'=>'03','不安禁止'=>'04','友好禁止'=>'05','暗躍+1'=>'06','暗躍+2'=>'07','移動上下'=>'08','移動左右'=>'09','移動斜め'=>'10');
	$hand_list_hero=array('裏'=>'0b','不安+1'=>'01','不安-1'=>'02','友好+1'=>'03','友好+2'=>'04','暗躍禁止'=>'05','移動上下'=>'06','移動左右'=>'07','移動禁止'=>'08');
	$board=array('病院','都市','神社','学校');

	
	$body.='<table>';
	//for($i=0;$i<4;$i++){
	foreach($board as $i => $value){
//		$body.='<tr><th>'.$board[$i].'暗躍:</th><td><input size="3" pattern="^[0-5]$" type="number" name="b'.$i.'">'.'</td></tr>';
		$body.='<tr><th>'.$value.'暗躍:</th><td><input size="3" pattern="^[0-5]$" type="number" name="b'.$i.'">'.'</td></tr>';
	}
	$body.='</table>';
	$hand_select_list=array();

	foreach(array('w','r','i') as $i){
		$writer_hand_select="<select name='".$i."'>";
		foreach($hand_list_writer as $key => $value){
			$writer_hand_select.="<option value='".$value."'>".$key.'</option>';
		}
		$writer_hand_select.="</select>";
		$hand_select_list[]=$writer_hand_select;
	}
	foreach(array('A','B','C') as $i){
		$writer_hand_select="<select name='".$i."'>";
		foreach($hand_list_hero as $key => $value){
			$writer_hand_select.="<option value='".$value."'>".$key.'</option>';
		}
		$writer_hand_select.="</select>";
		$hand_select_list[]=$writer_hand_select;
	}

	$char_list=array("男子学生","女子学生","お嬢様","巫女","刑事","サラリーマン","情報屋","医者","患者","委員長","イレギュラー","異世界人","神格","アイドル","マスコミ","大物","ナース","手先","学者","幻想",'魔獣','魔獣');
	$char_def_pos_list=array('学校','学校','学校','神社','都市','都市','都市','病院','病院','学校','学校','神社','神社','都市','都市','都市','病院','病院','病院','神社','病院','神社');



	$body.='<table><tr><th>W1:</th><th>脚本家カード:</th><td>'.$hand_select_list[0].'</td><td><input type="radio" name="rw" value="-1" checked>'.'</td></tr>'
			.'<tr><th>W2:</th><th>脚本家カード:</th><td>'.$hand_select_list[1].'</td><td><input type="radio" name="rr" value="-1" checked>'.'</td></tr>'
			.'<tr><th>W3:</th><th>脚本家カード:</th><td>'.$hand_select_list[2].'</td><td><input type="radio" name="ri" value="-1" checked>'.'</td></tr>'
			.'<tr><th>A:</th><th>主人公カード:</th><td>'.$hand_select_list[3].'</td><td><input type="radio" name="rA" value="-1" checked>'.'</td></tr>'
			.'<tr><th>B:</th><th>主人公カード:</th><td>'.$hand_select_list[4].'</td><td><input type="radio" name="rB" value="-1" checked>'.'</td></tr>'
			.'<tr><th>C:</th><th>主人公カード:</th><td>'.$hand_select_list[5].'</td><td><input type="radio" name="rC" value="-1" checked>'.'</td></tr>'
			.'</table>';
	$body.='</div>';

	$body.='<hr style="clear:both">';
	$body.='<table>';
	$body.='<tr><th>使用</th><th>死亡</th><th>キャラクター名</th><th>ボード</th><th>友好</th><th>不安</th><th>暗躍</th><th>W1</th><th>W2</th><th>W3</th><th>A</th><th>B</th><th>C</th></tr>';
//	$body.='<tr><th>使用</th><th>死亡</th><th>キャラクター名</th><th>ボード</th><th>友好</th><th>不安</th><th>暗躍</th><th>脚本家カード'.'<input type="radio" name="rw" value="" checked>'.'</th><th>脚本家カード'.'<input type="radio" name="rr" value="" checked>'.'</th><th>脚本家カード'.'<input type="radio" name="ri" value="" checked>'.'</th><th>主人公カード'.'<input type="radio" name="rA" value="" checked>'.'</th><th>主人公カード'.'<input type="radio" name="rB" value="" checked>'.'</th><th>主人公カード'.'<input type="radio" name="rC" value="" checked>'.'</th></tr>';
//	$body.='<tr><td>'.'</td>'.'<td>'.'</td>'.'<td>'.'</td>'.'<td>'.'</td>'.'<td>'.'</td>'.'<td>'.'</td>'.'<td>'.'</td>'.'<td>'.$hand_select_list[0].'</td>'.'<td>'.$hand_select_list[1].'</td>'.'<td>'.$hand_select_list[2].'</td>'.'<td>'.$hand_select_list[3].'</td>'.'<td>'.$hand_select_list[4].'</td>'.'<td>'.$hand_select_list[5].'</td>'.'</tr>';
//	for($i=0;$i<20;$i++){	
	foreach($char_list as $i => $outvalue){
		$board_select="<select name='p".$i."'>";
		foreach($board as $key => $value){
			$board_select.="<option value='".$key."' ";
			if($char_def_pos_list[$i]==$value){$board_select.='selected';}
			$board_select.=">".$value.'</option>';
		}
		$board_select.='</select>';
		$body.='<tr>';
		
		$body.='<td>'.'<input type="checkbox" name="u'.$i.'" value="1">'."</td>\n";
		$body.='<td>'.'<input type="checkbox" name="d'.$i.'" value="g">'."</td>\n";
		$body.='<td>'.$char_list[$i].'</td>';
		$body.='<td>'.$board_select.'</td>';
		$body.='<td>'.'<input size="3" type="number" pattern="^[0-9]+$" name="y'.$i.'">'.'</td>';
		$body.='<td>'.'<input size="3" type="number" pattern="^[0-9]+$" name="h'.$i.'">'.'</td>';
		$body.='<td>'.'<input size="3" type="number" pattern="^[0-9]+$" name="a'.$i.'">'.'</td>';
		if($i!==19){
			$body.='<td>'.'<input type="radio" name="rw" value="'.$i.'">'.'</td>';
			$body.='<td>'.'<input type="radio" name="rr" value="'.$i.'">'.'</td>';
			$body.='<td>'.'<input type="radio" name="ri" value="'.$i.'">'.'</td>';
			$body.='<td>'.'<input type="radio" name="rA" value="'.$i.'">'.'</td>';
			$body.='<td>'.'<input type="radio" name="rB" value="'.$i.'">'.'</td>';
			$body.='<td>'.'<input type="radio" name="rC" value="'.$i.'">'.'</td>';
		}
		$body.='</tr>';
	}
	
	
	$body.='<table>';
	$body.='<p><label name="0"> png画像を作成しない <input type="checkbox" name="o" value="1"></label></p>';
	$body.='<input type="submit" formmethod="get" value="ボード作成">';
	$body.="</form> ";
	
include "template.html";
