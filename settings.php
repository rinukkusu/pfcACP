<?php

	// set locale stuff
	date_default_timezone_set('Europe/Berlin');
	setlocale(LC_TIME, 'de_DE.UTF-8', 'de_DE.utf8', 'de_DE', 'deu_deu', 'de-DE', 'de-de', 'de_de');

	// page title
	define("title", "pfcACP");

	// helper defines
	define("baseurl", "http://rinukkusu.sub-r.de/pfcACP/");
	define("basepath", "/home/rinukkusu/www/pfcACP/");
	define("mailpath", "sub-r.de/mails/pfcACP/");

	define("psql_con_string", "host=localhost dbname=[db] user=[user] password=[password]");
	define("mysql_con_string", "mysql:host=localhost;dbname=[db]");

?>
