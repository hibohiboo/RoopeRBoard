<html>
<head>
<title>PHP TEST</title>
</head>

<body>

<?php

$link = mysql_connect('localhost', 'php', 'bakafire');
if (!$link) {
    die('�ڑ����s�ł��B'.mysql_error());
}

print('<p>�ڑ��ɐ������܂����B</p>');

$db_selected = mysql_select_db('roop', $link);
if (!$db_selected){
    die('�f�[�^�x�[�X�I�����s�ł��B'.mysql_error());
}

print('<p>�f�[�^�x�[�X��I�����܂����B</p>');

mysql_set_charset('utf8');

$result = mysql_query('SELECT * FROM log ');
if (!$result) {
    die('SELECT�N�G���[�����s���܂����B'.mysql_error());
}

while ($row = mysql_fetch_assoc($result)) {
    print('<p>');
    foreach($row as $value)print(','.$value);
 //   print(',number='.$row['key']);
    print('</p>');
}

print('<p>�f�[�^��ǉ����܂��B</p>');
$q='qury';
$id='i';
$key='k';
$sql = "INSERT INTO `roop`.`log` (`query`, `number`, `id`, `key`, `time`) VALUES ('".$q."', NULL, '".$id."', '".$key."', CURRENT_TIMESTAMP);";
$result_flag = mysql_query($sql);

if (!$result_flag) {
    die('INSERT�N�G���[�����s���܂����B'.mysql_error());
}

print('<p>�ǉ���̃f�[�^���擾���܂��B</p>');

$result = mysql_query('SELECT * FROM log');
if (!$result) {
    die('SELECT�N�G���[�����s���܂����B'.mysql_error());
}

while ($row = mysql_fetch_assoc($result)) {
    print('<p>');
    print('id='.$row['id']);
    print(',number='.$row['key']);
    print('</p>');
}

$close_flag = mysql_close($link);

if ($close_flag){
    print('<p>�ؒf�ɐ������܂����B</p>');
}

?>
</body>
</html>