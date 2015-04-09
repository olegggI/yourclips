<?php 
$db = mysql_connect ( "a90e.mysql.ukraine.com.ua", "a90e_70", "6hld3a22");	
mysql_select_db ("a90e_70", $db);
$lastid = mysql_query("SELECT * FROM  `lastid`");
$lastid = mysql_fetch_array($lastid);
$max = $lastid['lastid'];
?>
<?
function gettime ($seconds) {
$m = floor($seconds/60);
$s = $seconds - floor($seconds/60)*60;
if ($m==1) {$slov = 'минута';}
elseif ($m==2 or $m==3 or $m==4) { $slov = 'минуты';}
else {$slov = 'минут';}
if (substr($s, -1)==1) {$slovv = 'секунда';}
elseif (substr($s, -1)==2 or substr($s, -1)==3 or substr($s, -1)==4) { $slovv = 'секунды';}
else {$slovv = 'секунд';}
echo $m.' '.$slov.' '.$s.' '.$slovv;   
}
function oshibka (){
header("HTTP/1.0 404 Not Found");
echo '<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xml:lang="ru" lang="ru">
<head>
<base href="http://www.aweb.com.ua/" ></base>
	<title>Ошибка 404. Страница не найдена</title>
	 
 
</head>
<body>
 
<h1>Страница не найдена</h1>
 


</body>
</html>';
exit();
}
?>