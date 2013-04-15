<div class="wrap">
	<div id="icon-6toyz" class="icon32"></div>
    <h2>Mise à jour du flux de produits</h2>
	
	<?php include_once('inc.notice.php'); // Notice Plugin ?>
	
	
	<?php if ($step == 2): ?>
		<div id="message" class="updated below-h2">
			<p>L'actualisation s'est d&eacute;roul&eacute;e avec succ&egrave;s !</p>
		</div>
	<?php endif; ?>
	
	<p>Flux &agrave; utiliser: <a href="<?php echo $this->flux ?>"><?php echo $this->flux ?></a></p>

	<h3>Cat&eacute;gories &agrave; importer :</h3>

	<?php
	$categories = $this->getCategories(true);
	if (sizeof($categories) > 0):

		?>
		<table class="wp-list-table widefat">
			<?php
			foreach ($categories as $category):

				?>
				<tr>
					<td><?php echo $category->name ?></td>
				</tr>
		<?php endforeach; ?>
		</table>
	<?php else: ?>
		<p>Aucune catégorie n'est activée pour l'importation.</p>
	<?php endif; ?>


	<?php if (sizeof($categories) > 0): ?>
		<p>
			<a href="?page=sixtoyz-importation&step=1&noheader=1">
				<input type="button" value="Cliquez ici pour d&eacute;marrer l'actualisation du flux produits" class="button-primary" />
			</a>
		</p>
	<?php else: ?>
		<p>
			<a href="?page=sixtoyz-categories">
				<input type="button" value="Cliquez ici pour activer les catégories de produits à importer" class="button-primary" />
			</a>
		</p>
	<?php endif; ?>

</div>