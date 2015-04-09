<?
include_once 'bd/bd.php';
include_once 'classes/bd.php';
$artist_code = $_GET['artist_code'];
$clip_code = $_GET['clip_code'];
$query = "SELECT c.name as cname, a.name as aname, c.youtube_id as video, a.id as aid,
				 a.type as type, a.code as acode, c.code as ccode 
					FROM  artist a 
					JOIN clip c ON c.artist_id = a.id
					WHERE c.code = '$clip_code' AND a.code =  '$artist_code'";
$res = new Base($db);
$clip_data = $res->getRow($query);

$clips2artist = $res->getRows("SELECT * 
					FROM clip
					WHERE artist_id =" . $clip_data["aid"]);
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title><?= $clip_data['cname'] ?> - <?= $clip_data['aname'] ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content=""> 

		<!-- Le styles -->
		<link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="../assets/js/html5shiv.js"></script>
		<![endif]-->
		<script src="http://90-e.com.ua/js/jqueries.js" type="text/javascript"></script>
		<script src="/js/js.js" type="text/javascript"></script>
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
				<h1><small><a href="/<?= $clip_data['acode'] ?>/"><?= $clip_data['aname'] ?></a> - <?= $clip_data['cname'] ?></small></h1>
				<div class="row-fluid">
					<div class="span7 text-center">
						<div class="videoWrapper text-center">
							<iframe width="600"  
									height="337"  
									src="http://www.youtube.com/embed/<?= $clip_data['video'] ?>" 
									frameborder="0" 
									allowfullscreen>
							</iframe>
						</div>
					</div>
					<div class="span5 text-left">
						<div class="row-fluid">
							<ul class="thumbnails">
								<? foreach ($clips2artist as $value): ?>
									<li class="span12 clip-main-block">
										<a class="thumbnail clearfix clip-main-link <? if ($_SERVER['REQUEST_URI'] == '/' . $clip_data['acode'] . '/' . $value['code']): ?>obvodka<? endif ?>"   title="<?= $value['name'] ?> - <?= $clip_data['aname'] ?>" href="/<?= $clip_data['acode'] ?>/<?= $value['code'] ?>">    
											<img class="image_block pull-left span5 clearfix" src="http://img.youtube.com/vi/<?= $value['youtube_id'] ?>/0.jpg" alt="ALT NAME">
											<div class="caption block_content text-center">
												<h4><?= $value['name'] ?></h4>
												<p class="ptext"><?= $clip_data['aname'] ?></p>
											</div>
										</a> 
									</li>
								<? endforeach; ?>
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