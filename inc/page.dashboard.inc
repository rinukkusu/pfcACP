<?php
	if (defined("loaded")) {
		if (isset($current_user))
		{
?>

			<div class="row-fluid">
<?php
		require_once("menu.php");
?>
				<!-- Modal -->
				<div id="newMailModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form action="index.php?p=dashboard" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">Neue Mailadresse hinzuf&uuml;gen</h3>
						</div>
						<div class="modal-body">
							<label>Neue Mailadresse:</label>
							<input type="text" name="newmailuser" /><div class="at-sign">@</div><select name="newmailhost"><?php foreach ($current_user->sharedHost as $host) {
																										echo "<option value=".$host->id.">".$host->domain."</option>";
																									} ?></select>
						</div>
						<div class="modal-footer">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Abbrechen</button>
							<button class="btn btn-primary" type="submit" name="submit_mail">Hinzuf&uuml;gen</button>
						</div>
					</form>
				</div>
				<div class="span9 well">
					<fieldset>

						<!-- Form Name -->
						<legend>Meine Mailadressen</legend>

						<?php
							// add new mail?
							$newmailuser = isset($_POST['newmailuser']) ? $_POST['newmailuser'] : NULL;
							$newmailhost = isset($_POST['newmailhost']) ? $_POST['newmailhost'] : 0;
							$submitted_mail = isset($_POST['submit_mail']) ? true : false;

							if ($submitted_mail) {
								if (strlen($newmailuser) > 0 && $newmailhost > 0) {
									$host = R::findOne('host', 'id = ?', array( $newmailhost ));
									$newmail = $newmailuser."@".$host->domain;

									if (!mailexists($newmail)) {
										addanothermail($current_user->name, $newmail);

										?><div class="alert alert-success"><strong>Erfolg: </strong>Neue Mail <strong><?php echo $newmail; ?></strong> erfolgreich angelegt.</div><?php
									}
									else {
										?><div class="alert alert-error"><strong>Achtung: </strong>Mail existiert bereits.</div><?php
									}
								}
								else {
									?><div class="alert alert-error"><strong>Achtung: </strong>Fehler beim Erstellen der Mail.</div><?php
								}
							}
						?>

						<!--<p><a class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Neuen Benutzer erstellen</a></p>-->
						<form action="index.php?p=dashboard" method="POST">
							<div class="span5">
								<h3>Daten</h3>
								<?php
									$accountdata = getmailaccountdata($current_user->name);
								?>
								<dl class="dl-horizontal">
									<dt>Nutzername</dt>
									<dd><?php echo $current_user->name; ?></dd>
								</dl>
								<dl class="dl-horizontal">
									<dt>Hauptmail</dt>
									<dd><?php echo $accountdata->mail; ?></dd>
								</dl>
								<dl class="dl-horizontal">
									<dt>Nutzbare Mailhosts</dt>
									<dd><?php foreach ($current_user->sharedHost as $host) {
										echo $host->domain."<br />";
									} ?></dd>
								</dl>
							</div>
							<div class="span7">
								<h3>Weitere Mailadressen <a href="#newMailModal" role="button" data-toggle="modal" class="btn btn-mini btn-info"><i class="icon-plus icon-white" /></i> Neu</a></h3>
								<table class="table table-striped well">
									<tr>
										<th>Adresse</th>
										<th>Actions</th>
									</tr>

	<?php
			$mails = getothermails($current_user->name);
			if (count($mails) > 0) {
				foreach ($mails as $mail) {
	?>
									<tr>
										<td><?php echo $mail['address']; ?></td>
										<td>
											<a class="btn btn-mini btn-danger" title="L&ouml;schen" href=""><i class="icon-trash icon-white"></i></a>							
										</td>
									</tr>
	<?php
				}
			}
	?>

								</table>
							</div>
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