<div class="wrap">
	<div id="icon-6toyz" class="icon32"></div>
    <h2>Configuration g&eacute;n&eacute;rale</h2>
	
	<?php include_once('inc.notice.php'); // Notice Plugin ?>

	<?php 
	// Message UPDATE 
	if ($msg != '' && $color == 'ok'): ?>
        <div id="message" class="updated below-h2">
			<p><?php echo $msg;?></p>
		</div>
    <?php endif; ?>

	<?php 
	// Message ERROR 
	if ($msg != '' && $color == 'error'): ?>
        <div id="message" class="error below-h2">
			<p><?php echo $msg;?></p>
		</div>
    <?php endif; ?>

    <form method="post">
		<table class="form-table">
			<tr>
				<td>
					<?php wp_editor($template, "template", array("textarea_name" => "template", "textarea_rows" => 20)) ?>
				</td>
				<td align="center" valign="top">
					<table width="300" border="0">
						<tr>
							<td><strong>[intro]</strong><br />
								Texte d'introduction
							</td>
							<?php if ($this->flux = 'http://www.sexeapiles.com/'):?>
							<td><strong>[video_title]</strong><br />
								Nom de la vidéo
							</td>
							<?php endif; ?>
						</tr>
						<tr>
							<td><strong>[nom]</strong><br />
								Nom du produit
							</td>
							<?php if ($this->flux = 'http://www.sexeapiles.com/'):?>
							<td><strong>[video_url]</strong><br />
								URL de la vidéo
							</td>
							<?php endif; ?>
						</tr>
						<tr>
							<td><strong>[description]</strong><br />
								Description du produit
							</td>
							<?php if ($this->flux = 'http://www.sexeapiles.com/'):?>
							<td><strong>[video_description]</strong><br />
								Description de la vidéo
							</td>
							<?php endif; ?>
						</tr>
						<tr>
							<td><strong>[categorie]</strong><br />
								Cat&eacute;gorie du produit
							</td>
							<?php if ($this->flux = 'http://www.sexeapiles.com/'):?>
							<td><strong>[video_author]</strong><br />
								Auteur de la vidéo
							</td>
							<?php endif; ?>
						</tr>
						<tr>
							<td><strong>[prix]</strong><br />
								Prix du produit
							</td>
							<?php if ($this->flux = 'http://www.sexeapiles.com/'):?>
							<td><strong>[video_flv]</strong><br />
								Fichier flv de la vidéo
							</td>
							<?php endif; ?>
						</tr>
						<tr>
							<td><strong>[image]</strong><br />
								URL de l'image du produit
							</td>
							<?php if ($this->flux = 'http://www.sexeapiles.com/'):?>
							<td><strong>[video_img]</strong><br />
								Photo de la vidéo
							</td>
							<?php endif; ?>
						</tr>
						<tr>
							<td><strong>[bouton_acheter]</strong><br />
								Bouton acheter
							</td>
						</tr>
						<tr>
							<td><strong>[url]</strong><br />
								Lien vers le produit
							</td>
						</tr>
						<tr>
							<td><strong>[url_add]</strong><br />
								Lien vers l'ajout au panier
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" value="Sauvegarder" class="button-primary" />
		</p>
    </form>
</div>