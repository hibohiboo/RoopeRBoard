<?PHP
	$data=Board::make_data();
	//データボードのチップ

	Chip::make_data('day',$_GET['d']);
	Chip::make_data('loop',$_GET['l']);
	Chip::make_data('extra',$_GET['e']);
	//var_dump($_GET['e']);
	for($i=1;$i<9;$i++){
		Chip::make_data('affair',$_GET['j'.$i]);
	}

	//４箇所のボード
	$boards=Board::make_boards();
//	var_dump($data);
	
	foreach($boards as $i => $value){
		$value->set_anyaku($_GET['b'.$i]);
		if(isset($_GET['rumor'])==true && $i==$_GET['rumor'])$value->set_rumor()->end_g();
		foreach(array('w','r','i') as $w){
			if($_GET['r'.$w]==(CHARNUM+$i)){
				$value->set_hand('writer',$_GET[$w])->end_g();
			}
		}
		foreach(array('A','B','C') as $w){
			if($_GET['r'.$w]==($i+CHARNUM)){
				$value->set_hand('hero'.$w,$_GET[$w])->end_g();
			}
		}
	}
	
	for($i=0;$i<CHARNUM;$i++){
		if(isset($_GET['u'.$i])===false){continue;}
		if(isset($_GET['d'.$i])===true){
			$char=$boards[$_GET['p'.$i]]->set_character($i,'die');
		}else{
			$char=$boards[$_GET['p'.$i]]->set_character($i);
		}
		if(isset($_GET['k'])===true && $_GET['k'] == $i){
			$char->set_kasi();
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
	
	$svg= HTML::wrap_tag('svg',$svg_inner,array('xmlns'=>"http://www.w3.org/2000/svg",'version'=>"1.1",'width'=>$CANVASWIDTH,'height'=>$CANVASHEIGHT));
	$body.=$svg;
	
