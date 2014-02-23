<?PHP
//	$body.='<form action="RooperBoard.php" method="get">';
	$body.='<form action="b.php" method="get">';
	$body.='<input type="hidden" name="save" value="1">';
	$body.='<label><b>ID:</b><input name="id" size="3" style="ime-mode:disable;" type="text" value="'.$id.'"></label>';
	$body.='<label><b>鍵:</b><input name="key" size="3" pattern="^[0-9a-zA-Z]+$" type="text" value="'.$cookiekey.'"></label>';
	$body.='<label><b>拡大率:</b><input name="m" size="3" pattern="^[0-9]+$" type="number" value="'.$_GET["m"].'" max="500" required></label>';
	$body.='<table><tr>';
	$body.='<th>残りループ回数:</th><td><input name="l" size="3" pattern="^[0-7]$" type="number" value="'.$_GET["l"].'"></td>';
	$body.='<th>現在日数:</th><td><input name="d" size="3" pattern="^[1-8]$" type="number" value="'.$_GET["d"].'"></td>';
	$body.='<th>エクストラゲージ:</th><td><input name="e" size="3" pattern="^[0-7]$" type="number" value="'.$_GET["e"].'"></td>';
	$body.='</tr></table>';
	$body.='<div style="float:left">';
	$body.='<table><caption>事件予定</caption>';
	for($i=1;$i<=8;$i++){
		$body.='<tr><th>'.$i.'日目:</th><td>'.'<input type="checkbox" name="j'.$i.'" value="'.$i.'"';
		if(isset($_GET['j'.$i])===true){$body.=' checked';}
		$body.='></td></tr>';
	}
	$body.='</table></div>';
	$body.='<div>';
	$hand_list_writer=array('裏'=>'0b','不安+1'=>'01','不安+1'=>'02','不安-1'=>'03','不安禁止'=>'04','友好禁止'=>'05','暗躍+1'=>'06','暗躍+2'=>'07','移動上下'=>'08','移動左右'=>'09','移動斜め'=>'10');
	$hand_list_hero=array('裏'=>'0b','不安+1'=>'01','不安-1'=>'02','友好+1'=>'03','友好+2'=>'04','暗躍禁止'=>'05','移動上下'=>'06','移動左右'=>'07','移動禁止'=>'08');
	$board=array('病院','都市','神社','学校');

	/*
	$body.='<table>';
	foreach($board as $i => $value){
		$tmp[]='<th>'.$value.'暗躍:</th><td><input size="3" pattern="^[0-5]$" type="number" name="b'.$i.'">'.'</td>';
	}
	$body.='<tr>'.$tmp[0].$tmp[2].'</tr><tr>'.$tmp[1].$tmp[3].'</tr>';
	$body.='</table>';
	*/
	$hand_select_list=array();

	foreach(array('w','r','i') as $i){
		$writer_hand_select="<select name='".$i."'>";
		foreach($hand_list_writer as $key => $value){
			$writer_hand_select.="<option value='".$value."'";
			if($_GET[$i]==$value){$writer_hand_select.=' selected';}
			$writer_hand_select.=">".$key.'</option>';
		}
		$writer_hand_select.="</select>";
		$hand_select_list[]=$writer_hand_select;
	}
	foreach(array('A','B','C') as $i){
		$writer_hand_select="<select name='".$i."'>";
		foreach($hand_list_hero as $key => $value){
			$writer_hand_select.="<option value='".$value."'";
			if($_GET[$i]==$value){$writer_hand_select.=' selected';}
			$writer_hand_select.=">".$key.'</option>';
		}
		$writer_hand_select.="</select>";
		$hand_select_list[]=$writer_hand_select;
	}

	$char_list=array("男子学生","女子学生","お嬢様","巫女","刑事","サラリーマン","情報屋","医者","患者","委員長","イレギュラー","異世界人","神格","アイドル","マスコミ","大物","ナース","手先","学者","幻想",'魔獣','魔獣');
	$char_def_pos_list=array('学校','学校','学校','神社','都市','都市','都市','病院','病院','学校','学校','神社','神社','都市','都市','都市','病院','病院','病院','神社','病院','神社');

	$body.='<table><tr><th>MysteryCircle</th><td><input id="mystery" type="checkbox" name="tragedy1" value="v"';
	if(isset($_GET['tragedy1'])===true){$body.=' checked';}
	$body.='></td></tr>';
	$body.='<tr><th>HautedStage</th><td><input id="haunted" type="checkbox" name="tragedy2" value="h"';
	if(isset($_GET['tragedy2'])===true){$body.=' checked';}
	$body.='></td></tr></table>';

	$body.='<table><tr><th>W1:</th><th>脚本家カード:</th><td>'.$hand_select_list[0].'</td><td><input type="radio" name="rw" value="-1"';
	if(isset($_GET['rw'])===false || $_GET['rw']=='-1'){$body.=' checked="true"';}
	$body.='>'.'</td></tr>';
	$body.='<tr><th>W2:</th><th>脚本家カード:</th><td>'.$hand_select_list[1].'</td><td><input type="radio" name="rr" value="-1"';// checked="true">'.'</td></tr>'
	if(isset($_GET['rr'])===false || $_GET['rr']=='-1'){$body.=' checked="true"';}
	$body.='>'.'</td></tr>';
	$body.='<tr><th>W3:</th><th>脚本家カード:</th><td>'.$hand_select_list[2].'</td><td><input type="radio" name="ri" value="-1"';// checked="true">'.'</td></tr>'
	if(isset($_GET['ri'])===false || $_GET['ri']=='-1'){$body.=' checked="true"';}
	$body.='>'.'</td></tr>';
	$body.='<tr><th>A:</th><th>主人公カード:</th><td>'.$hand_select_list[3].'</td><td><input type="radio" name="rA" value="-1"';// checked="true">'.'</td></tr>'
	if(isset($_GET['rA'])===false || $_GET['rA']=='-1'){$body.=' checked="true"';}
	$body.='>'.'</td></tr>';
	$body.='<tr><th>B:</th><th>主人公カード:</th><td>'.$hand_select_list[4].'</td><td><input type="radio" name="rB" value="-1"';//checked="true">'.'</td></tr>'
	if(isset($_GET['rB'])===false || $_GET['rC']=='-1'){$body.=' checked="true"';}
	$body.='>'.'</td></tr>';
	$body.='<tr><th>C:</th><th>主人公カード:</th><td>'.$hand_select_list[5].'</td><td><input type="radio" name="rC" value="-1"';// checked="true">'.'</td></tr>'
	if(isset($_GET['rC'])===false || $_GET['rC']=='-1'){$body.=' checked="true"';}
	$body.='>'.'</td></tr>';
	$body.='</table>';
	$body.='</div>';

	$body.='<hr style="clear:both">';
	$body.='<table>';
	$body.='<colgroup span="1">';
	$body.='<colgroup span="1">';
	$body.='<thead><tr><th>使用</th><th>死亡</th><th class="kasi"><input type="radio" name="k" value="-1">仮死</th><th>キャラクター名</th><th>ボード</th><th>友好</th><th>不安</th><th>暗躍</th><th>W1</th><th>W2</th><th>W3</th><th>A</th><th>B</th><th>C</th></tr></thead>';
