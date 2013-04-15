<div class="wrap">
	<div id="icon-6toyz" class="icon32"></div>
    <h2>Cat&eacute;gories</h2>
	
	<?php include_once('inc.notice.php'); // Notice Plugin ?>

	<?php 
	// Message UPDATE 
	if ($message != '' && $color == 'ok'): ?>
        <div id="message" class="updated below-h2">
			<p><?php echo $message;?></p>
		</div>
    <?php endif; ?>
	<?php 
	// Message DELETE 
	if ($message != '' && $color == 'error'): ?>
        <div id="message" class="error settings-error below-h2">
			<p><?php echo $message;?></p>
		</div>
    <?php endif; ?>
	
	<table style="margin:15px 0">
		<tr>
			<td>
				<form method="post" action="">
					<input type="submit" value="Tout activer" class="button-primary" />
					<input type="hidden" name="categories_all_set" value="1" />
				</form>
			</td>
			<td>
				<form method="post" action="">
					<input type="submit" value="Tout d&eacute;sactiver" class="button-secondary" />
					<input type="hidden" name="categories_all_set" value="0" />
				</form>
			</td>
		</tr>
	</table>
	
    <form action="" method="post">

        <table class="widefat">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th>Cat&eacute;gorie du flux</th>     
					<?php
						$args = array(
						  'public'   => true,
						  '_builtin' => false,
						  'object_type' => array('category')
						); 
						
						$taxo_type=get_taxonomies($args,'object'); 
						
						if (count($taxo_type) == 0) {
					?>
							<th>Cat&eacute;gorie Wordpress</th>
					<?php 
						} else { ?>
							<th>Cat&eacute;gorie custom</th>
					<?php
						}
					?>
					<th>Activer</strong></th>
					<th>Ordre</th>
				</tr>
			</thead>
			<tbody>
            <?php
				$args = array(
					'type' => 'post',
					'child_of' => 0,
					'orderby' => 'name',
					'order' => 'ASC',
					'hide_empty' => false);

				$flux_categories = $this->getCategories();

				if (count($taxo_type) == 0)
					$categories = get_categories($args);
				else
					$categories = get_terms( get_option('custom_cat', ''), array( 'hide_empty' => 0 ) );
					
				$nb = 0;
				foreach ($flux_categories as &$flux_category):
					?>

					<tr<?php if ($flux_category->can_enable == false): $nb++; ?> style="border:1px solid black;"<?php elseif($flux_category->main_category_id>0): ?> class="subcat_<?php echo $flux_category->main_category_id ?>" style="display:none;"<?php endif; ?>>
						<th width="20"><?php echo $nb;?></th>
						<td<?php if ($flux_category->can_enable == false): ?> style="font-weight:bold;"<?php endif; ?>>
							<?php echo $flux_category->name; ?>
						</td>
						<td>
							<?php if ($flux_category->can_enable): ?>
								<?php if (count($taxo_type) == 0):?>
									<select name="wp-<?= $flux_category->id ?>">
										<option value="0"></option>
										<?php
										foreach ($categories as &$cat):
											if ($cat->cat_ID == $flux_category->wp_categorie):
												echo '<option value="' . $cat->cat_ID . '" selected="selected">' . $cat->cat_name . '</option>';
											else:
												echo '<option value="' . $cat->cat_ID . '" >' . $cat->cat_name . '</option>';
											endif;
										endforeach;
										?>
									</select>
								<?php else:?>
									<select name="wpcustom-<?= $flux_category->id ?>">
										<option value="0"></option>
										<?php
										foreach ($categories as &$cat):
											if ($cat->term_id == $flux_category->wp_categorie):
												echo '<option value="' . $cat->term_id . '" selected="selected">' . $cat->name . '</option>';
											else:
												echo '<option value="' . $cat->term_id . '" >' . $cat->name . '</option>';
											endif;
										endforeach;
										?>
									</select>
								<?php endif; ?>
							<?php endif; ?>
						</td>
						<td>
							<?php if ($flux_category->can_enable): ?>
								<input class="cat-<?php echo $flux_category->main_category_id ?>" type="checkbox" name="a-<?= $flux_category->id ?>" value="1"<?php if ($flux_category->enable == "1"): ?> checked="checked" <?php endif; ?>/>
							<?php else: ?>
							
								<?php
									// Test si les catégories à l'intérieur sont checkées :
									$sql_all = $wpdb->get_results('SELECT COUNT(*) as cpt FROM '. CATEGORIES_TABLE . ' WHERE main_category_id = '.$flux_category->id);
									$sql_enabled = $wpdb->get_results('SELECT COUNT(*) as cpt FROM '. CATEGORIES_TABLE . ' WHERE main_category_id = '.$flux_category->id.' AND enable = 1');
									
									if ($sql_all[0]->cpt == $sql_enabled[0]->cpt && $sql_all[0]->cpt > 0)
										$check = ' checked="checked" ';
									else
										$check = '';
								?>
								<input type="checkbox" <?php echo $check;?> onClick="checkall(<?php echo $flux_category->id ?>);" id="checkall_<?php echo $flux_category->id ?>"value="" />
							<?php endif; ?>
						</td>  
						<td>
							<?php if ($flux_category->can_enable == false): ?>
								<img src="<?php echo plugin_dir_url( __FILE__ );?>../img/arrow_down.png" alt="" class="down_button" id="cat_<?php echo $flux_category->id ?>" />
							<?php else: ?>
								&nbsp;
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
        </table>

        <p class="submit">
            <input type="submit" name="submit-categories" value="Sauvegarder" class="button-primary" />
        </p> 

    </form>
    <form method="post" action="">
        <p class="submit">
			<input name="autocreate" type="hidden" value="1" />
            <input type="submit" value="Cr&eacute;er / lier automatiquement les cat&eacute;gories activées" class="button-secondary" />
			
			<?php 	
				/* $args = array(
				  'public'   => true,
				  '_builtin' => false,
				  'object_type' => array('category')
				); 
				
				$taxo_type=get_taxonomies($args,'names'); 
				
				echo '<p><label>Utiliser la catégorie custom :</label>';
				echo '<select id="taxo_type">';
				foreach ($taxo_type as $taxo ) {
					echo '<option value="'. $taxo. '">'. $taxo. '</option>';
				}
				echo '</select></p>'; */
			?>
        </p>
    </form>
</div>   

<script type="text/javascript">
    function checkall(cat)
    {
        if(jQuery("#checkall_"+cat).is(':checked'))
        {
            jQuery(".cat-"+cat).attr("checked", true);
        }else{
            jQuery(".cat-"+cat).attr("checked", false);
        }
    }
    
    jQuery(function(){

        jQuery(".down_button").live("click", function(){ 
        
            id = jQuery(this).attr('id');
            
            jQuery(".sub"+id).fadeIn('slow');
            
            jQuery(this).removeClass("down_button");
            jQuery(this).addClass("up_button");
            
            src = jQuery(this).attr('src');
            src = src.replace('down','up');
            jQuery(this).attr('src', src);
        
        });  
        
        jQuery(".up_button").live("click", function(){ 
        
            id = jQuery(this).attr('id');
            
            jQuery(".sub"+id).fadeOut('slow');
            
            jQuery(this).removeClass("up_button");
            jQuery(this).addClass("down_button");            
            
            src = jQuery(this).attr('src');
            src = src.replace('up','down');
            jQuery(this).attr('src', src);        
        });          

    });    

</script>