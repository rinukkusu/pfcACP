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
							<table class="table table-striped well">
								<tr>
									<th>#</th>
									<th>Adresse</th>
									<th>Erstellt am</th>
									<th>Actions</th>
								</tr>

<?php
		$servers = R::find('server', ' active = ? AND user_id = ? ', array( true, $current_user->id ));
		foreach ($servers as $server) {
?>
								<tr id="elm_<?php echo $server->id; ?>" class="<?php if ($response) echo "success"; else echo "error"; ?>">
									<td><?php echo $server->id; ?></td>
									<td><?php echo $server->name; ?></td>
									<td><?php echo $server->game->name; ?></td>
									<td><?php echo $server->port; ?></td>
									<td><?php echo $players; ?></td>
									<td><?php echo $ping; ?></td>
									<td>
										<a class="btn btn-mini btn-inverse" title="Details" href="">Del</a>							
									</td>
								</tr>
<?php
		}
?>

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