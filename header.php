<?php
	if (defined("loaded")) {
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo title; ?>@<?php echo gethostname(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<script type="text/javascript">
			function setCurrentTime()
			{
				$.get('act/get_time_h.php', { }, 
					function(data) 
					{
						$('#elm_time').html(data);
						setTimeout(setCurrentTime(), 1000*5);
					});
			}
		</script>

		<!-- Le styles -->
		<link href="assets/css/bootstrap.css" rel="stylesheet">
		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
				background-color: #f5f5f5;
			}
			.sidebar-nav {
				padding: 9px 0;
			}

			@media (max-width: 960px) {
				/* Enable use of floated navbar text */
				.navbar-text.pull-right {
					float: none;
					padding-left: 5px;
					padding-right: 5px;
				}
			}

			.form-signin {
				max-width: 300px;
				padding: 19px 29px 29px;
				margin: 0 auto 20px;
				background-color: #fff;
				border: 1px solid #e5e5e5;
				-webkit-border-radius: 5px;
				   -moz-border-radius: 5px;
				        border-radius: 5px;
				-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
				   -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
				        box-shadow: 0 1px 2px rgba(0,0,0,.05);
			}
			.form-signin .form-signin-heading,
			.form-signin .checkbox {
				margin-bottom: 10px;
			}
			.form-signin input[type="text"],
			.form-signin input[type="password"] {
				font-size: 16px;
				height: auto;
				margin-bottom: 15px;
				padding: 7px 9px;
			}

			.well {
				background-color: #FFFFFF;
			}
		</style>
		<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
	</head>

	<body onload="setCurrentTime();<?php 
	if ($page=='usage') 
		echo 'JavaScript:timedRefresh(5000);'; 
	?>">

		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href="index.php"><?php echo title; ?></a>
					<div class="nav-collapse collapse">
						<p class="navbar-text pull-right">
<?php
	if (isset($current_user)) {
?>
							Logged in as <a href="index.php?p=profile" class="navbar-link"><?php echo $current_user->name; ?></a>
<?php
	}
	else {
?>
							<a href="index.php?p=login" class="navbar-link">Login</a>
<?php
	}
?>
						</p>
						<ul class="nav">
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>

		<div class="container-fluid">

<?php
	}
	else {
		echo "ach du ...";
	}
?>