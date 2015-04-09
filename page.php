<?
/** Данные приложения  * */
$client_id = 3707078;
$client_secret = 'QezPxlYiijjOxSE1YYbt';
?>
<?php
session_start();
if (isset($_GET['out'])) {
	unset($_SESSION['access_token']);
	unset($_SESSION['user_id']);
}
?>
<script src="http://90-e.com.ua/js/jqueries.js" type="text/javascript"></script>
<script type="text/javascript">

	function get_row_number(access_token, user_id) {
		$.ajax({
			url: 'server.php?call_row_number=' + 1 + '&access_token=' + access_token + '&user_id=' + user_id,
			success: function(data) {
				data = JSON.parse(data);
				go_create_array(data["count"], access_token, user_id);
				//go();
			}
		});
	}

	function go_create_array(e, access_token, user_id) {
		var seq = new Array();
		for (i = 1; i < e; i++) {
			seq.push(i);
		}
		go(seq, access_token, user_id);
	}

	function go(seq, access_token, user_id) {
		if (seq.length) {
			$.ajax({
				url: 'server.php?num_of_video=' + seq.shift() + '&access_token=' + access_token + '&user_id=' + user_id,
				success: function(ata) {
					var ata = JSON.parse(ata);
					var clip = '<a class="video"  title="' + ata["artist"] + ' - ' + ata["title"] + '" href="http://www.youtube.com/v/' + ata["videos"] + '?fs=1&amp;autoplay=1"><div class="vid" style="background: url(http://img.youtube.com/vi/' + ata["videos"] + '/1.jpg); width: 120px; height: 90px; float: left; margin: 5px;"><div align="center"><b>' + ata["artist"] + ' - ' + ata["title"] + '</b></div></a>';
					$('.clips').append(clip);
					go(seq, access_token, user_id);
				}
			});
		}
	}
</script>
<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript" src="fancybox/video.js"></script>

<?
if (isset($_SESSION['access_token']) && isset($_SESSION['user_id'])) {
	?>
	<a href="#" onclick="get_row_number('<?= $_SESSION['access_token'] ?>', '<?= $_SESSION['user_id'] ?>');">получить клипы!</a>
	<div class="clips"></div>
	<?
} else {
	if (isset($_GET['code'])) { // получаем код
		$code = $_GET['code'];
		// запрашиваем access_token
		$homepage = file_get_contents('https://oauth.vk.com/access_token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&code=' . $code . '&redirect_uri=http://music.natirka.com/?page_id=2');
		$info_array = (array) json_decode($homepage);
		$_SESSION['access_token'] = $info_array['access_token'];
		$_SESSION['user_id'] = $info_array['user_id'];
		?>

		<a href="#" onclick="get_row_number('<?= $_SESSION['access_token'] ?>', '<?= $_SESSION['user_id'] ?>');">получить клипы!</a>
		<div class="clips"></div>
		<?
	} else {
		?>
		Для определения Ваши любимых песен, перейдите пожалуйста по <a href="https://oauth.vk.com/authorize?client_id=<?= $client_id ?>&scope=audio&redirect_uri=http://music.natirka.com/?page_id=2&response_type=code">ссылке</a>
		<?
	}
}
?>