
<!-- JS Color -->
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ );?>../js/jscolor/jscolor.js"></script>

<!-- Color Picker -->
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ );?>../js/colorpicker/css/colorpicker.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script src="<?php echo plugin_dir_url( __FILE__ );?>../js/colorpicker/js/colorpicker.js"></script>

<!-- Classy Gradient -->
<script src="<?php echo plugin_dir_url( __FILE__ );?>../js/classygradient/js/jquery.classygradient.js"></script>
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ );?>../js/classygradient/css/jquery.classygradient.css" />

<!-- FD Slider -->
<script src="<?php echo plugin_dir_url( __FILE__ );?>../js/fd-slider/js/fd-slider.min.js"></script>
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ );?>../js/fd-slider/css/fd-slider.css" />

<div class="wrap">
	<div id="icon-6toyz" class="icon32"></div>
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab<?php if (!isset($_GET['onglet']) || $_GET['onglet'] != 'widgets') echo ' nav-tab-active';?>" href="admin.php?page=sixtoyz-styles">
			Style des pages
		</a>
		<a class="nav-tab<?php if (isset($_GET['onglet']) && $_GET['onglet'] == 'widgets') echo ' nav-tab-active';?>" href="admin.php?page=sixtoyz-styles&onglet=widgets">
			Style des widgets
		</a>
	</h2>

	<?php include_once('inc.notice.php'); // Notice Plugin ?>
	
    <?php 
	// Message UPDATE 
	if ($_GET["updated"] == "true"): ?>
        <div id="message" class="updated below-h2">
			<p>Modifications enregistr&eacute;es avec succ&egrave;s !</p>
		</div>
    <?php endif; ?>

    <?php
	// Message UPDATE articles
    if ($_POST["post_all"] == "1"):
        $p = (int) post_all();
    ?>
        <div id="message" class="updated below-h2">
			<p><? echo $p ?> articles post&eacute;s avec succ&egrave;s !</p>
		</div>
        <?php
    endif;
    ?>

    <?php
	// Message REFRESH articles
    if ($_POST["refresh_post"] == "1"):
        refresh_post();
    ?>
        <div id="message" class="updated below-h2">
			<p>Articles actualis&eacute;s avec succ&egrave;s !</p>
		</div>
        <?php
    endif;
    ?>

    <form method="post" action="">
		<div<?php if (isset($_GET['onglet']) && $_GET['onglet'] == 'widgets') echo ' style="display:none";';?>>
		<h3>Style du bouton "Acheter"</h3>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="text_input">Texte du bouton :</label>
						</th>
						<td>
							<input name="text_input" type="text" id="text_input" value="<?php echo get_option('text_input', 'Acheter [nom]'); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="color_input">Couleur du bouton :</label>
						</th>
						<td>
							<input name="color_input" type="text" id="color_input" class="color {hash:true}" value="<?php echo get_option('color_input', '#FFFFFF'); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="bg_input">Background du bouton :</label>
						</th>
						<td>
							<div id="gradient_input"></div>
							<input type="hidden" id="code_input" name="code_input" value="<?php echo get_option('code_input', '0% #000000'); ?>" />
							<textarea style="display:none" id="css_input" name="css_input"><?php echo get_option('css_input', 'background:#00000'); ?></textarea>
						</td>                    
					</tr>
					<tr valign="top">
						<td colspan="2"><i>Les styles du bouton seront repris pour les bulles de promotions des widget.</i></td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="bg_input">Picto du bouton :</label>
						</th>
						<td>
							<select name="picto_input" id="picto_input">
								<option value="aucun"<?php if (get_option('picto_input', 'aucun') == 'aucun'): ?> selected="selected"<?php endif; ?>>Aucun</option>
								<option value="loupe_clair"<?php if (get_option('picto_input', 'aucun') == 'loupe_clair'): ?> selected="selected"<?php endif; ?>>Loupe claire</option>
								<option value="loupe_fonce"<?php if (get_option('picto_input', 'aucun') == 'loupe_fonce'): ?> selected="selected"<?php endif; ?>>Loupe foncée</option>
								<option value="sac_clair"<?php if (get_option('picto_input', 'aucun') == 'sac_clair'): ?> selected="selected"<?php endif; ?>>Sac clair</option>
								<option value="sac_fonce"<?php if (get_option('picto_input', 'aucun') == 'sac_fonce'): ?> selected="selected"<?php endif; ?>>Sac foncé</option>
								<option value="plus_clair"<?php if (get_option('picto_input', 'aucun') == 'plus_clair'): ?> selected="selected"<?php endif; ?>>Plus clair</option>
								<option value="plus_fonce"<?php if (get_option('picto_input', 'aucun') == 'plus_fonce'): ?> selected="selected"<?php endif; ?>>Plus foncé</option>
								<option value="caddie_clair"<?php if (get_option('picto_input', 'aucun') == 'caddie_clair'): ?> selected="selected"<?php endif; ?>>Caddie clair</option>
								<option value="caddie_fonce"<?php if (get_option('picto_input', 'aucun') == 'caddie_fonce'): ?> selected="selected"<?php endif; ?>>Caddie foncé</option>
							
							</select>
						</td>                    
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="bg_input">Arrondi du bouton :</label>
						</th>
						<td>
							<table>
								<tr>
									<td width="445" style="padding:0">
										<input name="radius_input" type="text" min="0" max="20" id="radius_input" value="<?php echo get_option('radius_input', '0'); ?>" />
										pixels
									</td>
								</tr>
							</table>
						</td>                    
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="height_input">Largeur du bouton :</label>
						</th>
						<td>
							<input name="width_input" type="text" id="width_input" value="<?php echo get_option('width_input', ""); ?>" /> pixels<br/>
							<i>Laissez vide pour une hauteur automatique.</i>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="height_input">Hauteur du bouton :</label>
						</th>
						<td>
							<input name="height_input" type="text" id="height_input" value="<?php echo get_option('height_input', ""); ?>" /> pixels<br/>
							<i>Laissez vide pour une hauteur automatique.</i>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		
		<div<?php if (!isset($_GET['onglet']) || $_GET['onglet'] != 'widgets') echo ' style="display:none";';?>>
			<h3>Promotions et Top ventes</h3>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="border1_wdt_list">Bordure ligne paire :</label>
						</th>
						<td>
							<input name="border1_wdt_list" type="text" id="border1_wdt_list" class="color {hash:true}" value="<?php echo get_option('border1_wdt_list', '#E7E7E7'); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="border2_wdt_list">Bordure ligne impaire :</label>
						</th>
						<td>
							<input name="border2_wdt_list" type="text" id="border2_wdt_list" class="color {hash:true}" value="<?php echo get_option('border2_wdt_list', '#FFFFFF'); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="bg1_wdt_list">Background ligne paire :</label>
						</th>
						<td>
							<input name="bg1_wdt_list" type="text" id="bg1_wdt_list" class="color {hash:true}" value="<?php echo get_option('bg1_wdt_list', '#E7E7E7'); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="bg2_wdt_list">Background ligne impaire :</label>
						</th>
						<td>
							<input name="bg2_wdt_list" type="text" id="bg2_wdt_list" class="color {hash:true}" value="<?php echo get_option('bg2_wdt_list', '#FFFFFF'); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="bg_input">Arrondi des lignes :</label>
						</th>
						<td>
							<table>
								<tr>
									<td width="445" style="padding:0">
										<input name="radius_bg_wdt_list" type="text" min="0" max="10" id="radius_bg_wdt_list" value="<?php echo get_option('radius_bg_wdt_list', '0'); ?>" />
										pixels
									</td>
								</tr>
							</table>
						</td>                    
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="bg_input">Taille des bordures des lignes :</label>
						</th>
						<td>
							<table>
								<tr>
									<td width="445" style="padding:0">
										<input name="border_wdt_list" type="text" min="0" max="10" id="border_wdt_list" value="<?php echo get_option('border_wdt_list', '4'); ?>" />
										pixels
									</td>
								</tr>
							</table>
						</td>                    
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="title_wdt_list">Couleur des titres :</label>
						</th>
						<td>
							<input name="title_wdt_list" type="text" id="title_wdt_list" class="color {hash:true}" value="<?php echo get_option('title_wdt_list', '#757575'); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="text_wdt_list">Couleur des textes :</label>
						</th>
						<td>
							<input name="text_wdt_list" type="text" id="text_wdt_list" class="color {hash:true}" value="<?php echo get_option('text_wdt_list', '#757575'); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="price_wdt_list">Couleur des prix :</label>
						</th>
						<td>
							<input name="price_wdt_list" type="text" id="price_wdt_list" class="color {hash:true}" value="<?php echo get_option('price_wdt_list', '#FFFFFF'); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="bg_input">Background des prix :</label>
						</th>
						<td>
							<div id="gradient_price_wdt_list"></div>
							<input type="hidden" id="code_price_wdt_list" name="code_price_wdt_list" value="<?php echo get_option('code_price_wdt_list', '0% #404040'); ?>" />
							<textarea style="display:none" id="css_price_wdt_list" name="css_price_wdt_list"><?php echo get_option('css_price_wdt_list', 'background:#404040'); ?></textarea>
						</td>                    
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="bg_input">Arrondi des prix :</label>
						</th>
						<td>
							<table>
								<tr>
									<td width="445" style="padding:0">
										<input name="radius_price_wdt_list" type="text" min="0" max="20" id="radius_price_wdt_list" value="<?php echo get_option('radius_price_wdt_list', '0'); ?>" />
										pixels
									</td>
								</tr>
							</table>
						</td>                    
					</tr>
				</tbody>
			</table>
		</div>
		<p class="submit">
			<input type="submit" value="Sauvegarder" class="button-primary" />               
		</p>
		
		<p>A noter que certains éléments de votre widget comme la taille de la police ou la largeur de la box widgets sont gérés par la CSS de votre thème Wordpress. Vous pourrez les changer en vous rendant dans votre page permettant d'éditer style.css</p>
    </form>
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
			var $ = jQuery;
			var gradient_input = $("#gradient_input");
			var gradient_price_wdt_list = $("#gradient_price_wdt_list");
		
			gradient_input.ClassyGradient({
				gradient: '<?php echo get_option('code_input', '0% #000000'); ?>',
				width: 445,
				onChange:function(code, css) {
					$("#code_input").val(code);
					$("#css_input").val(css);
				}
			});
			gradient_price_wdt_list.ClassyGradient({
				gradient: '<?php echo get_option('code_price_wdt_list', '0% #000000'); ?>',
				width: 445,
				onChange:function(code, css) {
					$("#code_price_wdt_list").val(code);
					$("#css_price_wdt_list").val(css);
				}
			});
			
			fdSlider.createSlider({
				inp:document.getElementById("radius_input"),
				step:"1", 
				min:0,
				max:20,
				animation:"jump"
			});
			
			fdSlider.createSlider({
				inp:document.getElementById("border_wdt_list"),
				step:"1", 
				min:0,
				max:20,
				animation:"jump"
			});
			
			fdSlider.createSlider({
				inp:document.getElementById("radius_price_wdt_list"),
				step:"1", 
				min:0,
				max:20,
				animation:"jump"
			});
			
			fdSlider.createSlider({
				inp:document.getElementById("radius_bg_wdt_list"),
				step:"1", 
				min:0,
				max:10,
				animation:"jump"
			});
		});
	</script>
</div>        
