<?php

	function encryptpassword($password)
	{
		//$first = md5($password . salt_key1);
		//$second = md5(substr($first, -8) . salt_key2 . substr($first, 0, 8));

		return crypt($password);
	}

	function getuserbylogin($user, $password)
	{
		$potential_user = R::findOne('user', ' name = ? ', array($user));

		if (crypt($password, $potential_user->password) == $potential_user->password) {
			return $potential_user;
		}

		return null;
	}

	function checklogin()
	{
		$username = isset($_POST['username']) ? $_POST['username'] : NULL;
		$password = isset($_POST['password']) ? $_POST['password'] : NULL;

		$sid = "";

		if (isset($username) && isset($password))
		{
			$user = getuserbylogin($username, $password);

			if (isset($user))
			{
				$sid = md5($user->name . R::isoDateTime());
				$user->sid = $sid;
				R::store($user);
				setcookie("sid", $sid);
			}
		}

		return $sid;
	}

	function getrandomchar()
	{
		$c = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		return substr($c, mt_rand(0, strlen($c)), 1);
	}

	function generatepassword($length = 8)
	{
		$passwd = "";
		for ($i=0; $i < $length; $i++) { 
			$passwd .= getrandomchar();
		}

		return $passwd;
	}

	function checkmail($mail)
	{
		if (!preg_match('/^[a-zA-Z0-9_.+-]*@[a-zA-Z0-9-]*\.[a-zA-Z0-9-.]*$/', $mail))
			return false;
		else
			return true;
	}

	function getcpu()
	{
		exec('ps -aux', $processes);
	    foreach($processes as $process){
	        $cols = split(' ', ereg_replace(' +', ' ', $process));
	        if (strpos($cols[2], '.') > -1){
	            $cpuUsage += floatval($cols[2]);
	        }
	    }
	    return $cpuUsage;
	}

	function getmem()
	{
		exec('ps -aux', $processes);
	    foreach($processes as $process){
	        $cols = split(' ', ereg_replace(' +', ' ', $process));
	        if (strpos($cols[3], '.') > -1){
	            $memUsage += floatval($cols[3]);
	        }
	    }
	    return $memUsage;
	}

	function getproc()
	{
		$ret = array();
		exec('ps -aux | sort -r -k3,4 | head -10', $processes);
		array_shift($processes);
		foreach($processes as $process){
	        $cols = split(' ', ereg_replace(' +', ' ', $process));
	        $proc = array();
	        $proc['user'] = $cols[0];
	        $proc['pid'] = $cols[1];
	        $proc['cpu'] = $cols[2];
	        $proc['mem'] = $cols[3];
	        $proc['cmd'] = $cols[10];
	        $proc['arg1'] = $cols[11];

	        $ret[] = $proc;
	    }

	    return $ret;
	}

	function isportopen($port)
	{
		exec('nmap -p ' . $port . ' localhost', $nmap);
		if (strpos($nmap[5], 'open'))
			$ret = 0;
		elseif (strpos($nmap[5], 'closed'))
			$ret = 1;
		else
		{
			$ret = "Keine g&uuml;ltige Ausgabe von nmap.<br /><code>nmap -p " . $port . " localhost</code><br /><pre>";
			foreach($nmap as $line)
			{
				$ret .= $line . "<br />";
			}
			$ret .= "</pre>";
		}
		return $ret;
	}

	function getserverlog($server)
	{
		//exec()
	}

	function getmailaccountdata($username)
	{
		$dbcon = pg_connect(psql_con_string);
		$escaped_user = pg_escape_string($username);
		$result = pg_query($dbcon, "SELECT * FROM users WHERE userid='$escaped_user'");

		$ret = array();
		while ($mail = pg_fetch_object($result)) {
			$ret[] = $mail;
		}

		pg_close($dbcon);

		return $ret;
	}

	function getothermails($username)
	{
		$dbcon = pg_connect(psql_con_string);
		$escaped_user = pg_escape_string($username);

		$result = pg_query($dbcon, "SELECT * FROM virtual WHERE userid='$escaped_user'");
		while ($mail = pg_fetch_array($result)) {
			$ret[] = $mail;
		}

		pg_close($dbcon);

		return $ret;
	}

	function getavailablehosts()
	{
		$dbcon = pg_connect(psql_con_string);
		$result = pg_query($dbcon, "SELECT * FROM transport");

		$ret = array();
		while ($host = pg_fetch_object($result)) {
			$ret[] = $host;
		}

		pg_close($dbcon);

		return $ret;
	}

	function addmailuser($username, $host, $password)
	{
		// userid, password, realname, uid, gid, home, mail

		// check, if user already exists
		$dbcon = pg_connect(psql_con_string);

		$escaped_user = pg_escape_string($username);
		$result = pg_query($dbcon, "SELECT * FROM users WHERE userid='$escaped_user'");

		$rows = pg_num_rows($result);
		if ($rows == 1) {
			$user = pg_fetch_object($result);

			pg_close($dbcon);
			return $user->password;
		}
		else {
			$insertstring = "INSERT INTO users ";
			$insertstring.= "(userid, password, realname, uid, gid, home, mail) VALUES ";
			$insertstring.= "('$escaped_user', '$password', '$escaped_user', '1100', '100', '".mailpath."$escaped_user', '$escaped_user@$host')";

			@mkdir(mailpath.$escaped_user);
			@chmod(mailpath.$escaped_user, 0775);

			pg_query($dbcon, $insertstring);
			pg_close($dbcon);

			return $password;
		}
	}

	function addanothermail($username, $mail)
	{
		$dbcon = pg_connect(psql_con_string);
		$escaped_user = pg_escape_string($username);

		pg_query($dbcon, "INSERT INTO virtual VALUES ('$mail', '$username');");

		pg_close($dbcon);
	}

	function mailexists($mail)
	{
		$dbcon = pg_connect(psql_con_string);
		$result1 = pg_query($dbcon, "SELECT * FROM users WHERE mail='$mail'");
		$result2 = pg_query($dbcon, "SELECT * FROM virtual WHERE address='$mail'");

		if (pg_num_rows($result1) == 0 && pg_num_rows($result2) == 0) {
			return false;
		}

		return true;
	}

?>