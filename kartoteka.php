<?php
include('bd/bd.php');
include('classes/Pagination.class.php');
include('classes/bd.php');
$dbc = new Base($db);
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
		<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" media="screen" />
		<script type="text/javascript" src="fancybox/video.js"></script>
		<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" media="screen" />
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
						<h3 align='left'>Исполнители на букву: <font color='green'><?= $_GET['lit'] ?></font></h3>
						<table width='100%'><tr>
								<?
								$result = mysql_query("SELECT * FROM artist ORDER BY `artist`.`name` ASC ", $db);
								$myrow = mysql_fetch_array($result);
								$y = 0;
								do {

									if (iconv('CP1251', 'UTF-8', $myrow['name']{0}) == $_GET['lit']) {
										if ($y % 3 == 0) {
											echo '</tr><tr>';
										}
										echo "<td valign='top'><ul>";
										$ispol = $myrow['id'];
										printf('<li><a class="nav" href="/%s/" title="Клипы - ' . iconv('CP1251', 'UTF-8', $myrow['name']) . '"><b>%s</b></a>', $myrow['code'], iconv('CP1251', 'UTF-8', $myrow['name']));
										$res = mysql_query("SELECT * FROM clip WHERE artist_id=$ispol ", $db);
										$row = mysql_fetch_array($res);
										$i = 1;
										echo "<ul>";
										do {
											printf('<li><a class="nav" href="/%s/%s" title="Клип ' . iconv('CP1251', 'UTF-8', $myrow['name']) . ' - ' . iconv('CP1251', 'UTF-8', $row['name']) . '"><u>%s</u></a></li>', $myrow['code'], $row['code'], iconv('CP1251', 'UTF-8', $row['name']));
											$i++;
										} while ($row = mysql_fetch_array($res));
										echo "</ul></li></ul></td>";
										$y++;
									};
								} while ($myrow = mysql_fetch_array($result));
								?>
							</tr></table>
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