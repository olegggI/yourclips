<?php
include('bd/bd.php');
include('classes/Pagination.class.php');
include('classes/bd.php');
$dbc = new Base($db);

$curr_page = (isset($_GET['page'])) ? $_GET['page'] : 0;
$page = (isset($_GET['page'])) ? $_GET['page'] * 30 : 0;
$query = "SELECT `clip`.`code` as c_code ,
				 `clip`.`youtube_id` as video , 
				 `artist`.`name` as a_name, 
				 `clip`.`name` as c_name, 
				 `artist`.`code` as a_code
				 FROM  `clip` 
				 LEFT JOIN `artist` ON `artist`.`id` =  `clip`.`artist_id`
				 ORDER BY  `clip`.`id` DESC 
				 LIMIT $page , 30";

$clips = $dbc->getRows($query);

$handle = mysql_query("select count(1) FROM `clip`");
$tmp = mysql_fetch_array($handle);
$col_pages = floor($tmp[0] / 30);
$Pagination = new Pagination($curr_page, $col_pages);
$pagi = $Pagination->getPagi();
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Любимые клипы</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content=""> 

		<!-- Le styles -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="../assets/js/html5shiv.js"></script>
		<![endif]-->
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
		<script src="js/js.js" type="text/javascript"></script>  
	</script>
</head>

<body>
	<div class="container-fluid maincontainer">
		<div class="row-fluid">
			<div class="span12">
				<a href="/">
					<h3 class="text-info">
						Твои клипы!
					</h3>
				</a>
				<ul class="nav nav-pills navigation">
					<li class="active">
						<a href="/">Клипы</a>
					</li>
					<li>
						<a href="/my-clips.php">Мои клипы</a>
					</li>
				</ul>
				<div class="btn-toolbar">
					<div class="text-center"> 
						<?
						$alf = array("А", "Б", "В", "Г", "Д", "Е", "Ж", "З", "И", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Э", "Ю", "Я");

						foreach ($alf as $value) {
							echo "<a rel='nofollow' class='link' title='исполнители на букву - " . $value . "' href='/kartoteka.php?lit=" . $value . "'><button type='button' class='btn btn-mini'>" . $value . "</button></a>";
						};

						unset($value);

						$alf = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

						foreach ($alf as $value) {
							echo "<a rel='nofollow' class='link' title='наши исполнители на букву - " . $value . "' href='/kartoteka.php?lit=" . $value . "'><button type='button' class='btn btn-mini'>" . $value . "</button></a>";
						};
						?>

					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">

						<ul class="thumbnails piska">
							<? $i = 0; ?>
							<? foreach ($clips as $clip): ?>
								<? $i++; ?>
								<li class="span2 text-center">
									<div class="thumbnail text-center">
										<a class="thumbnail text-center"  title="<?= $clip['a_name'] ?> - <?= $clip['a_name'] ?>" href="/<?= $clip['a_code'] ?>/<?= $clip['c_code'] ?>">    
											<div class="text-center">
												<img class="image_block text-center" src="http://img.youtube.com/vi/<?= $clip['video'] ?>/0.jpg" alt="ALT NAME">
											</div>
											<div class="caption block_clip text-center">
												<p><?= $clip['c_name'] ?></p>
											</div>
										</a>
									</div>
								</li>
								<? if ($i % 6 == 0): ?>
								</ul><ul class="thumbnails piska">
								<? endif ?>
							<? endforeach; ?>
						</ul>
						<div class="pagination  text-center">
							<ul>
								<li>
									<a href="/"><<</a>
								</li>
								<? foreach ($pagi as $value): ?>
									<li <? if ($curr_page == $value[0]): ?> class="active" <? endif ?>>
										<a href="/?page=<?= $value[0] ?>"><?= $value[1] + 1 ?></a>
									</li>
								<? endforeach; ?>
								<li>
									<a href="/?page=<?= $col_pages ?>">>></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>