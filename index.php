<?php
	// load config
	require_once('config.php');

	// load RedBean
	require_once(join(DS, array(INC_PATH, 'rb.php')));

	// get page and make sure it has the correct format
	// characters a to z case insensitive
	$_page = isset($_GET['page']) ? $_GET['page'] : 'index';

	if (!preg_match("/^[a-z]+$/i", $_page)) {
		$_page = 'error';
	}

	// do routing
	switch ($_page) {
		default:
			$template_path = join(DS, array(TEMPL_PATH, $_page . '.php'));

			if (file_exists($template_path)) {
				require_once($template_path);
			}
			else {
				echo '404';
			}
			break;
	}

?>