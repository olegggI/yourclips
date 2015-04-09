<?php

class Server {
	/** ʳ������ ���� � ������ * */

	const NUMBER_OF_PACKET = 6;
	
	private $user_id; // vk id
	private $dbc; // ������ ����

	public function __construct($db) {
		$this->dbc = new Base($db);
	}

	/** ������� ��������� ������� ������������ * */
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
			/// ��� ����� ��������� �� ��� ����, � �� �� ���� ���. ���� ����� ��� ���� - �� ��������� �����
			$q = "SELECT c.id AS cid, c.youtube_id AS video, c.name AS c_name, a.name AS a_name, a.code AS a_code, c.code AS c_code
						FROM clip c
						LEFT JOIN artist a ON a.id = c.artist_id
						WHERE c.code = '" . $this->getCpu($query[$i]['title']) . "'";

			$clip = $this->dbc->getRow($q);

			if (!empty($clip)) {
				$string .= $this->returnOurVideo($clip);
			} else {
				$vidos = (string) $this->getVideos($query[$i]["artist"], $query[$i]["title"]);
				if ($vidos != 'Array' && !empty($vidos)) { // ���� ����� ����
					$string .= $this->addAndReturnClipBody($query[$i]["artist"], $query[$i]["title"], $vidos);
				} else { // ���� �� �����
					$string .= $this->returnClipError($query[$i]["artist"], $query[$i]["title"]);
				}
			}
		}
		$string .= '</ul>';
		die(json_encode(array("packeds" => $string)));
	}

	/** ������� ��������� ������� ������������ * */
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
	 * Return-����
	 * 
	 */

	private function returnClipError($a_name, $c_name) {
		$a_c = "������ $a_name  - $c_name �� �������� :(";
		$body = '<img class="image_block" src="http://placehold.it/120x120" alt="ALT NAME" />
						  <div class="caption block_clip">
							<p>' . $a_c . '</p>
						  </div>';
		return '<li class="span2"><div class="thumbnail">' . $body . '</div></li>';
	}

	private function addAndReturnClipBody($a_name, $c_name, $video_code = '') {
		$c_code = $this->getCpu($c_name);
		$a_code = $this->getCpu($a_name);

		$query = "SELECT * FROM artist WHERE code LIKE '" . $a_code . "'"; // ���� �� ����
		$author = $this->dbc->getRow($query);

		if (!empty($author)) { // ���� ��� ���� ������ - ��������� ������ ���� � ����
			$id_clip = $this->dbc->addClip($c_name, $c_code, $video_code, $author['id']);
			$this->formPlayList($id_clip);
		} else { // ���� ��� �������, ��������� ������� � ����� ���� � ����
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
		$trans = array("�" => "a",
			"�" => "b", "�" => "v", "�" => "g", "�" => "d", "�" => "e", "�" => "yo", "�" => "j", "�" => "z", "�" => "i", "�" => "i", "�" => "i", "�" => "i", "�" => "k", "�" => "l", "�" => "m", "�" => "n", "�" => "o", "�" => "p", "�" => "r", "�" => "s", "�" => "t", "�" => "y", "�" => "f", "�" => "h", "�" => "c", "�" => "ch", "�" => "sh", "�" => "sh", "�" => "i", "�" => "e", "�" => "u", "�" => "ya",
			"�" => "A", "�" => "B", "�" => "V", "�" => "G", "�" => "D", "�" => "E", "�" => "Yo", "�" => "J", "�" => "Z", "�" => "I", "�" => "I", "�" => "I", "�" => "K", "�" => "L", "�" => "M", "�" => "N", "�" => "O", "�" => "P", "�" => "R", "�" => "S", "�" => "T", "�" => "Y", "�" => "F", "�" => "H", "�" => "C", "�" => "Ch", "�" => "Sh", "�" => "Sh", "�" => "I", "�" => "E", "�" => "U", "�" => "Ya", "�" => "ye", "�" => "Ye",
			"�" => "", "�" => "", "�" => "", "�" => "");
		$text = strtr($title, $trans);
		$cpu = str_replace(' ', '-', $text);
		$cpu = str_replace('--', '-', $cpu);
		return preg_replace('/[^a-zA-Z-_0-9]+/i', '', $cpu);
	}

	private function getRequestForYoutube($title) {
		$cpu = str_replace(' ', '+', $title);
		$cpu = str_replace('  ', '+', $cpu);
		return preg_replace('/[^a-zA-Z�-��-�+0-9]+/i', '', $cpu);
	}

	/*
	 * ������� ������ �� ����
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