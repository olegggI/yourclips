<?php

class Base {

	public $db;
	public $userPlayList;

	public function __construct($db) {
		$this->db = $db;
	}

	public function getRows($query = '') {
		$res = mysql_query($query, $this->db);
		$rows = array();
		while ($row = mysql_fetch_array($res)) {
			foreach ($row as $key => $value) {
				$row[$key] = iconv('CP1251', 'UTF-8', $value);
			}
			$rows[] = $row;
		};
		return $rows;
	}

	public function getRow($query = '') {
		$res = mysql_query($query, $this->db);
		$row = mysql_fetch_array($res);

		foreach ($row as $key => $value) {
			$row[$key] = iconv('CP1251', 'UTF-8', $value);
		}

		return $row;
	}

	public function addClip($name, $code, $youtube_id, $artist_id) {
		$res = mysql_query("INSERT INTO `clip` (
					`id` ,
					`name` ,
					`code` ,
					`youtube_id` ,
					`artist_id`
					)
					VALUES (
					NULL ,  '" . iconv('UTF-8', 'CP1251', $name) . "', '$code',  '$youtube_id',  '$artist_id'
					);", $this->db);
		if (!$res)
			return FALSE;
		else
			return mysql_insert_id();
	}

	public function addArtist($name, $code) {
		if (preg_match("/[а-яА-Я]+/i", $name))
			$type = 1;
		else
			$type = 0;

		$res = mysql_query("INSERT INTO  `artist` (
							`id` ,
							`name` ,
							`code` ,
							`type`
							)
							VALUES (
							NULL ,  '" . iconv('UTF-8', 'CP1251', $name) . "',  '$code',  '$type'
							);", $this->db);
		if (!$res)
			return FALSE;
		else
			return mysql_insert_id();
	}

	public function initialAuthorization($code) {
		/** Данные приложения  * */
		$client_id = 3707078;
		$client_secret = 'QezPxlYiijjOxSE1YYbt';
		$redirect_uri = 'http://music.natirka.com/my-clips.php?page_id=2';
		$params = array(
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'code' => $code,
			'redirect_uri' => $redirect_uri
		);

		$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

		if (isset($token['access_token'])) {
			$params = array(
				'uids' => $token['user_id'],
				'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
				'access_token' => $token['access_token']
			);

			$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
			$_SESSION['access_token'] = $token['access_token'];
			$_SESSION['user_id'] = $userInfo['response'][0]['uid'];
			$_SESSION['first_name'] = $userInfo['response'][0]['first_name'];
			$_SESSION['last_name'] = $userInfo['response'][0]['last_name'];
			$_SESSION['bdate'] = $userInfo['response'][0]['bdate'];
			$_SESSION['photo_big'] = $userInfo['response'][0]['photo_big'];
		}
	}

	public function getPlayLsit() {
		print_r($this->userPlayList);
	}
	
	
	public function delPlayList() {
		$query = "DELETE FROM usertoclip WHERE user_vk_id = ".$_SESSION['user_id'];
		mysql_query($query, $this->db);
	}
	
	

	public function isPlayList($user_vk_id) {
		$query = "	SELECT clip.code as c_code, clip.youtube_id as video, artist.code as a_code,
					artist.name as a_name, clip.name as c_name, usertoclip.id as uid
					FROM usertoclip
					LEFT JOIN clip ON clip.id = usertoclip.clip_id
					LEFT JOIN artist ON artist.id = clip.artist_id
					WHERE usertoclip.user_vk_id =$user_vk_id
					ORDER BY  `usertoclip`.`id` ASC ";
		$this->userPlayList = $this->getRows($query);
		if (!empty($this->userPlayList))
			return true;
		else
			return false;
	}

}
