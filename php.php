<html>
<head>
<title>PHP TEST</title>
</head>

<body>

<?php

$link = mysql_connect('localhost', 'php', 'bakafire');
if (!$link) {
    die('接続失敗です。'.mysql_error());
}

print('<p>接続に成功しました。</p>');

$db_selected = mysql_select_db('roop', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

print('<p>データベースを選択しました。</p>');

mysql_set_charset('utf8');

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

print('<p>データを追加します。</p>');
$q='qury';
$id='i';
$key='k';
$sql = "INSERT INTO `roop`.`log` (`query`, `number`, `id`, `key`, `time`) VALUES ('".$q."', NULL, '".$id."', '".$key."', CURRENT_TIMESTAMP);";
$result_flag = mysql_query($sql);

if (!$result_flag) {
    die('INSERTクエリーが失敗しました。'.mysql_error());
}

print('<p>追加後のデータを取得します。</p>');

$result = mysql_query('SELECT * FROM log');
if (!$result) {
    die('SELECTクエリーが失敗しました。'.mysql_error());
}

while ($row = mysql_fetch_assoc($result)) {
    print('<p>');
    print('id='.$row['id']);
    print(',number='.$row['key']);
    print('</p>');
}

$close_flag = mysql_close($link);

if ($close_flag){
    print('<p>切断に成功しました。</p>');
}

?>
</body>
</html>