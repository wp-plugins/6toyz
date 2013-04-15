<div class="wrap">
	<div id="icon-6toyz" class="icon32"></div>
	<h2>Bienvenue sur le plugin 6toyz</h2>

	<?php 
		// STEP 1 :
		if (!isset($_GET['step']) || $_GET['step'] == 1):
	?>
			<p>Pour débuter, merci de renseigner vos identifiants 6toyz afin de récupérer les flux de vos marques blanches :</p>
			<?php 
			// Message ERROR 
			if (isset($_GET["error"]) && $_GET["error"] == 'pass'): ?>
				<div id="message" class="error settings-error">
					<p>Identifiant ou mot de passe incorrect.</p>
				</div>
			<?php endif; ?>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="affiliate_login">Identifiant * : </label>
							</th>
							<td>
								<input type="text" name="affiliate_login" id="affiliate_login" value="<?php echo get_option('affiliate_login', '');?>" />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="affiliate_pwd">Mot de passe : </label>
							</th>
							<td>
								<input type="password" name="affiliate_pwd" id="affiliate_pwd" value="" />
							</td>
						</tr>
					</tbody>
				</table>
				
				<p class="submit">
					<input type="submit" name="submit_init" value="Continuer" class="button-primary" />
				</p>
			</form>
			<p>
				<a href="admin.php?page=init&amp;step=2" class="button-secondary">Passer cette étape</a>
			</p>
			
			<p><i>* Il s'agit de l'identifiant utilisé pour se connecter l'espace affilié de <a href="http://www.6toyz.fr/">6toyz.fr</a>, et non l'ID affilié.</i></p>
		
	<?php 
		// STEP 2 :
		elseif ($_GET['step'] == 2):
	?>
			<p>Sélectionnez le site sur lequel vous voulez traiter les données :</p>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="flux">Site : </label>
							</th>
							<td>
								<select name="flux" id="flux">
									<?php foreach($sites as $site): ?>
									<option value="<?php echo $site['base_url'] ?>"><?php echo $site['name'] ?></option>
									<?php endforeach; ?>
								</select>
							
							</td>
						</tr>
					</tbody>
				</table>
							
				<p class="submit">
					<input type="submit" name="submit_flux" value="Sélectionner" class="button-primary" />
				</p>
			</form>
			<p>
				<a href="admin.php?page=init" class="button-secondary">Retour à l'étape précédente</a>
			</p>
	<?php
		// STEP 3 :
		elseif ($_GET['step'] == 3):
	?>
			<p>Choisissez entre la version Hétéro ou Gay :</p>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="version_h">Version : </label>
							</th>
							<td>
								<input type="radio" value="H" name="version" id="version_h" checked="checked"> <label for="version_h">Hétéro</label><br/>
								<input type="radio" value="G" name="version" id="version_g"> <label for="version_g">Gay</label>
								
							</td>
						</tr>
					</tbody>
				</table>
							
				<p class="submit">
					<input type="submit" name="submit_flux" value="Sélectionner" class="button-primary" />
				</p>
			</form>
			<p>
				<a href="admin.php?page=init&step=2" class="button-secondary">Retour à l'étape précédente</a>
			</p>
	<?php endif;?>
</div>