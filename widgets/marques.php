<?php
	class MarquesSixtoys extends WP_Widget {
		
		/**
		 * Constructeur :
		 */
		function MarquesSixtoys() {
			$widget_ops = array('classname' => 'MarquesSixtoys', 'description' => 'Affiche les marques de vos produits 6toyz');
			$this->WP_Widget('MarquesSixtoys', 'Marques 6toyz', $widget_ops);
		}

		/**
		 * Formulaire du widget :
		 */
		function form($instance) {
			global $wpdb;
			$instance = wp_parse_args((array) $instance, array('title' => 'Marques', 'category' => '', 'nb_marques' => 8));
			$title				= $instance['title'];
			$category			= $instance['category'];
			$nb_marques			= $instance['nb_marques'];
			
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Titre :</label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>">Basé sur :</label>
				<select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
					<option value="all"<?php if ($category == 'all'): ?> selected="selected"<?php endif;?>>Tout le site</option>
					<optgroup label="La catégorie :">
					<?php 
						$categories = $wpdb->get_results("SELECT * FROM " . CATEGORIES_TABLE . " WHERE enable=1 ORDER BY name");
						foreach ($categories as $categorie): ?>
							<option value="<?php echo $categorie->slug ?>"<?php if (attribute_escape($category) == $categorie->slug): ?> selected="selected"<?php endif; ?>><?php echo $categorie->name; ?></option>
					<?php 
						endforeach; 
					?>
					</optgroup>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('nb_marques'); ?>">Nombre de marques affichées :</label>
				<input class="widefat" id="<?php echo $this->get_field_id('nb_marques'); ?>" name="<?php echo $this->get_field_name('nb_marques'); ?>" type="number" value="<?php echo attribute_escape($nb_marques); ?>" />
			</p>
			<?php
		}

		/**
		 * Update du widget :
		 */
		function update($new_instance, $old_instance) {

			$instance = $old_instance;
			$instance['title']				= $new_instance['title'];
			$instance['category']			= $new_instance['category'];
			$instance['nb_products']		= $new_instance['nb_marques'];
			
			return $instance;
		}

		/**
		 * Template du widget :
		 */
		function widget($args, $instance) {
			global $wpdb;
			// Si j'ai renseigné un flux, je peux afficher le widget :
			if (get_option('flux', '') != '') {
				extract($args, EXTR_SKIP);

				echo $before_widget;
				
				// Récup des infos :
				$title 				= empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
				$category 			= $instance['category'];
				if ($category == '') $category = 'all';
				
				$nb_marques			= $instance['nb_marques'];
				if (!is_numeric($nb_marques)) $nb_marques = 8;

				// TITRE :
				if (!empty($title))
					echo $before_title . $title . $after_title;;
				
				
				// Requete marques :
				$sql = '	SELECT marque, marque_url, marque_slug, COUNT(marque) as nb_marque 
							FROM '.PRODUCTS_TABLE.' p, '.CATEGORIES_TABLE.' c, '.$wpdb->posts.' a
							WHERE p.id_categorie = c.id 
							AND p.id_post = a.ID 
							AND p.marque != "" 
							AND c.enable = 1 
							AND a.post_status = "publish"
							AND p.marque IS NOT NULL 
							GROUP BY marque 
							ORDER BY nb_marque DESC 
							LIMIT '.$nb_marques;
				
				$marques = $wpdb->get_results($sql);
				
				echo '<ul class="marques_sixtoyz">';
				foreach ($marques as $cpt => $marque) {
					?>
					<li class="cpt<?php echo $cpt%2;?>">
						<a href="<?php echo $marque->marque_url;?>?nodisc=1&tracker=<?php echo get_option('tracker');?>" target="_blank" title="<?php echo $marque->marque;?>">
							<img src="http://www.coquin-malin.com/images/marques/<?php echo $marque->marque_slug;?>.jpg" alt="<?php echo $marque->marque;?>" />
						</a>
					</li>
				<?php
				}
				echo '</ul>';

				echo $after_widget;
			}
		}
	}
	
	add_action('widgets_init', create_function('', 'return register_widget("MarquesSixtoys");'));
?>