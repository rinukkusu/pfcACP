<?php
	if (defined("loaded")) {
?>
	
<div class="row-fluid">
	<div class="span3 hidden-phone"></div>
	<div class="span6">
		<form class="form-horizontal well" action="index.php?p=setup" method="POST">
			<fieldset>

				<!-- Form Name -->
				<legend>Setup</legend>

<?php

		$mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;
		$password = isset($_POST['password']) ? $_POST['password'] : NULL;

		$id = R::findOne('user', ' name = ? ', array('admin'));
		if (isset($id))
		{
?>
			<div class="alert alert-error">
				<strong>Achtung:</strong> Setup wurde bereits ausgeführt.
			</div>
<?php
		}
		elseif (isset($password) && isset($mail))
		{
			// read out data
			$hosts = getavailablehosts();

			foreach ($hosts as $ahost) {
				$host = R::dispense('host');
				$host->domain = $ahost->domain;
				R::store($host);
			}

			$allhosts = R::findAll('host');


			$encryptedpw = encryptpassword($password);
			$user = R::dispense('user');
			$user->name = 'admin';
			$user->password = $encryptedpw;
			$user->mail = $mail;
			$user->isadmin = true;
			$user->created_at = date("Y-m-d H:i:s");

			$user->host = $allhosts[1];

			foreach ($allhosts as $singlehost) {
				$user->sharedHost[] = $singlehost;
			}

			$id = R::store($user);

			if ($id > 0)
			{
				
?>
			<div class="alert alert-success">
				<strong>Gl&uuml;ckwunsch:</strong> Der Adminaccount wurde erfolgreich erstellt - Sie können sich nun einloggen.
			</div>
<?php
			}
			else
			{
?>
			<div class="alert alert-error">
				<strong>Achtung: </strong>Der Adminaccount konnte nicht erstellt werden.
			</div>
<?php					
			}

		}
		else
		{
?>

				<div class="alert alert-warning">
					<strong>Achtung:</strong> Es wird ein Benutzer 'admin' angelegt, f&uuml;r den Sie im folgenden Abschnitt ein Passwort vergeben k&ouml;nnen. Dies ist nicht zur&uuml;cksetzbar.
				</div>

				<div class="control-group">
					<div class="control-label">
						<label>E-Mail</label>
					</div>
					<div class="controls">
						<input id="mail" name="mail" class="input-large" placeholder="" type="text">
					</div>
				</div>

				<div class="control-group">
					<div class="control-label">
						<label>Passwort</label>
					</div>
					<div class="controls">
						<input id="password" name="password" class="input-large" placeholder="" type="password">
					</div>
				</div>

				<!-- Button -->
				<div class="control-group">
					<div class="controls">
						<button class="btn btn-primary button-loading" data-loading-text="Loading...">Adminaccount erstellen</button>
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