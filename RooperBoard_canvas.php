<?php
	if($_GET['o']==='1' || $_GET['mo']=='s'){
		$head='';
	}else{
		$script=make_javascript($canvas_images);
		$head='<script>'.$script.'</script>';
		$footer="\n<canvas";
		$footer .=" style='display:none'";
		$footer .=" id='canvas' width='".$CANVASWIDTH."' height='".$CANVASHEIGHT."'></canvas>";
		$footer.="<br><textarea style='display:none' id='text'></textarea>";
		$footer.="<button onclick='makePng();'>png画像を作成</button><br>";
		$footer.="<progress id='progress' value='0' max='100'>現在、画像をダウンロード中です。</progress><span id='imgmessage'>ただいまpng画像準備中です。</span><br>";
		$footer.="<div style='width:$CANVASWIDTH"."px;height:$CANVASHEIGHT"."px;border:solid 3px #000000;'><img width='$CANVASWIDTH' height='$CANVASHEIGHT' src='' alt='png' id='png'></div>";
	}
