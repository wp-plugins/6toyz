<?php
	class TopventesSixtoys extends WP_Widget {
		
		/**
		 * Constructeur :
		 */
		function TopventesSixtoys() {
			$widget_ops = array('classname' => 'TopventesSixtoys', 'description' => 'Affiche le top ventes de votre site 6toyz');
			$this->WP_Widget('TopventesSixtoys', 'Top ventes 6toyz', $widget_ops);
		}

		/**
		 * Formulaire du widget :
		 */
		function form($instance) {
			global $wpdb;
			$instance = wp_parse_args((array) $instance, array('title' => 'Promotions', 'category' => '', 'nb_products' => 8, 'show_image' => true, 'size_image' => 'photo_80x120', 'length_description' => 0, 'show_description' => true, 'show_price' => true));
			$title				= $instance['title'];
			$category			= $instance['category'];
			$nb_products		= $instance['nb_products'];
			$size_image			= $instance['size_image'];
			$show_image			= $instance['show_image'];
			$show_description	= $instance['show_description'];
			$length_description	= $instance['length_description'];
			$show_price			= $instance['show_price'];
			
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
				<label for="<?php echo $this->get_field_id('size_image'); ?>">Taille des photos (en pixels) :</label>
				<select class="widefat" id="<?php echo $this->get_field_id('size_image'); ?>" name="<?php echo $this->get_field_name('size_image'); ?>">
					<option value="80x120"<?php if ($size_image == '80x120'): ?> selected="selected"<?php endif;?>>80 x 20</option>
					<option value="120x180"<?php if ($size_image == '120x180'): ?> selected="selected"<?php endif;?>>120 x 180</option>
					<option value="147x206"<?php if ($size_image == '147x206'): ?> selected="selected"<?php endif;?>>147 x 206</option>
					<option value="150x230"<?php if ($size_image == '150x230'): ?> selected="selected"<?php endif;?>>150 x 230</option>
					<option value="162x225"<?php if ($size_image == '162x225'): ?> selected="selected"<?php endif;?>>162 x 225</option>
				</select>
			</p>
			<p>
				<input class="checkbox" class="widefat" id="<?php echo $this->get_field_id('show_image'); ?>" name="<?php echo $this->get_field_name('show_image'); ?>" type="checkbox"<?php if ($show_image) echo ' checked="checked"'; ?> />
				<label for="<?php echo $this->get_field_id('show_image'); ?>">Afficher les photos</label>
				<br/>
				<input class="checkbox" id="<?php echo $this->get_field_id('show_description'); ?>" name="<?php echo $this->get_field_name('show_description'); ?>" type="checkbox"<?php if ($show_description) echo ' checked="checked"'; ?> />
				<label for="<?php echo $this->get_field_id('show_description'); ?>">Afficher les descriptions</label>
				<br/>
				<input class="checkbox" class="widefat" id="<?php echo $this->get_field_id('show_price'); ?>" name="<?php echo $this->get_field_name('show_price'); ?>" type="checkbox"<?php if ($show_price) echo ' checked="checked"'; ?> />
				<label for="<?php echo $this->get_field_id('show_price'); ?>">Afficher les prix</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('length_description'); ?>">Nombre de caractères de la description :</label>
				<input class="widefat" id="<?php echo $this->get_field_id('length_description'); ?>" name="<?php echo $this->get_field_name('length_description'); ?>" type="number" value="<?php echo attribute_escape($length_description); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('nb_products'); ?>">Nombre de produits affichés :</label>
				<input class="widefat" id="<?php echo $this->get_field_id('nb_products'); ?>" name="<?php echo $this->get_field_name('nb_products'); ?>" type="number" value="<?php echo attribute_escape($nb_products); ?>" />
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
			$instance['nb_products']		= $new_instance['nb_products'];
			$instance['size_image']			= $new_instance['size_image'];
			$instance['length_description']	= $new_instance['length_description'];
			
			if ($new_instance['show_image'] == 'on')
				$instance['show_image'] = true;
			else
				$instance['show_image'] = false;
				
			if ($new_instance['show_description'] == 'on')
				$instance['show_description'] = true;
			else
				$instance['show_description'] = false;
				
			if ($new_instance['show_price'] == 'on')
				$instance['show_price'] = true;
			else
				$instance['show_price'] = false;
				
			return $instance;
		}

		/**
		 * Template du widget :
		 */
		function widget($args, $instance) {
			// Si j'ai renseigné un flux, je peux afficher le widget :
			if (get_option('flux', '') != '') {
				extract($args, EXTR_SKIP);

				echo $before_widget;
				
				// Récup des infos :
				$title 				= empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
				$size_image			= $instance['size_image'];
				$show_image			= $instance['show_image'];
				$show_description	= $instance['show_description'];
				$show_price			= $instance['show_price'];
				$length_description	= $instance['length_description'];
				$category 			= $instance['category'];
				if ($category == '') $category = 'all';
				
				$nb_products		= $instance['nb_products'];
				if (!is_numeric($nb_products)) $nb_products = 8;

				// TITRE :
				if (!empty($title))
					echo $before_title . $title . $after_title;;
				
				// URL du top vente :
				if (get_option('flux') == 'http://www.sexeapiles.com/')
					$flux = 'http://boutique.sexeapiles.com/';
				else
					$flux = get_option('flux');
					
				// Version ?
				if (get_option('flux_version', 'A') != 'A')
					$version = '&sex='.get_option('flux_version');
				else
					$version = '';
					
				if ($category != 'all')
					$url = $flux.'/sitemap/products/output/xml/category/'.$category.'?order=topventes&limit='.$nb_products.$version;
				else
					$url = $flux.'/sitemap/products/output/xml/?order=topventes&limit='.$nb_products.$version;
					
				// Récup du contenu avec curl :
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				$datas = curl_exec($ch);
				curl_close($ch);

				if (!$datas)
					continue;

				$xml = new SimpleXMLElement($datas);
				
				$products = $xml->xpath('/products/product');
				
				echo '<ul class="topventes sixtoyz">';
				foreach ($products as $cpt => $product) {
					if ($nb_products > $cpt) {
					?>
					<li class="cpt<?php echo $cpt%2;?>">
						<a href="<?php echo $product->url;?>?nodisc=1&tracker=<?php echo get_option('tracker');?>" target="_blank" title="<?php echo $product->name;?>">
							<?php if ($show_image):
									switch ($size_image) {
										case '80x120':
										default:
											$img = $product->photo_80x120;
											$width = 80;
											$height = 120;
											break;
										case '120x180':
											$img = $product->photo_120x180;
											$width = 120;
											$height = 180;
											break;
										case '147x206':
											$img = $product->photo_147x206;
											$width = 147;
											$height = 206;
											break;
										case '150x230':
											$img = $product->photo_150x230;
											$width = 150;
											$height = 230;
											break;
										case '162x225':
											$img = $product->photo_162x225;
											$width = 162;
											$height = 225;
											break;
									}
							?>
									<div class="visuel">
										<img src="<?php echo $img;?>" width="<?php echo $width;?>" height="<?php echo $height;?>" alt="" />
										<?php if (is_numeric((int)$product->reduction) && (int)$product->reduction > 0):?>
										<span class="promo">-<?php echo $product->reduction;?>%</span>
										<?php endif;?>
									</div>
							<?php endif;?>
							
							<h4><?php echo $product->name;?></h4>
							
							<?php if ($show_description && strlen(trim(strip_tags($product->description))) > 0):?>
								<?php if (is_numeric($length_description) && $length_description > 0) $length = $length_description; else $length = 10000; ?>
								<p><?php if (strlen(trim(strip_tags($product->description))) > $length) echo substr(trim(strip_tags($product->description)), 0, $length).'...'; else echo trim(strip_tags($product->description)); ?></p>
							<?php endif;?>
							
							<?php if ($show_price):?>
								<span class="prix"><?php echo $product->price;?>&euro;</span>
							<?php endif; ?>
						</a>
					</li>
				<?php
					}
					else
						break;
				}
				echo '</ul>';

				echo $after_widget;
			}
		}
	}
	
	add_action('widgets_init', create_function('', 'return register_widget("TopventesSixtoys");'));
?>