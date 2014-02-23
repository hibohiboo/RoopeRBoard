<?PHP
require_once('RooperBoard_func.php');
require_once('RooperBoard_conf.php');



if($_GET['mo']!='s'){
	include 'RooperBoard.php';
	include 'RooperBoard_file.php';
}
if($_GET['mo']!='v'){
	include 'RooperBoardSetting.php';
}

include 'RooperBoard_canvas.php';

include 'template/header.html';
include 'template/body.html';
include 'template/footer.html';
