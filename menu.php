<?php
	if (defined("loaded")) {
		$at = 'class="active"';

?>

<div class="span3">
	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			<li class="nav-header">Dashboard</li>

			<li <?php if ($page == 'dashboard') echo $at; ?>><a href="index.php?p=dashboard"><i class="icon-home"></i> Meine Mailadressen</a></li>
<?php
	if ($current_user->isadmin) {
?>

			<li class="nav-header">Konfiguration</li>
			<li <?php if ($page == 'users') echo $at; ?>><a href="index.php?p=users"><i class="icon-user"></i> Benutzer</a></li>
<?php
	}
?>

			<li class="nav-header">Misc</li>
			<li><a href="index.php?p=logout"><i class="icon-circle-arrow-left"></i> Ausloggen</a></li>
			<li class="divider"></li>
			<li class="muted"><span>CPU: <?php echo getcpu(); ?> %</span><span class="pull-right">RAM: <?php echo getmem(); ?> %</span></li>
		</ul>
	</div><!--/.well -->
</div><!--/span-->

<?php
	}
	else {
		echo "ach du ...";
	}
?>