<?php
	require_once('../settings.php');
	require_once('../inc/functions.php');
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
		$id = trim(str_replace('/', '_', $_GET['id']));
		$name = trim(str_replace('/', '_', $_GET['name']));
		$isadmin = trim(str_replace('/', '_', $_GET['isadmin']));

		$user = R::load('user', $id);
		if (!$user)
		{
			echo "Kein Benutzer mit dieser ID in der Datenbank gefunden.";
		}
		elseif (strlen($name) < 1)
		{
			echo "Name des Benutzers muss mindestens 1 Zeichen lang sein.";
		}
		elseif (R::findOne('user', ' name = ? AND id != ? ', array($name, $id)))
		{
			echo "Es ist bereits ein Benutzer mit diesem Namen vorhanden.";
		}
		elseif (R::findOne('user', ' mail = ? AND id != ? ', array($mail, $id)))
		{
			echo "Es ist bereits ein Benutzer mit dieser E-Mail vorhanden.";
		}
		else
		{
			$user->name = $name;
			$user->isadmin = $isadmin == '1' ? true : false;
			R::store($user);
			echo "1";
		}
	}
	else
	{
		echo "Nicht eingeloggt oder nicht berechtigt.";
	}

?>