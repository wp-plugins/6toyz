<div class="wrap">
	<div id="icon-6toyz" class="icon32"></div>
    <h2>Articles</h2>
	
	<?php include_once('inc.notice.php'); // Notice Plugin ?>

    <?php if ($msg && $color == 'ok') : ?>
        <div id="message" class="updated below-h2">
			<p><?php echo $msg ?></p>
		</div>
    <?php endif; ?>
    <?php if ($msg && $color == 'error') : ?>
        <div id="message" class="error below-h2">
			<p><?php echo $msg ?></p>
		</div>
    <?php endif; ?>

    <table class="form-table" style="width:auto">
		<tbody>
			<tr>
				<th scope="row" style="width:500px;vertical-align:middle!important">
					Cr&eacute;er les articles pour les produits ajout&eacute;s :
				</th>
				<td valign="middle" style="width:60px">
					<a href="?page=sixtoyz-articles&post=1" id="post_all">
						<img src="<?php echo plugin_dir_url( __FILE__ );?>../img/create.png" alt="Poster" />
					</a>
				</td>
			</tr>
			<tr>
				<th scope="row" style="width:500px;vertical-align:middle!important">
					Supprimer tous les articles cr&eacute;&eacute;s par le plugin :
					<br />
					<i style="color:red;">Attention, cette action est irr&eacute;versible, pensez &agrave; effectuer une sauvegarde !</i>
				</th>
				<td style="vertical-align:middle!important">
					<a href="?page=sixtoyz-articles&remove_all=1">
						<img src="<?php echo plugin_dir_url( __FILE__ );?>../img/remove.png" alt="Supprimer" />
					</a>
				</td>
			</tr>        
			<tr>
				<th scope="row" style="width:500px;vertical-align:middle!important">
					Effectuer une sauvegarde des articles post&eacute;s :
					<?php
					if ($backup_test == false):

						?>
						<i style="font-color:red;">Attention: le dossier <?php echo BACKUP_DIR ?> n'est pas accessible en &eacute;criture</i>
						<?php
					endif;

					?>
				</th>
				<td>
					<?php if ($backup_test): ?>
						<a href="?page=sixtoyz-articles&backup=1">
							<img src="<?php echo plugin_dir_url( __FILE__ );?>../img/backup.png" alt="Sauvegarder" />
						</a>
					<?php else: ?>
						<img src="<?php echo plugin_dir_url( __FILE__ );?>../img/backup_grey.png" alt="Sauvegarder" />                
					<?php endif; ?>
				</td>            
			</tr>
			<?php if ($backup_test and sizeof($backups) > 0): ?>
				<tr>
					<th scope="row" style="width:400px;vertical-align:middle!important">
						Restaurer une sauvegarde :<br />
						<form name="restore_frm" action="" method="get">

							<select name="filename">
								<option value=""></option>
								<?php foreach ($backups as $filename): ?>
									<?php
									$f = explode('-', $filename);
									$year = substr($f[0], 0, 4);
									$month = substr($f[0], 4, 2);
									$day = substr($f[0], 6, 2);
									$hour = substr($f[1], 0, 2);
									$minutes = substr($f[1], 2, 2);
									$seconds = substr($f[1], 4, 2);

									?>
									<option value="<?php echo $filename ?>">Sauvegarde du <?php echo "$day/$month/$year &agrave; $hour:$minutes:$seconds" ?></option>
								<?php endforeach; ?>
							</select>    
							<input type="hidden" name="restore" value="1" />
							<input type="hidden" name="page" value="sixtoyz-articles" />
						</form>                
					</th>
					<td style="vertical-align:middle!important">
						<a href="#" onClick="document.forms['restore_frm'].submit();">
							<img src="<?php echo plugin_dir_url( __FILE__ );?>../img/restore.png" alt="Restaurer" />
						</a>
					</td>            
				</tr>        
			<?php endif; ?>
			<tr>
				<th scope="row" style="width:400px;vertical-align:middle!important">
					<p>Actualiser les articles d&eacute;j&agrave; post&eacute;s :</p>
					<p><i>Vous avez effectu&eacute; une modification sur le template des articles, chang&eacute; de tracker ou d'id affili&eacute; ?</i>
						<br />
						<i>Note: Seul le contenu suivant sera conserv&eacute; :</i></p>
					<ul>
						<li>- Avant la balise [product id=XXX]</li>
						<li>- Après la balise [/product]</li>
						<li>- Entre [product_description] et [/product_description]</li>
					</ul>
					<p>
						<i style="color:red;">Attention &agrave; bien effectuer une sauvegarde avant d'effectuer cette action!</i></p>
				</th>
				<td valign="top">
					<a href="?page=sixtoyz-articles&repost=1">
						<img src="<?php echo plugin_dir_url( __FILE__ );?>../img/update.png" alt="Reposter" />
					</a>
				</td>            
			</tr>
			<!--
			<tr>
				<th scope="row" style="width:400px;vertical-align:middle!important">
					Mettre &agrave; jour le flux produits :
				</th>
				<td>
					<a href="?page=sixtoyz-importation">
						<img src="<?php echo plugin_dir_url( __FILE__ );?>../img/sync.png" alt="Synchroniser" />
					</a>
				</td>
			</tr>

			<?php if ($upgrade): ?>
				<tr>
					<th scope="row" style="width:400px;vertical-align:middle!important">
						<strong style="color:orange;">Une nouvelle mise &agrave; jours est disponible !
					</th>
					<td>
						<a href="?page=sixtoyz-articles&upgrade=1" title="Mettre à jour">
							<img src="<?php echo plugin_dir_url( __FILE__ );?>../img/upgrade.png" alt="Mettre à jour" />
						</a>
					</td>
				</tr>        
			<?php endif; ?>
			-->
		</tbody>
    </table>

</div>