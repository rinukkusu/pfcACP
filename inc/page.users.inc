<?php
	if (defined("loaded")) {
		if (isset($current_user) && $current_user->isadmin)
		{
?>

			<div class="row-fluid">
<?php
		require_once("menu.php");
?>
				
				<div class="span9 well">
					<fieldset>

						<!-- Form Name -->
						<legend>Benutzer</legend>
<?php
		$submitted = isset($_POST['submit']) ? true : false;
		$username = isset($_POST['username']) ? $_POST['username'] : NULL;
		$isadmin = isset($_POST['isadmin']) ? true : false;
		$mainhost = isset($_POST['mainhost']) ? $_POST['mainhost'] : 1;
		
		$error = false;

		if ($submitted)
		{
			if (strlen($username) == 0)
			{
				$error = true;
?>
						<div class="alert alert-error"><strong>Achtung: </strong>Kein Nutzername angegeben.</div>
<?php			
			}
			elseif (R::findOne('user', ' name = ? ', array( $username ))) {
				$error = true;
?>
						<div class="alert alert-error"><strong>Achtung: </strong>Benutzer existiert bereits.</div>
<?php
			}
			elseif (!array_key_exists($mainhost, $current_user->sharedHost)) {
				$error = true;
?>
						<div class="alert alert-error"><strong>Achtung: </strong>Keine Berechtigung für diese Domain.</div>
<?php				
			}
			else {
				$host = R::findOne('host', 'id = ?', array( $mainhost ));

				$newpass = generatepassword();
				$newuser = R::dispense('user');
				$newuser->name = $username;
				$newuser->isadmin = $isadmin;
				$newuser->active = true;
				$newuser->sharedHost[] = $host;
				$newuser->host = $host;
				$returnpass = addmailuser($username, $host->domain, encryptpassword($newpass));
				$newuser->password = $returnpass;
				$newuser->created_at = date("Y-m-d H:i:s");

				R::store($newuser);
?>
				<div class="alert alert-success"><strong>Erfolg: </strong>Neuer Benutzer wurde erfolgreich angelegt. <?php if ($newpass == $returnpass) { echo "Das Passwort lautet: <strong>$newpass</strong>"; } ?></div>
<?php
			}
		}
			
?>
						<div class="alert alert-error" id="errormsg" style="display: none;"></div>

						<!--<p><a class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Neuen Benutzer erstellen</a></p>-->
						<form action="index.php?p=users" method="POST">
							<table class="table table-striped well">
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Haupthost</th>
									<th>Admin</th>
									<th>Actions</th>
								</tr>

<?php
		$findarray = array();
		$findarray[] = true;
		foreach ($current_user->sharedHost as $host) {
			$findarray[] = $host->id;
		}

		$users = R::find('user', ' active = ? AND host_id IN ('.R::genSlots($current_user->sharedHost).')', $findarray);
		foreach ($users as $user) {
?>
								<tr id="elm_<?php echo $user->id; ?>" class="<?php if ($user->isadmin) echo "error"; ?>">
									<td id="elm_id_<?php echo $user->id; ?>"><?php echo $user->id; ?></td>
									<td id="elm_username_<?php echo $user->id; ?>"><?php echo $user->name; ?></td>
									<td id="elm_mainhost_<?php echo $user->id; ?>"><?php echo $user->host->domain; ?></td>
									<td id="elm_isadmin_<?php echo $user->id; ?>"><?php if ($user->isadmin) echo "<i class='icon-ok'></i>"; ?></td>
									<td>
										<a class="btn btn-mini btn-default" title="Bearbeiten" onclick="editElement('<?php echo $user->id; ?>');"><i class="icon-edit"></i></a>
										<a href="act/del_user.php?u=<?php echo $user->id; ?>" class="btn btn-mini btn-danger" title="L&ouml;schen"><i class="icon-trash icon-white"></i></a>
									</td>
								</tr>
								<tr class="warning" style="display: none;" id="edit_<?php echo $user->id; ?>">
									<td><?php echo $user->id; ?></td>
									<td>
										<input id="username<?php echo $user->id; ?>"class="form-control input-small" style="width: 90%; margin-bottom: 0; padding: 1px 6px;" value="<?php echo $user->name; ?>" type="text">
									</td>
									<td>
										<span id="mainhost<?php echo $user->id; ?>"><?php echo $user->host->domain; ?></span>
									</td>
									<td>
										<label class="checkbox">
      										<input id="isadmin<?php echo $user->id; ?>" type="checkbox" <?php if ($user->isadmin) echo "checked"; ?>> Admin
    									</label>
									</td>
									<td>
										<a class="btn btn-mini btn-success" title="Speichern" onclick="saveUser('<?php echo $user->id; ?>');"><i class="icon-ok-circle icon-white"></i></a>
										<a class="btn btn-mini btn-danger" title="Abbrechen" onclick="canceleditElement('<?php echo $user->id; ?>');"><i class="icon-ban-circle icon-white"></i></a>
									</td>
								</tr>
<?php
		}
?>
								<tr class="success">
									<td><i class="icon-arrow-right"></i></td>
									<td>
										<input id="username" name="username" class="form-control input-small" style="width: 90%;" <?php if ($error) echo "value='$username'"; else echo "placeholder='Nutzername'"; ?> type="text">
									</td>
									<td>
										<select id="mainhost" name="mainhost" class="form-control" style="width: 90%;">
											<?php
												foreach ($current_user->sharedHost as $host) {
													echo "<option value='$host->id'>$host->domain</option>";
												}
											?>
										</select>
									</td>
									<td>
										<label class="checkbox">
      										<input name="isadmin" type="checkbox"> Admin
    									</label>
									</td>
									<td>
										<input type="submit" name="submit" value="Erstellen" class="btn btn-small btn-info" title="Neuen Benutzer erstellen">
									</td>
								</tr>
							</table>
						</form>
					</fieldset>
				</div><!--/span-->
			</div><!--/row-->
			
<?php
		}
		else {
?>
			<div class="alert alert-error">
				<strong>Achtung:</strong> Sie sind nicht eingeloggt oder nicht dazu berechtigt, zu dieser Seite zu navigieren.
			</div>
<?php
		}
	}
	else {
		echo "ach du ...";
	}
?>
