<?php

	function encryptpassword($password)
	{
		$first = md5($password . salt_key1);
		$second = md5(substr($first, -8) . salt_key2 . substr($first, 0, 8));

		return $second;
	}

	function getuserbylogin($user, $password)
	{
		$encryptedpw = encryptpassword($password);
		return R::findOne('user', ' name = ? AND password = ? ', array($user, $encryptedpw));
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

	function creatediranduser($username, $password)
	{
		$home = basepath . "data/" . $username;
		//echo $home . "<br />";
		$old = umask(0);
		mkdir($home, 0775);
		umask($old);

		$strUserAdd = 'sudo /usr/sbin/useradd ' . $username . ' -d ' . $home . ' -g nginx -c "' . $username . '" -s /bin/bash';
		exec($strUserAdd, $result);
		//var_dump($result);

		$strChownDir = 'sudo /bin/chown ' . $username . ':nginx -R ' . $home;
		exec($strChownDir, $result);
		//var_dump($result);

		$strSetPw = 'echo ' . $password . ' | sudo /usr/bin/passwd ' . $username . ' --stdin';
		exec($strSetPw, $result);
		//var_dump($result);
	}

	function createserverdir($server)
	{
		$gamefiles = gamespath . $server->game->skeleton . "/*";
		$destdir = datapath . $server->user->name . "/" . $server->name;

		$old = umask(0);
		mkdir($destdir, 0775);
		umask($old);

		$strCopy = 'sudo /bin/cp -r ' . $gamefiles . " " . $destdir;
		exec($strCopy, $result);

		$strChownDir = 'sudo /bin/chown ' . $username . ':nginx -R ' . $home;
		exec($strChownDir, $result);
	}

	function removediranduser($username)
	{
		$strUserDel = 'sudo /usr/sbin/userdel ' . $username;
		exec($strUserAdd, $result);
	}

	function getrandomchar()
	{
		$c = 'ABCDEFGH-IJKLMNOPQR_STUVWXYZ012.3456789ab!cdefghijklmno*pqrstuvwxyz';
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
?>