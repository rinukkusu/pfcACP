<?php
	require_once('../settings.php');
	require_once('../inc/rb.php');
	R::setup(sqlite_con_string);

	$current_user = NULL;
	$sid = $_COOKIE['sid'];
	if (strlen($sid) > 0) {
		$current_user =  R::findOne('user', ' sid = ? ', array($sid));
		if (!isset($current_user)) {
			setcookie("sid", "");
		}
	}
	else {
		setcookie("sid", "");
	}

	if (isset($current_user) && $current_user->isadmin)
	{
		$id = trim(str_replace('/', '_', $_GET['u']));
		$user = R::load('user', $id);
		if ($user)
		{
			if ($user->isadmin)
			{
				$user->active = false;
				$user->sid = "";
				R::store($user);
			}
			else
			{
				R::trash($user);
			}
		}
	}

	header("Location: " . baseurl . "index.php?p=users");
?>