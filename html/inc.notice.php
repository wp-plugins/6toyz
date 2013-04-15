<?php
	$msg_notice = '';
	
	// Catégorie active ?
	$sql = 'SELECT * FROM '.CATEGORIES_TABLE.' WHERE enable = 1';
	$nb_cat_actives = count($wpdb->get_results($sql));
	if ($nb_cat_actives == 0) {
		$msg_notice = '<a href="admin.php?page=sixtoyz-categories">Aucune catégorie n\'est activée</a>.';
	}
	// Catégorie liée ?
	else {
		$sql = 'SELECT * FROM '.CATEGORIES_TABLE.' WHERE wp_categorie > 0';
		$nb_cat_liees = count($wpdb->get_results($sql));
		if (count($wpdb->get_results($sql)) == 0)
			$msg_notice = '<a href="admin.php?page=sixtoyz-categories">Aucune catégorie n\'est liée à Wordpress</a>.';
		elseif ($nb_cat_liees < $nb_cat_actives)
			$msg_notice = '<a href="admin.php?page=sixtoyz-categories">Vous n\'avez pas lié toutes les catégories à Wordpress</a>.';
		// Produit créé ?
		else {
			$sql = 'SELECT * FROM '.PRODUCTS_TABLE.'';
			if (count($wpdb->get_results($sql)) == 0)
				$msg_notice = '<a href="admin.php?page=sixtoyz-importation">Aucun produit 6toyz n\'a été importé</a>.';
		}
	}
	
	
	
	
?>

<?php if ($msg_notice != ''):?>
	
		<div id="message" class="updated below-h2">
			<p>	Attention, le plugin 6toyz n'est pas encore configuré : 
				<?php echo $msg_notice; ?>
			</p>
		</div>
	
<?php endif;?>