//	$body.='<tr><th>使用</th><th>死亡</th><th>キャラクター名</th><th>ボード</th><th>友好</th><th>不安</th><th>暗躍</th><th>脚本家カード'.'<input type="radio" name="rw" value="" checked>'.'</th><th>脚本家カード'.'<input type="radio" name="rr" value="" checked>'.'</th><th>脚本家カード'.'<input type="radio" name="ri" value="" checked>'.'</th><th>主人公カード'.'<input type="radio" name="rA" value="" checked>'.'</th><th>主人公カード'.'<input type="radio" name="rB" value="" checked>'.'</th><th>主人公カード'.'<input type="radio" name="rC" value="" checked>'.'</th></tr>';
//	$body.='<tr><td>'.'</td>'.'<td>'.'</td>'.'<td>'.'</td>'.'<td>'.'</td>'.'<td>'.'</td>'.'<td>'.'</td>'.'<td>'.'</td>'.'<td>'.$hand_select_list[0].'</td>'.'<td>'.$hand_select_list[1].'</td>'.'<td>'.$hand_select_list[2].'</td>'.'<td>'.$hand_select_list[3].'</td>'.'<td>'.$hand_select_list[4].'</td>'.'<td>'.$hand_select_list[5].'</td>'.'</tr>';
//	for($i=0;$i<20;$i++){
	
	foreach($char_list as $i => $outvalue){
		$board_select="<select disabled='true' id='p".$i."' name='p".$i."'>";
		foreach($board as $key => $value){
			$board_select.="<option value='".$key."' ";
			if(isset($_GET['p'.$i])===false && $char_def_pos_list[$i]==$value){$board_select.='selected';}
			elseif($_GET['p'.$i]==$key){$board_select.='selected';}
			$board_select.=">".$value.'</option>';
		}
		$board_select.='</select>';
		$body.='<tr id="ctr'.$i.'">';
		
		$body.='<td>'.'<input type="checkbox" id="u'.$i.'" name="u'.$i.'" value="1"';
		if(isset($_GET['u'.$i])===true){$body.=' checked';}
		$body.='>'."</td>";
		$body.='<td>'.'<input disabled="true" id="d'.$i.'" type="checkbox" name="d'.$i.'" value="g"';
		if(isset($_GET['d'.$i])===true){$body.=' checked';}
		$body.='>'."</td>";
		$body.='<td class="kasi">'.'<input disabled="true" id="k'.$i.'" type="radio" name="k" value="'.$i.'"';
		if($_GET['k']==$i){$body.=' checked';}
		$body.='>'."</td>";
		$body.='<td>'.$char_list[$i].'</td>';
		$body.='<td>'.$board_select.'</td>';
		$body.='<td>'.'<input disabled="true" id="y'.$i.'" size="3" type="number" pattern="^[0-1]?[0-9]?$" name="y'.$i.'" value="'.$_GET['y'.$i].'">'.'</td>';
		$body.='<td>'.'<input disabled="true" id="h'.$i.'" size="3" type="number" pattern="^[0-1]?[0-9]?$" name="h'.$i.'" value="'.$_GET['h'.$i].'">'.'</td>';
		$body.='<td>'.'<input disabled="true" id="a'.$i.'" size="3" type="number" pattern="^[0-1]?[0-9]?$" name="a'.$i.'" value="'.$_GET['a'.$i].'">'.'</td>';
		if($i!=19){
			$body.='<td>'.'<input disabled="true" type="radio" name="rw" id="rw'.$i.'" value="'.$i.'"';
			if($_GET['rw']==$i){$body.=' checked="true"';}
			$body.='>'.'</td>';
			$body.='<td>'.'<input disabled="true" type="radio" name="rr" id="rr'.$i.'" value="'.$i.'"';//>'.'</td>';
			if($_GET['rr']==$i){$body.=' checked="true"';}
			$body.='>'.'</td>';
			$body.='<td>'.'<input disabled="true" type="radio" name="ri" id="ri'.$i.'" value="'.$i.'"';//>'.'</td>';
			if($_GET['ri']==$i){$body.=' checked="true"';}
			$body.='>'.'</td>';
			$body.='<td>'.'<input disabled="true" type="radio" name="rA" id="rA'.$i.'" value="'.$i.'"';//>'.'</td>';
			if($_GET['rA']==$i){$body.=' checked="true"';}
			$body.='>'.'</td>';
			$body.='<td>'.'<input disabled="true" type="radio" name="rB" id="rB'.$i.'" value="'.$i.'"';//>'.'</td>';
			if($_GET['rB']==$i){$body.=' checked="true"';}
			$body.='>'.'</td>';
			$body.='<td>'.'<input disabled="true" type="radio" name="rC" id="rC'.$i.'" value="'.$i.'"';//>'.'</td>';
			if($_GET['rC']==$i){$body.=' checked="true"';}
			$body.='>'.'</td>';
		}
		$body.='</tr>';
	}
	$charnum=count($char_list);
	foreach($board as $i =>$value){
		$thisnumber=$charnum+$i;
		$body.='<tr>';
		
		$body.='<td>'."</td>";
		$body.='<td>'."</td>";
		$body.='<td class="kasi">'."</td>";
		$body.='<td>'.$value.'</td>';
		$body.='<td>'.'</td>';
		$body.='<td>'.'</td>';
		$body.='<td>'.'</td>';
		$body.='<td>'.'<input size="3" type="number"  pattern="^[0-5]$" type="number" name="b'.$i.'" value="'.$_GET['b'.$i].'">'.'</td>';
			$body.='<td>'.'<input type="radio" name="rw" value="'.$thisnumber.'"';//>'.'</td>';
			if($_GET['rw']==$thisnumber){$body.=' checked="true"';}
			$body.='>'.'</td>';
			$body.='<td>'.'<input type="radio" name="rr" value="'.$thisnumber.'"';//>'.'</td>';
			if($_GET['rr']==$thisnumber){$body.=' checked="true"';}
			$body.='>'.'</td>';
			$body.='<td>'.'<input type="radio" name="ri" value="'.$thisnumber.'"';//>'.'</td>';
			if($_GET['ri']==$thisnumber){$body.=' checked="true"';}
			$body.='>'.'</td>';
			$body.='<td>'.'<input type="radio" name="rA" value="'.$thisnumber.'"';//>'.'</td>';
			if($_GET['rA']==$thisnumber){$body.=' checked="true"';}
			$body.='>'.'</td>';
			$body.='<td>'.'<input type="radio" name="rB" value="'.$thisnumber.'"';//>'.'</td>';
			if($_GET['rB']==$thisnumber){$body.=' checked="true"';}
			$body.='>'.'</td>';
			$body.='<td>'.'<input type="radio" name="rC" value="'.($charnum+$i).'"';//>'.'</td>';
			if($_GET['rC']==$thisnumber){$body.=' checked="true"';}
			$body.='>'.'</td>';
		$body.='</tr>';
	}
	
	$body.='</table>';
	$body.='<p>';
	$body.='噂話：';
	$board_select="<select name='rumor'>";
	$board_select.="<option value='-1'>-</option>";
	foreach($board as $key => $value){
		$board_select.="<option value='".$key."' ";
		if(isset($_GET['rumor'])===true && $_GET['rumor']==$key){$board_select.='selected';}
		$board_select.=">".$value.'</option>';
	}
	$board_select.='</select>';
	$body.=$board_select;
	$body.='<label name="0"> png画像を作成しない <input type="checkbox" name="o" value="1"';
	if(isset($_GET['o'])){$body.='checked';}
	$body.='></label></p>';
	$body.='<input type="submit" formmethod="get" value="ボード作成">';
	$body.="</form> ";
