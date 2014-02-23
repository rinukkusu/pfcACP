<?php
	require_once('settings.php');
	require_once('inc/rb.php');
	require_once('inc/functions.php');

	touch(basepath . "db/db.sqlite");
	R::setup("sqlite:" . basepath . "db/db.sqlite");

	$page = trim(str_replace('/', '_', $_GET['p']));


	$sid = "";
	if ($page == 'login' && isset($_POST['username']) && isset($_POST['password']))
	{
		$sid = checklogin();
	}
	elseif ($page == 'logout') {
		setcookie("sid", "");
		$page = "login";
	}
	else {
		$sid = $_COOKIE['sid'];
	}

	$current_user = NULL;

	
	if (strlen($sid) > 0) {
		$current_user =  R::findOne('user', ' sid = ? ', array($sid));
		if (!isset($current_user)) {
			setcookie("sid", "");
		}
	}
	else {
		setcookie("sid", "");
	}

	$adminuser = R::findOne('user', ' name = ? ', array('admin'));
	if (strlen($page) == 0) 
	{
		if (isset($current_user))
			$page = "dashboard";
		else
			$page = "login";
	}

	if (!isset($adminuser))
		$page = "setup";

	define("loaded", true);

	require_once("header.php");

	$ppath = "inc/page." . $page . ".inc";
	if (!file_exists($ppath))
	{
?>
		<div class="alert alert-error">
			<strong>Achtung:</strong> Seite nicht gefunden.
		</div>
<?php
	}
	else {
		require_once($ppath);
	}
	

	require_once("footer.php");
?>
