<?php

class Server {
	/** Кількість відео у відповіді * */

	const NUMBER_OF_PACKET = 6;
	
	private $user_id; // vk id
	private $dbc; // обьект базы

	public function __construct($db) {
		$this->dbc = new Base($db);
	}

	/** Функция получение массива аудиозаписей * */
	public function getPlayList($access_token, $user_id) {
		$this->user_id = $user_id;
		$query = (array) json_decode(file_get_contents('https://api.vk.com/method/audio.get?uid=' . $user_id . '&access_token=' . $access_token));
		$query = reset($query);

		$requests_numbers = self::NUMBER_OF_PACKET;
		if (isset($_GET['call_row_number'])) {
			die(json_encode(array('count' => round(count($query) / $requests_numbers))));
		}

		$string = '<ul class="thumbnails piska" style="display: none;">';
		for ($i = $_GET['num_of_video']; $i < $_GET['num_of_video'] + $requests_numbers && $i < count($query); $i++) {
			$query[$i] = (array) $query[$i];
			/// тут треба перевірять на код кліпа, а не на ютюб айді. відео брать вже потім - бо грузитиме сістєму
			$q = "SELECT c.id AS cid, c.youtube_id AS video, c.name AS c_name, a.name AS a_name, a.code AS a_code, c.code AS c_code
						FROM clip c
						LEFT JOIN artist a ON a.id = c.artist_id
						WHERE c.code = '" . $this->getCpu($query[$i]['title']) . "'";

			$clip = $this->dbc->getRow($q);

			if (!empty($clip)) {
				$string .= $this->returnOurVideo($clip);
			} else {
				$vidos = (string) $this->getVideos($query[$i]["artist"], $query[$i]["title"]);
				if ($vidos != 'Array' && !empty($vidos)) { // если нашли клип
					$string .= $this->addAndReturnClipBody($query[$i]["artist"], $query[$i]["title"], $vidos);
				} else { // если не нашли
					$string .= $this->returnClipError($query[$i]["artist"], $query[$i]["title"]);
				}
			}
		}
		$string .= '</ul>';
		die(json_encode(array("packeds" => $string)));
	}

	/** Функция получение массива аудиозаписей * */
	public function getVideos($artist, $title) {

		$artist = $this->getRequestForYoutube($artist);
		$title = $this->getRequestForYoutube($title);

		$homepage = file_get_contents("http://gdata.youtube.com/feeds/api/videos?q=$artist+$title");
		$xml = (array) new SimpleXMLElement($homepage);

		$entry = array();
		foreach ((array) $xml['entry'] as $value) {
			$value = (array) $value;
			return substr($value['id'], -11);
		}
		return $entry;
	}

	/*
	 * 
	 * Return-ЬОРИ
	 * 
	 */

	private function returnClipError($a_name, $c_name) {
		$a_c = "Клипец $a_name  - $c_name не загружен :(";
		$body = '<img class="image_block" src="http://placehold.it/120x120" alt="ALT NAME" />
						  <div class="caption block_clip">
							<p>' . $a_c . '</p>
						  </div>';
		return '<li class="span2"><div class="thumbnail">' . $body . '</div></li>';
	}

	private function addAndReturnClipBody($a_name, $c_name, $video_code = '') {
		$c_code = $this->getCpu($c_name);
		$a_code = $this->getCpu($a_name);

		$query = "SELECT * FROM artist WHERE code LIKE '" . $a_code . "'"; // ищем по коду
		$author = $this->dbc->getRow($query);

		if (!empty($author)) { // если уже есть артист - добавляем только клип к нему
			$id_clip = $this->dbc->addClip($c_name, $c_code, $video_code, $author['id']);
			$this->formPlayList($id_clip);
		} else { // если нет артиста, добавляем артиста а потом клип к нему
			$id_clip = $this->dbc->addClip($c_name, $c_code, $video_code, $this->dbc->addArtist($a_name, $a_code));
			$this->formPlayList($id_clip);
		}
		return $this->returnClipLiBody($a_name, $c_name, $a_code, $c_code, $video_code);
	}

	private function returnOurVideo($clip) {
		$this->formPlayList($clip['cid']);
		return $this->returnClipLiBody($clip['a_name'], $clip['c_name'], $clip['a_code'], $clip['c_code'], $clip['video']);
	}
	
	private function returnClipLiBody($a_name, $c_name, $a_code, $c_code, $video) {
		$body = '<a target="_blank" class="video thumbnail"  title="' . $c_name . '" href="/' . $a_code . '/' . $c_code . '">    
						<img class="image_block" src="http://img.youtube.com/vi/' . $video . '/0.jpg" alt="">
						  <div class="caption block_clip">
							<p>' . $a_name . ' - ' . $c_name . '</p>
						  </div>
						</a>';
		return '<li class="span2"><div class="thumbnail">' . $body . '</div></li>';
	}

	private function getCpu($title) {
		$trans = array("а" => "a",
			"б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "yo", "ж" => "j", "з" => "z", "и" => "i", "і" => "i", "і" => "i", "й" => "i", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "y", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sh", "ы" => "i", "э" => "e", "ю" => "u", "я" => "ya",
			"А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ё" => "Yo", "Ж" => "J", "З" => "Z", "И" => "I", "І" => "I", "Й" => "I", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "У" => "Y", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "Ch", "Ш" => "Sh", "Щ" => "Sh", "Ы" => "I", "Э" => "E", "Ю" => "U", "Я" => "Ya", "є" => "ye", "Є" => "Ye",
			"ь" => "", "Ь" => "", "ъ" => "", "Ъ" => "");
		$text = strtr($title, $trans);
		$cpu = str_replace(' ', '-', $text);
		$cpu = str_replace('--', '-', $cpu);
		return preg_replace('/[^a-zA-Z-_0-9]+/i', '', $cpu);
	}

	private function getRequestForYoutube($title) {
		$cpu = str_replace(' ', '+', $title);
		$cpu = str_replace('  ', '+', $cpu);
		return preg_replace('/[^a-zA-Zа-яА-Я+0-9]+/i', '', $cpu);
	}

	/*
	 * Привязує чувака до кліпа
	 */

	private function formPlayList($clip_id) {
		$us_id = $this->user_id;
		$query = "
			INSERT INTO `usertoclip` (
			`id`,
			`user_vk_id`,
			`clip_id`
			)
			VALUES (
			NULL ,  '$us_id',  '$clip_id'
				);";
		mysql_query($query);
	}

}