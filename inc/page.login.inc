<?php
	if (defined("loaded")) {
?>
	
<div class="row-fluid">
	<div class="span3 hidden-phone"></div>
	<div class="span6">
		<form class="form-signin" action="index.php?p=login" method="POST">
			<fieldset>

				<!-- Form Name -->
				<legend>Login</legend>

<?php

		$username = isset($_POST['username']) ? $_POST['username'] : NULL;
		$password = isset($_POST['password']) ? $_POST['password'] : NULL;
		$error = false;

		if (isset($username) && isset($password))
		{
			$user = getuserbylogin($username, $password);

			if (isset($user))
			{
				if ($user->id == 1) {
					// update host data
					$hosts = getavailablehosts();

					foreach ($hosts as $ahost) {
						$host = R::findOne('host', 'domain = ?', array( $ahost->domain ));
						if (!$host) {
							$host = R::dispense('host');
							$host->domain = $ahost->domain;
							R::store($host);
						}
					}

					$allhosts = R::findAll('host');

					foreach ($allhosts as $singlehost) {
						$user->sharedHost[$singlehost->id] = $singlehost;
					}

					R::store($user);
				}
?>
				<div class="alert alert-success">
					<p><strong>Erfolgreich eingeloggt!</strong><a href="index.php?p=dashboard" class="btn btn-primary btn-mini pull-right">Weiter &raquo;</a></p>

				</div>
<?php
			}
			else 
			{
				$error = true;
			}
		}
		
		if (!(isset($username) && isset($password)) || $error == true)
		{
			if ($error == true)
			{
?>
				<div class="alert alert-error">
					<strong>Achtung:</strong> Falscher Nutzername oder Passwort.
				</div>
<?php
			}
?>
				

				<input id="username" name="username" class="input-block-level" placeholder="Nutzername" type="text">
				<input id="password" name="password" class="input-block-level" placeholder="Passwort" type="password">

				<!-- Button -->
				<div class="control-group">
					<div class="controls">
						<button class="btn btn-primary button-loading" data-loading-text="Loading...">Einloggen</button>
					</div>
				</div>

<?php
		}
?>
			</fieldset>
		</form>
	</div>
	<div class="span3 hidden-phone"></div>
</div>
<?php
	}
	else {
		echo "ach du ...";
	}
?>
