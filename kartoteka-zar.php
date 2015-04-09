<?php
if (isset($_GET["lit"])) {
	if (file_exists('cache-kar/index_' . $_GET["lit"] . '.cache') && !isset($_GET["nom"])) {
		readfile('cache-kar/index_' . $_GET["lit"] . '.cache');
		exit();
	}
	ob_start();
}
include('blocks/bd.php'); 
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Зарубежные исполнители</title>
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


		<table width="1000px" border="0" align="center" bordercolor="#FFFFFF" cellpadding="0px" cellspacing="0px" >
<?php include('blocks/header.php'); ?>
			<tr>
				<td><table width="100%" border="0"  cellpadding="0px" cellspacing="0px" >
						<tr>


							<td  valign="top" bgcolor="#ffffff"  >
								<table align="center" cellpadding="0px" cellspacing="0px" bgcolor="#ffffff">
									<tr>
										<td>
<?
$alf = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�");
echo "<div align='center' style='margin: 5px; font-family: Verdana; float: left' >";
foreach ($alf as $value) {
	echo "<a class='nav' title='���� ����������� �� ����� - " . $value . "' href='http://90-e.com.ua/kartoteka.php?lit=" . $value . "'><font style='font-size: 12px; color: #000000;'>" . $value . "</font></a> ";
};
echo "</div>";
?> </td>
										<td>
											<?
											unset($value);
											$alf = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
											echo "<div style='margin: 5px; font-family: Verdana; '>";
											foreach ($alf as $value) {
												echo "<a class='nav' title='���������� ����������� �� ����� - " . $value . "' href='http://90-e.com.ua/kartoteka-zar.php?lit=" . $value . "'><font style='font-size: 12px; ; color: #000000;'>" . $value . "</font></a> ";
											};
											echo "</div>";
											?>
										</td></tr></table>


											<?
											$result = mysql_query("SELECT * FROM video_zar ORDER BY `video_zar`.`title` ASC ", $db);

											$myrow = mysql_fetch_array($result);
											echo "<h2 align='center'>���������� ����������� 90-� �� �����: <font color='green'>" . $_GET['lit'] . "</font></h2>";
											echo "<table width='100%'><tr>";
											$y = 0;
											do {

												if ($myrow['title']{0} == $_GET['lit']) {
													if ($y % 3 == 0) {
														echo '</tr><tr>';
													}
													echo "<td valign='top'><ul>";
													$ispol = $myrow['id'];
													printf('<li><a class="nav" href="http://90-e.com.ua/zarubezhnye/%s" title="����� - ' . $myrow['title'] . '"><b>%s</b></a>', $myrow['cpu'], $myrow['title']);
													$res = mysql_query("SELECT * FROM clips_zar WHERE ispol=$ispol ", $db);
													$row = mysql_fetch_array($res);
													$i = 1;
													echo "<ul>";
													do {
														printf('<li><a  class="nav" href="http://90-e.com.ua/zarubezhnye/%s/%s" title="���� ' . $myrow['title'] . ' - ' . $row['title'] . '"><u>%s</u></a></li>', $myrow['cpu'], $row['code'], $row['title']);
														$i++;
													} while ($row = mysql_fetch_array($res));
													echo "</ul></li></ul></td>";
													$y++;
												};
											} while ($myrow = mysql_fetch_array($result));

											echo "</tr></table>";
											?>






							</td>


						</tr>
					</table>

				</td>

			</tr>

<?php include('blocks/footer.php'); ?>
		</table> 
	</body>
</html>
<?php
if (isset($_GET["lit"])) {

	$buffer = ob_get_contents();
	ob_end_flush();
	$fp = fopen('cache-kar/index_' . $_GET["lit"] . '.cache', 'w');
	fwrite($fp, $buffer);
	fclose($fp);
}
?>
