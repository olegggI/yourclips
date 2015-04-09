<?php
include_once 'bd/bd.php';
include_once 'classes/bd.php';
$res = new Base($db);
session_start();
if (isset($_GET['out'])) {
	unset($_SESSION['access_token']);
	unset($_SESSION['user_id']);
}
if (isset($_GET['code'])) {
	$res->initialAuthorization($_GET['code']);
}
if (isset($_GET['reset'])) {
	$res->delPlayList();
}
$client_id = 3707078;
$client_secret = 'QezPxlYiijjOxSE1YYbt';
$redirect_uri = 'http://music.natirka.com/my-clips.php?page_id=2';
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
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
		<script src="js/js.js" type="text/javascript"></script>
		

	</script>
</head>
<body>
	<div class="container-fluid maincontainer" ng-app ng-controller="Controller">
		<div class="row-fluid">
			<div class="span12">
				<a href="/">
					<h3 class="text-info">
						Твои клипы!
					</h3>
				</a>
				<ul style="float: left" class="nav nav-pills navigation">
					<li>
						<a href="/">Клипы</a>
					</li>
					<li class="active">
						<a href="/my-clips.php">Мои клипы</a>
					</li>
				</ul>
				<ul style="float: right" class="nav nav-pills navigation">
					<li>
						<a href="?reset=1">Перезагрузить клипы</a>
					</li>
				</ul>
				<div class="row-fluid">
					<div class="span12 text-center">
						<div class="jopa">

						</div>
						<? if (isset($_SESSION['access_token']) && isset($_SESSION['user_id'])): ?>
							<? if ($res->isPlayList($_SESSION['user_id'])): ?>
								<h1 class="text-left"><small>{{name}}</small></h1>
								<div class="row-fluid">	
									<div class="span8 text-left">
										<div class="videoWrapper text-left">
											<iframe  
												src="http://www.youtube.com/embed/{{video}}" 
												frameborder="0" 
												allowfullscreen>
											</iframe>
										</div>
									</div>

									<div class="span4 text-left">
										<div style="max-height: 370px; overflow-y: auto; border: 1px #000;">
											<div class="row-fluid">
												<ul class="thumbnails">
													<? foreach ($res->userPlayList as $clip): ?>
														<li video="<?= $clip['video'] ?>" name="<?= $clip['a_name'] ?> - <?= $clip['c_name'] ?>" class="span12 clip-main-block">
															<a  ng-click="video='<?= $clip['video'] ?>'; name='<?= $clip['a_name'] ?> - <?= $clip['c_name'] ?>';" class="thumbnail clearfix clip-main-link"  title="<?= $clip['a_name'] ?> - <?= $clip['c_name'] ?>" href="#">    
																<img class="image_block pull-left span5 clearfix" src="http://img.youtube.com/vi/<?= $clip['video'] ?>/0.jpg" alt="ALT NAME">
																<div class="caption block_clip">
																	<p><?= $clip['c_name'] ?></p>
																</div>
															</a>
														</li>
													<? endforeach; ?>
												</ul>
											</div>
										</div>
									</div>
								</div>

								<? // $res->getPlayLsit(); ?>
							<? else: ?>	
								<script type="text/javascript"> get_row_number('<?= $_SESSION['access_token'] ?>', '<?= $_SESSION['user_id'] ?>');</script>
								<div class="loader" align="center">
									<img src="/img/loader.gif" />
								</div>
							<? endif ?>
						<? else: ?>
							<a href="https://oauth.vk.com/authorize?client_id=<?=$client_id ?>&scope=audio,first_name,last_name&redirect_uri=http://music.natirka.com/my-clips.php?page_id=2&response_type=code&v=5.29"><button class="btn btn-large btn-primary btn-info" type="button">Ввойти через Вконтакте</button></a>
						<? endif ?>
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