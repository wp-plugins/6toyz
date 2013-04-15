<div class="wrap">
	<div id="icon-6toyz" class="icon32"></div>
    <h2>Configuration g&eacute;n&eacute;rale</h2>

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
	
		<h3>Affiliation</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="affiliate_id">Votre num&eacute;ro d'affili&eacute; :</label>
					</th>
					<td>
						<input name="affiliate_id" id="affiliate_id" type="text" value="<?php echo get_option('affiliate_id', ""); ?>" />
					</th>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tracker">Votre tracker :</label>
					</th>
					<td>
						<input name="tracker" type="text" id="tracker" value="<?php echo get_option('tracker', ""); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
		
		<?php 	
			$args = array(
			  'public'   => true,
			  '_builtin' => false,
			  'object_type' => array('category')
			); 
			
			$taxo_type=get_taxonomies($args,'object'); 
			
			$args=array(
				'public'   => true,
				'_builtin' => false
			); 
			$post_types=get_post_types($args,'object'); 
			
			if (count($taxo_type) > 0 && count($post_types) > 0) {
		?>
		<h3>Champs Custom</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="custom_cat">Importer les catégories dans (Custom Taxonomy) :</label>
					</th>
					<td>
						
						<?php
							echo '<select id="custom_cat" name="custom_cat">';
							foreach ($taxo_type as $taxo ) {
								echo '<option value="'. $taxo->query_var. '"';
								
								if (get_option('custom_cat', '') == $taxo->query_var)
									echo ' selected ';
								
								echo '>'. $taxo->labels->name. '</option>';
							}
							echo '</select>';
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="custom_cat">Importer les articles dans (Custom Post type) :</label>
					</th>
					<td>
						
						<?php
							echo '<select id="custom_post" name="custom_post">';
							echo '<option value="post">Post</option>';
							foreach ($post_types as $post_type ) {
								echo '<option value="'. $post_type->query_var. '"';
								
								if (get_option('custom_post', '') == $post_type->query_var)
									echo ' selected ';
								
								echo '>'. $post_type->labels->name. '</option>';
							}
							echo '</select>';
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
		
		<h3>Articles</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="ppd">Nombre de produits par jour :</label>
					</th>
					<td>
						<input name="ppd" type="text" id="ppd" value="<?php echo get_option('ppd', 10); ?>" size="6" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="new_post_status">Status des articles :</label>
					</th>
					<td>
						<select name="new_post_status" id="new_post_status">
							<option value="draft"<?php if (get_option('new_post_status', "draft") == "draft"): ?> selected="selected"<?php endif; ?>>Brouillon</option>
							<option value="publish"<?php if (get_option('new_post_status', "draft") == "publish"): ?> selected="selected"<?php endif; ?>>Publi&eacute;</option>
							<option value="pending"<?php if (get_option('new_post_status', "draft") == "pending"): ?> selected="selected"<?php endif; ?>>En attente</option>                            
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="phase_intro">Phrase d'introduction (laissez &agrave; 0 pour ne pas limiter la description) :</label>
					</th>
					<td>
						<textarea name="phase_intro" id="phase_intro" cols="80" rows="6"><?php echo get_option('phase_intro', "[nom] est un [categorie] disponible sur notre boutique sexy de [categorie]"); ?></textarea></td>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tags_produits">Activer les tags :</label>
					</th>
					<td>
						<select name="tags_produits" id="tags_produits">
							<option value="1"<?php if (get_option('tags_produits', "1") == "1"): ?> selected="selected"<?php endif; ?>>Oui</option>
							<option value="0"<?php if (get_option('tags_produits', "1") == "0"): ?> selected="selected"<?php endif; ?>>Non</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="descriptions_max">Longueur descriptions (laissez à 0 pour ne pas limiter la description) :</label>
					</th>
					<td>
						<input name="descriptions_max" type="text" id="descriptions_max" value="<?php echo get_option('descriptions_max', 0); ?>" size="6" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="picture_size">Taille des photos :</label>
					</th>
					<td>
						<select name="picture_size" id="picture_size">
							<?php foreach ($picture_sizes as $size): ?>
								<option value="<? echo $size ?>"<?php if (get_option('picture_size', "400x450") == $size): ?> selected="selected"<?php endif; ?>><? echo $size ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		
		<h3>Publicit&eacute;s</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="promo_postend">Banni&egrave;re en fin d'article :</label>
					</th>
					<td>
						<select name="promo_postend" id="promo_postend">
							<option value="1"<?php if (get_option('promo_postend', "1") == "1"): ?> selected="selected"<?php endif; ?>>Oui</option>
							<option value="0"<?php if (get_option('promo_postend', "1") == "0"): ?> selected="selected"<?php endif; ?>>Non</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="promo_postend_html">Code HTML banni&egrave;re :</label>
					</th>
					<td>
						<textarea name="promo_postend_html" id="promo_postend_html" cols="80" rows="6"><? echo get_option('promo_postend_html', htmlentities('<iframe src="http://ads.6toyz.fr/?a=[ref]&tracker=[tracker]&type=1&height=60&width=468" width="468" height="60"  class="iframe_6toyz" frameborder="0" scrolling="no"></iframe>')); ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" value="Sauvegarder" class="button-primary" />               
		</p>
    </form>
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
			var $ = jQuery;
			var gradient_input = $("#gradient_input");
		
			gradient_input.ClassyGradient({
				gradient: '<?php echo get_option('code_input', '0% #000000'); ?>',
				width: 445,
				onChange:function(code, css) {
					$("#code_input").val(code);
					$("#css_input").val(css);
				}
			});
			
			fdSlider.createSlider({
				inp:document.getElementById("radius_input"),
				step:"1", 
				min:0,
				max:20,
				animation:"jump"
			});
		
		});
	</script>
</div>        
