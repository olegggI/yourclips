<?
include '/bd/bd.php';
$bufone = file_get_contents('http://www.clipafon.ru');
preg_match_all('|<div id="alphabet">(.*?)</div>|sei', $bufone, $matchess);
preg_match_all('|<a[^>]+href="([^ >]+)[^>]*">(.*?)</a>|sei', $matchess[1][0], $matchess);

$arraychek = array();
$arraychek[] = "http://www.clipafon.ru/letter-y.html";
$arraychek[] = "http://www.clipafon.ru/letter-z.html";
foreach ($arraychek as $url) {
	$buf = file_get_contents($url);
	preg_match_all('|<div id="browse_main">(.*?)</div>|sei', $buf, $matchess);
	preg_match_all('|<a[^>]+href="([^ >]+)[^>]*">(.*?)</a>|sei', $matchess[1][0], $matchess);

	foreach ($matchess[2] as $key => $names) {

		$names = explode(", ", $names);
		$names = trim($names[1] . ' ' . $names[0]);
		if (preg_match("/[а-яА-Я]+/i", $names))
			$type = 1;
		else
			$type = 0; 

		$artist_url = $matchess[1][$key];
		$artist_name = delAppostroff($names);
		$artist_code = getCpu($names);
		
		$artist_type = $type;

		$qwery = "INSERT INTO `artist` ( `id`, `name`, `code`, `type`) VALUES ( NULL ,  '$artist_name',  '$artist_code',  '$artist_type' );";

		if (!mysql_query($qwery))
			die(print_r($qwery . ' - bad request! ' . mysql_error()));

		$id_artist = mysql_insert_id();


		$bufone = file_get_contents($artist_url);
		preg_match_all('|<span class="artist_name">(.*?)</span>|sei', $bufone, $clip_names);
		preg_match_all('|<img src="(.*?)"|sei', $bufone, $img_url);

		unset($img_url[1][0]);
		unset($img_url[1][1]);
		array_pop($img_url[1]);

		$img_array = array();

		$i = 0;
		$artist_video = array();
		$clips_array = $clip_names[1];

		foreach ($img_url[1] as $val) {
			if (ereg('i.ytimg.com/vi/', $val)) {
				preg_match_all('|/vi/(.*?)/|sei', $val, $aaa);
				$artist_video[] = array('name' => trim($clips_array[$i]), 'clip' => $aaa[1][0], 'code' => getCpu(trim($clips_array[$i])));
			} elseif (ereg('img.youtube.com', $val)) {
				preg_match_all('|/vi/(.*?)/|sei', $val, $aaa);
				$artist_video[] = array('name' => trim($clips_array[$i]), 'clip' => $aaa[1][0], 'code' => getCpu(trim($clips_array[$i])));
			}
			$i++;
		}

		foreach ($artist_video as $value) {
			$qwerys = "INSERT INTO `clip` ( `id` , `name` , `code` , `youtube_id` , `artist_id`) VALUES (NULL ,  '" . delAppostroff($value['name']) . "',  '" . $value['code'] . "',  '" . $value['clip'] . "',  '" . $id_artist . "');";

			if (!mysql_query($qwerys))
				die(print_r($qwerys . ' - bad request! ' . mysql_error()));
		}
	} 
}

function getCpu($title) {
	$trans = array("а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo", "ж" => "j", "з" => "z", "и" => "i", "і" => "i", "і" => "i", "й" => "i", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "y", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sh", "ы" => "i", "э" => "e", "ю" => "u", "я" => "ya",
		"А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ё" => "Yo", "Ж" => "J", "З" => "Z", "И" => "I",  "І" => "I", "Й" => "I", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "У" => "Y", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "Ch", "Ш" => "Sh", "Щ" => "Sh", "Ы" => "I", "Э" => "E", "Ю" => "U", "Я" => "Ya", "є" => "ye", "Є" => "Ye",
		"ь" => "", "Ь" => "", "ъ" => "", "Ъ" => "");
	$text = strtr($title, $trans);
	$cpu = str_replace(' ', '-', $text);
	$cpu = str_replace('.', '-', $cpu);
	$cpu = str_replace(',', '', $cpu);
	$cpu = str_replace(')', '', $cpu);
	$cpu = str_replace('(', '', $cpu);
	$cpu = str_replace("'", "", $cpu);
	$cpu = str_replace('%', '', $cpu);
	$cpu = str_replace('?', '', $cpu);
	$cpu = str_replace('!', '', $cpu);
	$cpu = str_replace(',', '', $cpu);
	$cpu = str_replace('--', '-', $cpu);
	$cpu = str_replace('-&', '', $cpu);
	return $cpu;
}

function delAppostroff($title) {
	$title = str_replace("'", "", $title);
	return iconv('UTF-8','CP1251',$title);
}

?>