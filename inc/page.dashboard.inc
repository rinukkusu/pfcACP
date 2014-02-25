<?php
	if (defined("loaded")) {
		if (isset($current_user))
		{
?>

			<div class="row-fluid">
<?php
		require_once("menu.php");
?>
				
				<div class="span9 well">
					<fieldset>

						<!-- Form Name -->
						<legend>Meine Mailadressen</legend>

						<!--<p><a class="btn btn-small btn-info"><i class="icon-plus icon-white"></i> Neuen Benutzer erstellen</a></p>-->
						<form action="index.php?p=servers" method="POST">
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
								<h3>Weitere Mailadressen</h3>
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
											<a class="btn btn-mini btn-inverse" title="Details" href="">Delete</a>							
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