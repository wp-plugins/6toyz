<?php

/*
  Plugin Name: 6Toyz
  Plugin URI: http://www.6toyz.fr
  Description: -
  Author: 6Toyz
  Version: 1.1.2
  Author URI: http://www.6toyz.fr/
 */

class sixtoyzPlug
{
	/**
     * Constructeur
     * @global type $wpdb
     * @return boolean 
     */
    function sixtoyzPlug() {
		global $wpdb;

		$this->categories = array();
		$this->flux = get_option('flux');

		$this->picture_sizes = array('80x120', '162x225', '147x206', '150x230', '120x180');
		
		define('PRODUCTS_TABLE', $wpdb->prefix . 'sixtoyz_products');
		define('CATEGORIES_TABLE', $wpdb->prefix . 'sixtoyz_categories');
		define('TEMPLATE_FILE', dirname(__FILE__) . '/config/template.html');
		define('TEMPLATE_FILE_VIDEO', dirname(__FILE__) . '/config/template_video.html');
		define('CSS_CUSTOM_FILE', dirname(__FILE__) . '/css/custom.css');
		define('BACKUP_DIR', dirname(__FILE__) . '/tmp/');
		define('PLUGIN_VERSION', (int) @file_get_contents(dirname(__FILE__) . '/tmp/VERSION'));
		define('PLUGIN_SETUP_DIR', dirname(__FILE__) . '/');
	}
	
    /**
     * Installation du plugin
     * @global type $wpdb
     * @return boolean 
     */
    function install() {
        global $wpdb;
		
		$sql = "CREATE TABLE IF NOT EXISTS `" . PRODUCTS_TABLE . "` (`id` INT NOT NULL, `id_post` INT NOT NULL, `id_product` INT NOT NULL, `name` VARCHAR(240) NOT NULL, `description` TEXT NOT NULL, `price` FLOAT NOT NULL, `price_old` FLOAT NOT NULL, `img_url` TEXT NOT NULL, `url` TEXT NOT NULL, `marque` VARCHAR(240) NOT NULL, `marque_url` VARCHAR(240) NOT NULL, `marque_slug` VARCHAR(240) NOT NULL, `id_categorie` INT NOT NULL, UNIQUE (`id`)) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
		$wpdb->query($sql);
	
        $sql = "ALTER TABLE `" . PRODUCTS_TABLE . "` ADD `video_title` VARCHAR(255) NOT NULL, ADD `video_url` TEXT NOT NULL, ADD `video_description` TEXT NOT NULL, ADD `video_author` VARCHAR(255) NOT NULL, ADD `video_vid` INT NOT NULL, ADD `video_vkey` VARCHAR(255) NOT NULL, ADD `video_flv` VARCHAR(255) NOT NULL, ADD `video_img` TEXT NOT NULL;";
        $wpdb->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `" . CATEGORIES_TABLE . "` (`id` int(11) NOT NULL auto_increment, `name` varchar(240) NOT NULL,`slug` varchar(240) NOT NULL, `wp_categorie` int(11) NOT NULL, `main_category_id` int(11) NOT NULL, `enable` tinyint(1) NOT NULL, `can_enable` tinyint(1) NOT NULL,  PRIMARY KEY  (`id`), UNIQUE KEY `name` (`name`) ) ENGINE=MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
        $wpdb->query($sql);

        return true;
    }

    /**
     * Désinstallation du plugin
     * @global type $wpdb
     * @return boolean 
     */
    function uninstall() {
        global $wpdb;

        $sql = "DROP TABLE `" . PRODUCTS_TABLE . "`, `" . CATEGORIES_TABLE . "`;";
        $wpdb->query($sql);

        delete_option('flux');

        return true;
    }
	
	function init() {
		if (!is_admin())
			wp_enqueue_script('jquery');
	}

	/**
     * Configuration du menu
     * @global type $flux
     */
	function page_configuration_menu() {
		// Si le flux est renseigné :
		if ($this->flux) {
			add_menu_page('6Toys - Configuration', '6toyz', 'administrator', 'sixtoyz-config', array(&$this, 'page_configuration'), plugin_dir_url( __FILE__ ).'img/favicon.png');
			add_submenu_page('sixtoyz-config', 'Configuration', 'Configuration', 'administrator', 'sixtoyz-config', array(&$this, 'page_configuration'));
			add_submenu_page('sixtoyz-config', 'Styles du frontend', 'Styles du frontend', 'administrator', 'sixtoyz-styles', array(&$this, 'page_styles'));
			add_submenu_page('sixtoyz-config', '6Toys - Cat&eacute;gories', 'Cat&eacute;gories', 'administrator', 'sixtoyz-categories', array(&$this, 'page_categories'));
			add_submenu_page('sixtoyz-config', '6Toyz - Mise à jour du flux', 'Mise à jour du flux', 'administrator', 'sixtoyz-importation', array(&$this, 'page_importation'));
			add_submenu_page('sixtoyz-config', '6Toyz - Articles', 'Articles', 'administrator', 'sixtoyz-articles', array(&$this, 'page_articles'));
			add_submenu_page('sixtoyz-config', '6Toyz - Template des articles', 'Template des articles', 'administrator', 'sixtoyz-template', array(&$this, 'page_template'));
			//add_submenu_page('sixtoyz-config', '', 'Espace affilié', 'administrator', 'sixtoyz-stats', array(&$this, 'page_stats'));
		}
		// Au départ, je n'affiche que ce menu :
		else
			add_menu_page('6Toys - Choix du flux', '6toyz', 'administrator', 'init', array(&$this, 'page_init'), plugin_dir_url( __FILE__ ).'img/favicon.png');
	}

	/**
     * Modification du <head>
     */
	function admin_register_head() {
		echo '<link rel="stylesheet" type="text/css" href="'.plugin_dir_url( __FILE__ ).'css/admin.css" />';
		echo '<script type="text/javascript" src="'.plugin_dir_url( __FILE__ ).'js/admin.js"></script>';
	}

	/**
     * Options du plugin 
     */
	function register_configuration() {
		register_setting('cmp', 'affiliate_id', 'intval');
		register_setting('cmp', 'affiliate_login');
		register_setting('cmp', 'affiliate_pwd');
		register_setting('cmp', 'tracker');
		register_setting('cmp', 'descriptions_max', 'intval');
		register_setting('cmp', 'intro');
		register_setting('cmp', 'new_post_status');
		register_setting('cmp', 'picture_size');
		register_setting('cmp', 'border', 'intval');
		register_setting('cmp', 'border_color');
		register_setting('cmp', 'promo_postend_html');
		register_setting('cmp', 'ppd', 'intval');
		register_setting('cmp', 'template_posts');
		register_setting('cmp', 'flux');
	}

	/**
     * Page : template
     */
	function page_template() {
        global $wpdb;
		
		if ($_POST['template']) {
			$template = stripslashes($_POST['template']);
			
			if ($this->flux != 'http://www.sexeapiles.com/')
				$result = file_put_contents(TEMPLATE_FILE, $template);
			else
				$result = file_put_contents(TEMPLATE_FILE_VIDEO, $template);
				
			if ($result === false) {
				$msg = "Une erreur est survenue lors de l'enregistrement de votre nouveau template.";
				
				if ($this->flux != 'http://www.sexeapiles.com/')
					$msg .= "<br />Veuillez vérifiez que le fichier " . TEMPLATE_FILE . " est accessible en lecture et écriture ( chmod 777 ).";
				else
					$msg .= "<br />Veuillez vérifiez que le fichier " . TEMPLATE_FILE_VIDEO . " est accessible en lecture et écriture ( chmod 777 ).";
				$color = "error";
			}
			else {
				$msg = "Modification effectuée avec succès !";
				$color = "ok";
			}
		}
		
		// Suivant le flux, je n'affiche pas le même template :

		if ($this->flux != 'http://www.sexeapiles.com/')
			$template = stripslashes(file_get_contents(TEMPLATE_FILE));
		else
			$template = stripslashes(file_get_contents(TEMPLATE_FILE_VIDEO));

		require_once('html/template.php');
	}

	/**
     * Page : categories :
     */
	function page_categories() {
		global $wpdb;
		
		// Sauvegarder les catégories cochées :
		if ($_POST["submit-categories"]) {
			if ($this->categories_submit($_POST)) {
				$message = 'Les catégories sélectionnées ont bien été activées.';
				$color = 'ok';
			}
			else {
				$message = 'Erreur lors de l\'activation, veuillez réessayer ultérieurement.';
				$color = 'error';
			}
		}	
		// Tout activer ou tout désactiver ?
		elseif ($_POST['categories_all_set'] == '0' or $_POST['categories_all_set'] == '1') {
			if ($this->categories_all($_POST['categories_all_set'])) {
				$message = 'Toutes les catégories ont bien été activées.';
				$color = 'ok';
			}
			else {
				$message = 'Erreur lors de l\'activation, veuillez réessayer ultérieurement.';
				$color = 'error';
			}
		}
		// Création auto des catégories activées
		elseif ($_POST["autocreate"] == "1") {
			$args = array(
			  'public'   => true,
			  '_builtin' => false,
			  'object_type' => array('category')
			); 
			
			$taxo_type=get_taxonomies($args,'object'); 
			
			if (count($taxo_type) == 0) {
				if ($this->categories_autocreate()) {
					$message = 'Les catégories ont bien été créées et liées automatiquement.';
					$color = 'ok';
				}
				else {
					$message = 'Erreur lors de l\'activation, veuillez réessayer ultérieurement.';
					$color = 'error';
				}
			}
			else {
				if ($this->categories_custom_autocreate()) {
					$message = 'Les catégories ont bien été créées et liées automatiquement.';
					$color = 'ok';
				}
				else {
					$message = 'Erreur lors de l\'activation, veuillez réessayer ultérieurement.';
					$color = 'error';
				}
			}
		}

		require_once('html/categories.php');

	}

	/**
     * Page : configuration
     */
	function page_configuration() {
		global $_GET, $_POST, $wpdb;
		
        $picture_sizes = $this->picture_sizes;
		
		if ($_POST) {
			foreach ($_POST as $k => $v)
				if ($k)
					update_option($k, stripslashes($v));
			
			$_GET["updated"] = true;
		}

		require_once('html/configuration.php');
	}

	/**
     * Page : styles
     */
	function page_styles() {
		global $_GET, $_POST, $wpdb;
		
        $picture_sizes = $this->picture_sizes;
		
		if ($_POST) {
			foreach ($_POST as $k => $v)
				if ($k)
					update_option($k, stripslashes($v));
			
			$_GET["updated"] = true;
			
			// -----------------------------------------
			// Sauvegarde du CSS dans le fichier :
			// -----------------------------------------
			
			// ONGLET "STYLE DES PAGES"
			// ____________________________________
				
				// Couleur Bouton :
				$css = 'a.bouton_acheter {color:'.$_POST['color_input'].'}'."\n\r";
					
				// Gradient Bouton :
				$css.= 'a.bouton_acheter span {'.$_POST['css_input']."\n";
				
				// Width Bouton :
				$width_input = get_option('width_input', '');
				if ($width_input != '')
					$css.= 'width:'.get_option('width_input').'px;'."\n";
				
				// Height Bouton :
				$height_input = get_option('height_input', '');
				if ($height_input != '')
					$css.= 'height:'.get_option('height_input').'px; line-height:'.get_option('height_input').'px;'."\n";
				
				// Radius Bouton :
				$css.= 'border-radius:'.get_option('radius_input', '0').'px}'."\n\r";
				
				// Picto Bouton :
				$background_image = get_option('picto_input', 'aucun');
				if ($background_image != 'aucun')
					$css.= 'a.bouton_acheter span:after {content:url('.plugin_dir_url( __FILE__ ).'img/pictos/bouton_acheter/'.$background_image.'.png)}'."\n\r";
			
			$css.= "\n\r\n\r";
			
			// ONGLET "STYLE DES WIDGETS"
			// ____________________________________
				
				// Bordure ligne paire :
				$css.= 'ul.sixtoyz li.cpt0 {border-color:'.$_POST['border1_wdt_list']."; \n";
				
				// Background ligne paire :
				$css.= 'background:'.$_POST['bg1_wdt_list']."}\n\r";
				
				// Bordure ligne impaire :
				$css.= 'ul.sixtoyz li.cpt1 {border-color:'.$_POST['border2_wdt_list']."; \n";
				
				// Background ligne impaire :
				$css.= 'background:'.$_POST['bg2_wdt_list']."}\n\r";
				
				// Radius ligne :
				$css.= 'ul.sixtoyz li {border-radius:'.$_POST['radius_bg_wdt_list']."px;\n";
				
				// Largeur ligne :
				$css.= 'border-width:'.$_POST['border_wdt_list']."px}\n\r";
				
				// Couleur Titres :
				$css.= 'ul.sixtoyz li h4 {color:'.$_POST['title_wdt_list'].'}'."\n\r";
				
				// Couleur Textes :
				$css.= 'ul.sixtoyz li p {color:'.$_POST['text_wdt_list'].'}'."\n\r";
				
				// Couleur Prix :
				$css.= 'ul.sixtoyz li .prix {color:'.$_POST['price_wdt_list']."; \n";
				
				// Gradient Prix :
				$css.= $_POST['css_price_wdt_list']."\n";
				
				// Radius Prix :
				$css.= 'border-radius:'.get_option('radius_price_wdt_list', '0').'px}'."\n\r";
				
				// Couleur bulles :
				$css.= 'ul.sixtoyz li .visuel .promo {color:'.$_POST['color_input']."; \n";
				
				// Gradient bulles :
				$css.= $_POST['css_input']."} \n\r";
				
			$result = file_put_contents(CSS_CUSTOM_FILE, $css);
		}

		require_once('html/styles.php');
	}

	/**
     * Page : importation
     */
	function page_importation() {
		global $wpdb;
		
		$step = $_GET["step"] ? (int) $_GET["step"] : 0;

		if ($step == 1) {
			$this->importation();
			wp_redirect("admin.php?page=sixtoyz-importation&step=2");
		}
		else
			require_once('html/importation.php');
	}

	/**
     * Page : articles
     */
	function page_articles() {
		global $wpdb;
		
		// Créer les articles :
		if ($_GET['post']) {
			$c = $this->post_all();
			if ($c > 0)
				$msg = "$c nouveaux articles ont été créés.";
			else
				$msg = "Aucun nouvel article n'a été créé.";
			$color = "ok";
		}

		// Actualiser les articles :
		if ($_GET['repost']) {
			$c = $this->repost_all();
			if ($c > 0)
				$msg = "$c articles ont été modifiés avec succès.";
			else
				$msg = "Aucun article n'a été modifié.";
			$color = "ok";
		}

		// Supprimer les articles :
		if ($_GET['remove_all']) {
			$posts = $wpdb->get_results("SELECT * FROM " . PRODUCTS_TABLE . " WHERE id_post>0");

			$d = 0;

			foreach ($posts as &$post) {
				wp_delete_post($post->id_post, true);
				$d++;
			}

			$wpdb->query("UPDATE " . PRODUCTS_TABLE . " SET id_post=0");

			if ($d > 0)
				$msg = "$d articles ont été supprimés.";
			else
				$msg = "Aucun article n'a été supprimé.";
			$color = "ok";
		}

		// Backup des articles :
		if ($_GET['backup']) {

			$filename = BACKUP_DIR . '/' . date('Ymd-His') . '.json';
			@touch($filename);

			if (file_exists($filename) && is_writable($filename)) {
				$result = $this->backup_all($filename);

				if ($result) {
					$msg = "Vos articles ont été sauvegardés dans le fichier $filename avec succès";
					$color = "ok";
				}
				else {
					$msg = "Une erreur est survenue lors de la sauvegarde de vos articles, vérifiez que le dossier " . BACKUP_DIR . " est bien accessible en lecture/écriture";
					$color = "error";
				}
			}
			else {
				$msg = "Impossible de démarrer la sauvegarde, vérifiez que le dossier " . BACKUP_DIR . " est bien accessible en lecture/écriture";
				$color = "error";
			}
		}

		// Affichage des backup :
		$backup_test = is_writeable(BACKUP_DIR);
		if ($backup_test) {
			$backups = scandir(BACKUP_DIR);
			unset($backups[1]);
			unset($backups[0]);

			foreach ($backups as $k => $backup) {
				$backup = explode('.', $backup);
				if (end($backup) != 'json')
					unset($backups[$k]);
			}
		}

		// Restaurer un backup :
		if ($_GET["restore"]) {
			$filename = $_GET["filename"];

			if (in_array($filename, $backups)) {
				$datas = file_get_contents(BACKUP_DIR . "/" . $filename);

				if ($datas) {
					$results = array('ok' => 0, 'nok' => 0);
					$posts = json_decode($datas, 1);

					foreach ($posts as $k => $post) {
						$current_post = $this->get_post($post['ID']);

						if (!$current_post)
							$result = $this->new_post($post['post_title'], $post['post_content'], $post['post_status'], $post['post_author'], $post['post_date'], null, $post['post_category']);
						else
							$result = $this->update_post($post['ID'], $post['post_title'], $post['post_content'], $post['post_status'], $post['post_author'], $post['post_date'], $post['post_category']);
						
						if ($result > 0)
							$results['ok']++;
						else
							$results['nok']++;
					}

					if (sizeof($posts) == $results['ok']) {
						$msg = "Restauration de la sauvegarde effectuée avec succès ! ({$results['ok']} importés)";
						$color = "ok";
					}
					else {
						$msg = "Certains articles n'ont pas été restaurés: {$results['nok']}/{$results['ok']}";
						$color = "error";
					}
				}
				else {
					$msg = "Erreur lors de la restauration, le fichier $filename est vide";
					$color = "error";
				}
			}
			else {
				$msg = "Erreur lors de la restauration, le fichier $filename n'existe pas";
				$color = "error";
			}
		}

		// MAJ du plugin :
		if ($_GET["upgrade"]) {
			$result = $this->upgrade();
			$msg = nl2br($result['msg']);
			$color = $result['color'];
			$upgrade = false;
		}
		// Ou check d'une nouvelle maj :
		else
			$upgrade = $this->check_upgrade();

		require_once('html/articles.php');
	}

	/**
	 * Page : init
     * Initialisation du plugin (première utilisation)
     */
	function page_init() {
		global $_GET, $_POST, $wpdb;

		// -------------------------------------------- //
		// STEP 1
		// -------------------------------------------- //
		if (!isset($_GET['step']) || $_GET['step'] == 1) {
			delete_option('affiliate_sites');
			update_option('flux_version', 'A');
			
			// Submit de l'ID affilié
			if ($_POST['submit_init']) {
				// Est-il au bon format ?
				if (isset($_POST['affiliate_login']) && strlen($_POST['affiliate_login']) > 0 && isset($_POST['affiliate_pwd']) && strlen($_POST['affiliate_pwd']) > 0) {
				
				
					$sites = json_decode(file_get_contents('http://affiliation.6toyz.fr/ws.php/websites?username='.$_POST['affiliate_login'].'&password='.$_POST['affiliate_pwd']));
					
					// Identifiant incorrect ?
					if (gettype($sites) != 'object') {
						delete_option('affiliate_sites');
						echo '<meta http-equiv="refresh" content="0;url=admin.php?page=init&error=pass" />';
						exit;
					}
					// Si c'est ok, je save les options :
					else {
						update_option('affiliate_login', $_POST['affiliate_login']);
						update_option('affiliate_pwd', $_POST['affiliate_pwd']);
						update_option('affiliate_sites', $sites);
					}
				}
				// Si on n'a pas bien renseigné, erreur :
				else {
					delete_option('affiliate_sites');
					echo '<meta http-equiv="refresh" content="0;url=admin.php?page=init&error=pass" />';
					exit;
				}

				echo '<meta http-equiv="refresh" content="0;url=admin.php?page=init&step=2" />';
				exit;
			}
		}
		// -------------------------------------------- //
		// STEP 2
		// -------------------------------------------- //
		elseif ($_GET['step'] == 2) {

			// Si j'ai un ID affilié, je change de flux :
			if (get_option('affiliate_sites', '') != '') {
				$sites = array();
				foreach (get_option('affiliate_sites', '') as $mb)
					if ($mb->webservice != '')
						$sites[] = array('name' => $mb->name, 'url' => $mb->url.'sitemap/products/output/csv', 'base_url' => $mb->url);
			}
			// Sinon je prends les flux 6toyz :
			else
				$sites = json_decode(file_get_contents('http://coquin-malin.ma2tdev.com/sites.json'), true);
		
			// Submit du flux :
			if (isset($_POST['flux'])) {
				// Si je suis sur CM, j'ai une step 3 :
				if ($_POST['flux'] == 'http://www.coquin-malin2.com/') {
					echo '<meta http-equiv="refresh" content="0;url=admin.php?page=init&step=3" />';
					exit;
				}
				else {
					update_option('flux', $_POST['flux']);
					
					// Récupération des catégories :
					$all_categories = file_get_contents($_POST['flux'] . "/sitemap/categories");
					
					if ($all_categories) {
						$all_categories = json_decode($all_categories, true);
						$this->explore_categories($all_categories);
					}
					
					echo '<meta http-equiv="refresh" content="0;url=admin.php?page=sixtoyz-categories" />'; // Redirect sur la page importation
					exit;
				}
			}
		}
		// -------------------------------------------- //
		// STEP 3
		// -------------------------------------------- //
		elseif ($_GET['step'] == 3) {
			// Submit du flux :
			if (isset($_POST['version'])) {
				// Flux :
				update_option('flux', 'http://www.coquin-malin.com/');
				//update_option('flux', 'http://pierro:pierro@sandbox.sexshop.st/');
				
				// Et version :
				update_option('flux_version', $_POST['version']);
				
				// Récupération des catégories :
				$all_categories = file_get_contents(get_option('flux') . "/sitemap/categories/sex/".$_POST['version']);
				
				if ($all_categories) {
					$all_categories = json_decode($all_categories, true);
					$this->explore_categories($all_categories);
				}
				
				echo '<meta http-equiv="refresh" content="0;url=admin.php?page=sixtoyz-categories" />'; // Redirect sur la page importation
				exit;
			
			}
		}
		
		require_once('html/init.php');
	}

	/**
     * Importation des produits des catégories actives
     * @global type $wpdb
     * @global type $categories
     * @global type $flux
     * @return boolean 
     */
    function importation() {
		global $wpdb;

		$this->categories = $this->getCategories(true);
		
		if (!$this->flux or sizeof($this->categories) == 0)
			return false;

		// ------------------------------------ //
		// PRODUITS OU VIDEOS ?
		// ------------------------------------ //
		
		// Produits :
		if ($this->flux != 'http://www.sexeapiles.com/') {
			foreach ($this->categories as $category) {
				$request_url = $this->flux . "/sitemap/products/output/xml/category/" . $category->slug;
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $request_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				$datas = curl_exec($ch);
				curl_close($ch);

				if (!$datas)
					continue;

				$xml = new SimpleXMLElement($datas);

				$products = $xml->xpath('/products/product');
				
				foreach ($products as $product) {
					$test = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) `" . PRODUCTS_TABLE . "` WHERE id=%s LIMIT 0,1", $product->ref));

					$object_vars = get_object_vars($product);

					foreach ($object_vars as $k => $v) {
						if (!is_array($v) && is_string($k))
							$product->$k = trim($v);
					}

					if ($test == 0)
						$sql = "INSERT INTO `" . PRODUCTS_TABLE . "` (id,id_product,name,description,price,price_old,img_url,url,marque,marque_url,marque_slug,id_categorie) VALUES('".addslashes(trim($product->ref))."','".addslashes(trim($product->id))."','".addslashes(trim($product->name))."','".addslashes(trim($product->description))."','".addslashes(trim($product->price))."','','".addslashes(trim($product->photo_150x230))."','".addslashes(trim($product->url))."','".addslashes(trim($product->marque))."','".addslashes(trim($product->marque_url))."','".addslashes(trim($product->marque_slug))."', '".addslashes(trim($category->id))."')";
					else
						$sql = "UPDATE `" . PRODUCTS_TABLE . "` SET price='$product->price' WHERE id='$product->ref' LIMIT 1;";
					$wpdb->query($sql);
				}
			}
		}
		// Vidéos :
		else {
			global $wpdb;

			$this->categories = $this->getCategories(true);

			if (!$this->flux or sizeof($this->categories) == 0)
				return false;

			$request_url = "http://www.sexeapiles.com/wp.php";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $request_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			$datas = curl_exec($ch);
			curl_close($ch);

			if (!$datas)
				continue;

			$xml = new SimpleXMLElement($datas);

			$products = $xml->xpath('/products/product');

			foreach ($products as $product) {
			
				// Récup de la catégorie :
				$category = $wpdb->get_results('SELECT * FROM '.CATEGORIES_TABLE.' WHERE name = "'.addslashes(trim($product->category)).'" ');

				$test = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) `" . PRODUCTS_TABLE . "` WHERE id=%s LIMIT 0,1", $product->id));

				if ($test == 0) {
$sql = "INSERT INTO `" . PRODUCTS_TABLE . "` 
    (id,
    id_product,
    name,
    description,
    price,
    price_old,
    img_url,
    url,
    marque,
    marque_url,
    marque_slug,
    id_categorie,
    video_title,
    video_url, 
    video_description,
    video_author,
    video_vid,
    video_vkey,
    video_flv, 
    video_img)
    VALUES('".
        addslashes(trim($product->ref))."','". // id
        addslashes(trim($product->id))."','". // id_produit
        addslashes(trim($product->name))."','". // name
        addslashes(trim($product->description))."','". // description
        addslashes(trim($product->price))."',". // price
        " '','". // price_old
        addslashes(trim($product->photo_150x230))."','". // img_url
        addslashes(trim($product->url))."', '". // url
        addslashes(trim($product->marque))."', '". //marque
        addslashes(trim($product->marque_url))."', '". // marque_url
        addslashes(trim($product->marque_slug))."','". // marque_slug
        addslashes(trim($category[0]->id))."', '". // id_categorie    
        addslashes(trim((string)$product->video->title[0]))."', '". // video_title
        addslashes(trim($product->video->link))."', '". // video_url
        addslashes(trim($product->video->description))."', '". // video_description
        addslashes(trim($product->video->author))."', '". // video_author
        addslashes(trim($product->video->vid))."', '". // video_vid
        addslashes(trim($product->video->vkey))."', '". // video_vkey
        addslashes(trim($product->video->flv))."', '". // video_flv
        addslashes(trim($product->video->img))."')"; // video_img

				}
				else
					$sql = "UPDATE `" . PRODUCTS_TABLE . "` SET price='$product->price' WHERE id='$product->id' LIMIT 1;";
					
				$wpdb->query($sql);
			}
		}

		$wpdb->flush();
		return true;
	
    }

    /**
     * Création d'un nouvel article
     * @param type $post_title Titre
     * @param type $post_content Contenu
     * @param type $post_status Statut
     * @param type $post_author Auteur
     * @param type $post_date Date de publication
     * @param type $tags_input Tags
     * @param type $post_category Catégories
     * @return type Id du nouveau post / 0 si echec
     */
    function new_post($post_title, $post_content, $post_status, $post_author, $post_date, $tags_input, $post_category) {
        $post = array();
		
        $post['post_title'] = $post_title;
        $post['post_content'] = $post_content;
        $post['post_status'] = $post_status;
        $post['post_author'] = $post_author;
        $post['post_date'] = $post_date;
        $post['tags_input'] = $tags_input;
        $post['post_category'] = $post_category;
		
		if (get_option('custom_post', '') != '')
			$post['post_type'] = get_option('custom_post', '');

        return wp_insert_post($post);
    }

    /**
     * Mise à jour d'un article
     * @param type $post_id Id de l'article
     * @param type $post_title Titre
     * @param type $post_content Contenu
     * @param type $post_status Statut
     * @param type $post_author Auteur
     * @param type $post_date Date de publication
     * @param type $post_category Catégories
     * @return type Id du post / 0 si echec
     */
    function update_post($post_id, $post_title, $post_content, $post_status, $post_author, $post_date, $post_category) {
        $post = array();

        $post['post_title'] = $post_title;
        $post['post_content'] = $post_content;
        $post['post_status'] = $post_status;
        $post['post_author'] = $post_author;
        $post['post_date'] = $post_date;
        $post['post_category'] = is_array($post_category) ? $post_category : array($post_category);
        $post['ID'] = $post_id;

        return wp_update_post($post);
    }

    /**
     * Programme tout les articles
     * @global type $wpdb
     * @return int 
     */
    function post_all() {
	
	global $wpdb;

        $total = 0;
        $produits = $wpdb->get_results("SELECT p.*, c.name AS category_name, c.wp_categorie FROM " . PRODUCTS_TABLE . " AS p INNER JOIN " . CATEGORIES_TABLE . " AS c ON c.id=p.id_categorie WHERE p.id_post=0 AND wp_categorie > 0");
		
	if ($this->flux != 'http://www.sexeapiles.com/')
		$template = file_get_contents(TEMPLATE_FILE);
	else
		$template = file_get_contents(TEMPLATE_FILE_VIDEO);
	
        $affiliate_id = get_option('affiliate_id');
        $tracker = get_option('tracker');
        $status = get_option('new_post_status');
        $ppd = get_option('ppd');
        $tags_produits = get_option('tags_produits');

        $intro_base = get_option('phase_intro');
        $descriptions_max = get_option('descriptions_max');

        $date = time();
        $p = 1;

        $picture_size = get_option('picture_size');

        $tags_base[] = "Achat [categorie]";
        $tags_base[] = "Achat [nom]";
        $tags_base[] = "[categorie] discount";
        $tags_base[] = "[nom] discount";
        $tags_base[] = "Boutique [categorie]";
        $tags_base[] = "Acheter [categorie]";
        $tags_base[] = "Acheter [nom]";

        $promo_postend = get_option('promo_postend');

        $promo_cm = null;
        if ($promo_postend == "1") {
            $promo_cm = get_option('promo_postend_html');
			$promo_cm = str_replace("[ref]", $affiliate_id, $promo_cm);
			$promo_cm = str_replace("[tracker]", $tracker, $promo_cm);
		}
	
        $replaces = array("nom", "description", "prix", "lien", "categorie", "url", "url_add", "image");
		$replaces_video = array('title', 'url', 'description', 'author', 'vid', 'vkey', 'flv', 'img');
		
        $promo_popunder = get_option('promo_popunder');
        $promo_popunder_url = get_option('promo_popunder_url');
        $promo_popunder_width = get_option('promo_popunder_width');
        $promo_popunder_height = get_option('promo_popunder_height');

        if ($promo_popunder == "1")
            $popunder = ' onClick="window.open(\'' . $promo_popunder_url . '\',\'\',\'width=' . $promo_popunder_width . ',height=' . $promo_popunder_height . ',left=\'+((screen.width-' . $promo_popunder_width . ')/2)+\',top=\'+((screen.height-' . $promo_popunder_height . ')/2)+\'\').blur(); window.focus();"';


        foreach ($produits as &$produit) {
            $intro = '[product_intro]' . $intro_base . '[/product_intro]';
            $tags = implode(", ", $tags_base);

            $nom = $produit->name;
            $prix = $produit->price;

            $content = "[product id=" . $produit->id . "]" . $template . $promo_cm . "[/product]";

            $description = $produit->description;

            $url = str_replace("\n", "", $produit->url . "?ref=$affiliate_id&pref=$tracker");
			
			// URL Add :
			$split_url = split("/",$url);
			$domaine = $split_url[2];
			$url_add = "http://$domaine/cart/bulkAdd?products=$produit->id_product&ref=$affiliate_id&pref=$tracker";

            $image = str_replace('150x230', $picture_size, $produit->img_url);

            if ($descriptions_max > 0) {
                if (strlen($description) >= $descriptions_max) {
                    $description = substr($description, 0, $descriptions_max);
                    $espace = strrpos($description, " ");
                    $description = substr($description, 0, $espace) . "...";
                }
            }

            $description = str_replace(" .", ".", $description);
            $description = str_replace("!", " !\n", $description);
            $description = str_replace("...", "\o/", $description);
            $description = str_replace("..", "\o/", $description);
            $description = str_replace(".", ".\n", $description);
            $description = str_replace("\o/", "...\n", $description);
            $description = str_replace("- ", "\n- ", $description);
            $description = str_replace("?", " ?\n", $description);

            $description = "[product_description]" . $description . "[/product_description]";

			$text_input = get_option('text_input', 'Acheter [nom]');
			
            $bouton = '[product_bouton_acheter]<span ' . $popunder . '>'.$text_input.'</span>[/product_bouton_acheter]';
            $content = str_replace("[bouton_acheter]", $bouton, $content);

            foreach ($replaces as $replace) {
                $intro = str_replace("[$replace]", $$replace, $intro);
                $description = str_replace("[$replace]", $$replace, $description);
                $content = str_replace("[$replace]", $$replace, $content);
                $tags = str_replace("[$replace]", $$replace, $tags);
                $bouton = str_replace("[$replace]", $$replace, $bouton);
            }

            foreach ($replaces_video as $replace) {
                $v = "video_$replace";
                $intro = str_replace("[video_$replace]", $produit->$v, $intro);
                $description = str_replace("[video_$replace]", $produit->$v, $description);
                $content = str_replace("[video_$replace]", $produit->$v, $content);
                $tags = str_replace("[video_$replace]", $produit->$v, $tags);
                $bouton = str_replace("[video_$replace]", $produit->$v, $bouton);
                $content = str_replace("[url_plugin]", plugin_dir_url( __FILE__ ), $content);
            }

            $content = str_replace("[intro]", $intro, $content);

            $new_post = $this->new_post($produit->name, $content, $status, 1, date("Y-m-d H:i:s", $date), ($tags_produits ? $tags : null), array($produit->wp_categorie));

	    add_post_meta($new_post, 'flv', $produit->video_flv, true);
	    add_post_meta($new_post, 'video_url', $produit->video_url, true);
   	    add_post_meta($new_post, 'product_url', $produit->url, true); 

            $wpdb->update(PRODUCTS_TABLE, array("id_post" => $new_post), array('id' => $produit->id), array('%d'), array('%d'));

            $total++;

            $p++;
            if ($p == $ppd) {
                $date = $date + (3600 * 24);
                $p = 0;
            }


            if ($total > 10)
                $wpdb->flush();
        }

        return $total;
    }

    /**
     * Sauvegarde les articles créés par le plugin
     * @global type $wpdb
     * @param type $filename Fichier de sortie
     * @return boolean Résultat ( 1:ok, 0:nok )
     */
    function backup_all($filename) {
        global $wpdb;

        if (!$filename or is_writable($filename) == false)
            return false;

        $backup = array();

        $produits = $wpdb->get_results("SELECT id, id_post FROM " . PRODUCTS_TABLE . " WHERE id_post>0");

        foreach ($produits as &$produit) {
            $post = get_post($produit->id_post, 'ARRAY_A');

            $backup[$produit->id] = $post;
        }

        $content = json_encode($backup);

        $r = file_put_contents($filename, $content);

        if ($r === false)
            return false;
        else
            return true;
    }

    /**
     * Repost les messages
     * @global type $wpdb
     * @global type $flux
     * @return int 
     */
    function repost_all() {
        global $wpdb;

        $total = 0;

        $produits = $wpdb->get_results("SELECT p.*, c.name AS category_name, c.wp_categorie FROM " . PRODUCTS_TABLE . " AS p INNER JOIN " . CATEGORIES_TABLE . " AS c ON c.id=p.id_categorie WHERE p.id_post>0 AND wp_categorie > 0");

		if ($this->flux != 'http://www.sexeapiles.com/')
			$template = file_get_contents(TEMPLATE_FILE);
		else
			$template = file_get_contents(TEMPLATE_FILE_VIDEO);

        $affiliate_id = get_option('affiliate_id');
        $tracker = get_option('tracker');
        $status = get_option('new_post_status');
        $tags_produits = get_option('tags_produits');

        $intro_base = get_option('phase_intro');
        $descriptions_max = get_option('descriptions_max');

        $picture_size = get_option('picture_size');

        $promo_postend = get_option('promo_postend');

        $promo_cm = null;
        if ($promo_postend == "1") {
            $promo_cm = get_option('promo_postend_html');
			$promo_cm = str_replace("[ref]", $affiliate_id, $promo_cm);
			$promo_cm = str_replace("[tracker]", $tracker, $promo_cm);
		}
	
        $replaces = array("nom", "description", "prix", "lien", "categorie", "url", "url_add", "image");

        $promo_popunder = get_option('promo_popunder');
        $promo_popunder_url = get_option('promo_popunder_url');
        $promo_popunder_width = get_option('promo_popunder_width');
        $promo_popunder_height = get_option('promo_popunder_height');

        if ($promo_popunder == "1")
            $popunder = ' onClick="window.open(\'' . $promo_popunder_url . '\',\'\',\'width=' . $promo_popunder_width . ',height=' . $promo_popunder_height . ',left=\'+((screen.width-' . $promo_popunder_width . ')/2)+\',top=\'+((screen.height-' . $promo_popunder_height . ')/2)+\'\').blur(); window.focus();"';
        
        foreach ($produits as &$produit) {
			/* echo '<pre>';
			var_dump($produit); */
			
            $post_old = get_post($produit->id_post, 'ARRAY_A');

            $url = str_replace("\n", "", $produit->url . "?ref=$affiliate_id&pref=$tracker");
			
			// URL Add :
			$split_url = split("/",$url);
			$domaine = $split_url[2];
			$url_add = "http://$domaine/cart/bulkAdd?products=$produit->id_product&ref=$affiliate_id&pref=$tracker";
			
            $image = str_replace('150x230', $picture_size, $produit->img_url);

            $intro = '[product_intro]' . $intro_base . '[/product_intro]';

            $nom = $produit->name;
            $prix = $produit->price;

            $old_content = $post_old['post_content'];
            $old_product = $this->recuperation($old_content, "[product id=" . $produit->id . "]", "[/product]");

            $content = "[product id=" . $produit->id . "]" . $template . $promo_cm . "[/product]";

            $description = $this->recuperation($post_old['post_content'], "[product_description]", "[/product_description]", $balises);

            $description = "[product_description]" . $description . "[/product_description]";

			$text_input = get_option('text_input', 'Acheter [nom]');
			
            $bouton = '[product_bouton_acheter]<span ' . $popunder . '>'.$text_input.'</span>[/product_bouton_acheter]';
            $content = str_replace("[bouton_acheter]", $bouton, $content);

            foreach ($replaces as $replace) {
                $intro = str_replace("[$replace]", $$replace, $intro);
                $description = str_replace("[$replace]", $$replace, $description);
                $content = str_replace("[$replace]", $$replace, $content);
                $bouton = str_replace("[$replace]", $$replace, $bouton);
            }

            $video = array('title', 'url', 'description', 'author', 'vid', 'vkey', 'flv', 'img');

            foreach ($video as $replace) {
                $v = "video_$replace";
                $intro = str_replace("[video_$replace]", $produit->$v, $intro);
                $description = str_replace("[video_$replace]", $produit->$v, $description);
                $content = str_replace("[video_$replace]", $produit->$v, $content);
                $tags = str_replace("[video_$replace]", $produit->$v, $tags);
                $bouton = str_replace("[video_$replace]", $produit->$v, $bouton);
                $content = str_replace("[url_plugin]", plugin_dir_url( __FILE__ ), $content);
            }

            $content = str_replace("[intro]", $intro, $content);

            if ($old_content)
                $new_content = str_replace($old_product, $content, $old_content);
            else
                $new_content = $content;
				
			
            $update_post = $this->update_post($produit->id_post, $post_old['post_title'], $new_content, $post_old['post_status'], $post_old['post_author'], $post_old['post_date'], $post_old['post_category']);
            #$new_post = $this->new_post($produit->name, $content, $status, 1, date("Y-m-d H:i:s", $date), ($tags_produits ? $tags : null), array($produit->wp_categorie));
            #$wpdb->update(VIDEOS_TABLE, array("id_post" => $new_post), array('id' => $produit->id), array('%d'), array('%d'));

	    $new_post = $produit->id_post;
            add_post_meta($new_post, 'flv', $produit->video_flv, true) || update_post_meta($new_post, 'flv', $produit->video_flv);
            add_post_meta($new_post, 'video_url', $produit->video_url, true) || update_post_meta($new_post, 'video_url', $produit->video_url);
            add_post_meta($new_post, 'product_url', $produit->url, true) || update_post_meta($new_post, 'product_url', $produit->url);

            $total++;
        }

        return $total;
    }

    /**
     * Importation d'une nouvelle catégorie
     * @global type $wpdb
     * @param type $name Nom
     * @param type $slug Slug
     * @param type $main_category_id Id de la catégorie mère
     * @param type $can_enable Activable?
     * @return type $id Id
     */
    function new_category($name, $slug, $main_category_id = null, $can_enable = true) {
        global $wpdb;

        $sql = "INSERT INTO `" . CATEGORIES_TABLE . "` (name, slug, can_enable, main_category_id) VALUES('{$name}','{$slug}', '{$can_enable}', '{$main_category_id}')";
        $wpdb->query($sql);

        return $wpdb->insert_id;
    }

    /**
     * Parcourt récursivement le tableau de catégories retourné par le webservice
     * @param type $categories Tableau de catégories
     * @param type $main_category_id Id de la catégorie mère
     */
    function explore_categories($categories, $main_category_id = null) {
        foreach ($categories as $category) {
            if (is_array($category['categories'])) {
                $new_category = $this->new_category($category['name'], $category['slug'], null, false);
                $this->explore_categories($category['categories'], $new_category);
            }
            else
                $this->new_category($category['name'], $category['slug'], $main_category_id, true);
        }
    }

    function categories($id_categorie) {
        global $wpdb;

        $result = array();
        $categories = $wpdb->get_results("SELECT wp_categorie FROM " . CATEGORIES_TABLE . " WHERE id = '$id_categorie' AND enable = 1");

        foreach ($categories as $categorie)
            $result[] = $categorie->wp_categorie;

        return $result;
    }
	
	/**
     * Active/désactive toutes les catégories
     * @global type $wpdb
     * @param type $activate 
     */
    function categories_all($activate) {
        global $wpdb;

        $wpdb->query("UPDATE " . CATEGORIES_TABLE . " SET enable=" . $activate);
		
		return true;
    }

    /**
     * Traite le formulaire de la page catégories
     * @global type $wpdb
     * @param type $datas
     * @return boolean 
     */
    function categories_submit($datas) {
        global $wpdb;

        $wpdb->query("UPDATE " . CATEGORIES_TABLE . " SET wp_categorie=0, enable=0");

        foreach ($datas as $key => $value) {
            $test = explode("wp-", $key);

            if (is_numeric($test[1]))
                $wpdb->update(CATEGORIES_TABLE, array('wp_categorie' => $value), array('id' => $test[1]), array('%d'), array('%d'));

            $test = explode("a-", $key);

            if (is_numeric($test[1]))
                $wpdb->update(CATEGORIES_TABLE, array('enable' => 1), array('id' => $test[1]), array('%d'), array('%d'));
        }

        return true;
    }

    /**
     * Récupère la liste des catégories
     * @global type $wpdb
     * @param type $enable Toutes/Actives/Désactivées
     * @return type 
     */
    function getCategories($enable = null) {
        global $wpdb;

        if ($enable)
            return $wpdb->get_results("SELECT * FROM " . CATEGORIES_TABLE . " WHERE enable = 1");
        else
            return $wpdb->get_results("SELECT * FROM " . CATEGORIES_TABLE);
    }

    /**
     * Récupère un objet catégorie
     * @global type $wpdb
     * @param type $id
     * @return boolean 
     */
    function getCategory($id) {
        global $wpdb;

        $result = $wpdb->get_results("SELECT * FROM " . c . " WHERE id='" . (int) $id . "' LIMIT 0,1");

        if (is_object($result[0]))
            return str_replace("\n", "", $result[0]->name);
        else
            return false;
    }

    function categories_autocreate() {
        global $wpdb;

        $categories = $this->getCategories();

        foreach ($categories as $category) {
            if ($category->enable == true and !$category->wp_categorie) {
                $test = get_cat_ID($category->name);

                if ($test > 0)
                    $new_id = $test;
                else
                    $new_id = wp_create_category($category->name);

                if ($new_id > 0)
                    $wpdb->query("UPDATE " . CATEGORIES_TABLE . " SET wp_categorie=" . $new_id . " WHERE id=" . $category->id);
            }
        }

        $wpdb->flush();
		
		return true;
    }

    function categories_custom_autocreate() {
        global $wpdb;

        $categories = $this->getCategories();

        foreach ($categories as $category) {
            if ($category->enable == true and !$category->wp_categorie) {
                //$test = get_cat_ID($category->name);
                $test = get_term($category->name, get_option('custom_cat', ''));
				
                if (!is_null($test)) {
                    $new_id = $test->term_id;
				}
                else {
                    $new_id = wp_insert_term($category->name, get_option('custom_cat', ''));
					
					$new_id = $new_id['term_id'];
				}
				
				//echo $category->name.' :' .$new_id.'<br>';
				
                if ($new_id > 0)
                    $wpdb->query("UPDATE " . CATEGORIES_TABLE . " SET wp_categorie=" . $new_id . " WHERE id=" . $category->id);
            }
        }

        $wpdb->flush();
		
		return true;
    }

	/**
     * Suppression des shortcodes des articles créés par le plugi
     * @param type $atts
     * @param type $content
     * @return type 
     */
    function short_product($atts, $content = null) {
        $vars = array('intro', 'name', 'description', 'category', 'price', 'image', 'bouton_acheter', 'url');

        foreach ($vars as $var) {
            $content = str_ireplace("[product_$var]", "", $content);
            $content = str_ireplace("[/product_$var]", "", $content);
        }

        return $content;
    }

	/**
     * Test l'existance d'une nouvelle version du plugin
     */
    function check_upgrade() {
        $new_version = file_get_contents("http://coquin-malin.ma2tdev.com/plugin6.txt");

        if ($new_version and $new_version > PLUGIN_VERSION)
            return $new_version;
        else
            return false;
    }

	/**
     * Mise à jour du plugin
     */
    function upgrade() {
        $result = array('msg' => null, 'color' => null);

        if ($new_version = $this->check_upgrade()) {
            $upgrade_url = "http://coquin-malin.ma2tdev.com/plugin.sap/$new_version.bin";
            $upgrade = @file_get_contents($upgrade_url);

            if ($upgrade) {
                $errors = array();

                $upgrade = gzuncompress($upgrade);
                $upgrade = base64_decode($upgrade);
                $upgrade = json_decode($upgrade, 1);

                $files = array_keys($upgrade);

                foreach ($files as $file) {
                    $file = PLUGIN_SETUP_DIR . $file;

                    if (is_writable($file) === false)
                        $errors[] = "$file n'est pas accessible en écriture";

                    $file_dir = dirname($file);
                    if (!is_dir($file_dir)) {
                        $r = mkdir($file_dir, 0777, true);
                        if ($r == false) {
                            $errors[] = "Erreur lors de la création du dossier $file_dir";
                            break;
                        }
                    }
                }

                if (sizeof($errors) > 0) {
                    $result['msg'] = "Erreur lors de la mise à jour :\n" . implode("\n", $errors);
                    $result['color'] = "red";
                    return $result;
                }

                foreach ($upgrade as $file => $content) {
                    $file = PLUGIN_SETUP_DIR . $file;
                    $content = base64_decode($content);

                    $r = file_put_contents($file, $content);

                    if ($r === false)
                        $errors[] = "Erreur lors de l'écriture du fichier $file";
                }

                if (sizeof($errors) > 0) {
                    $result['msg'] = "Erreur lors de la mise à jour :\n<br />" . implode("\n<br />", $errors);
                    $result['color'] = "red";
                    return $result;
                }
                else {
                    $result['msg'] = "La mise à jour s'est déroulée avec succès !";
                    $result['color'] = '#0C0';
                    return $result;
                }
            }
            else {
                $result['msg'] = "Erreur lors de la mise à jour : Impossible de télécharger la mise à jour:\n$upgrade_url";
                $result['color'] = "red";
                return $result;
            }
        }
        else {
            $result['msg'] = "Vous utilisez déjà la dernière version du plugin";
            $result['color'] = "black";
            return $result;
        }
    }

    /**
     * Récupération du contenu entre deux balises
     * @param type $html Code HTML
     * @param type $start Balise de départ
     * @param type $end Balise de fin
     * @param type $balises Retourner le résultat avec les balises
     * @return type 
     */
    function recuperation($html, $start, $end, $balises = true) {
        $debut = strpos($html, $start) + strlen($start);
        $fin = strpos($html, $end);
        $result = substr($html, $debut, $fin - $debut);
        if ($balises)
            return $start . $result . $end;
        else
            return $result;
    }

    /**
     * Redirection vers l'espace affilié 
     */
    function page_stats() {
		if (get_option('affiliate_login', '') != '' && get_option('affiliate_pwd', '') != '')
			echo '<script language="javascript" type="text/javascript">window.location.replace("http://affiliation.6toyz.fr/login?username='.get_option('affiliate_login').'&password='.get_option('affiliate_pwd').'");</script>';
		else
			echo '<script language="javascript" type="text/javascript">window.location.replace("http://www.6toyz.fr");</script>';
    }

	/**
     * Remplacement des balises dans le content (et résumé) des singles posts
     * @param type $content Content
     * @return type $content Content
     */
	function sixtoyz_content($content) {
		$content = preg_replace('/\[product_description\]/', '', $content); 
		$content = preg_replace('/\[\/product_description\]/', '', $content); 
		$content = preg_replace('/\[product_bouton_acheter\]/', '', $content); 
		$content = preg_replace('/\[\/product_bouton_acheter\]/', '', $content); 
		$content = preg_replace('/\[product(.+)\]/', '', $content); 
		$content = preg_replace('/\[\/product\]/', '', $content); 
		$content = preg_replace('/\[video_description\]/', '', $content); 
		$content = preg_replace('/\[\/video_description\]/', '', $content); 
		
		return $content;
	}

	/**
     * Ajoute les styles du plugin dans le frontend
     */
    function enqueue_scripts() {
        wp_register_style('frontend-style', plugins_url('css/frontend.css', __FILE__));
        wp_register_style('custom-style', plugins_url('css/custom.css', __FILE__));
		
        wp_enqueue_style('frontend-style');
        wp_enqueue_style('custom-style');
    }
}

// Création du plugin :
if (class_exists("sixtoyzPlug")) {
    $plug = new sixtoyzPlug();

	// Hooks admin :
	register_activation_hook(__FILE__,  array(&$plug, 'install'));
	register_deactivation_hook(__FILE__, array(&$plug, 'uninstall'));

	add_action('admin_menu', array(&$plug, 'page_configuration_menu'));
	add_action('admin_init', array(&$plug, 'register_configuration'));
	add_action('init', array(&$plug, 'init'));
	add_action('admin_head', array(&$plug, 'admin_register_head'));
	
	// Hooks frontend :
	add_action('wp_enqueue_scripts', array(&$plug, 'enqueue_scripts'));
	
	// Filtres :
	add_filter('the_content', array(&$plug, 'sixtoyz_content'));
	add_filter('the_excerpt', array(&$plug, 'sixtoyz_content'));
	
	// Widgets:
	require_once(dirname(__FILE__) . '/widgets/topventes.php');
	require_once(dirname(__FILE__) . '/widgets/promotions.php');
	require_once(dirname(__FILE__) . '/widgets/marques.php');
	
	
	// TESTS CUSTOM POSTS & TAXONOMY //
	/*
	add_action( 'init', 'create_post_type' );
	function create_post_type() {
		register_post_type( 'product1',
			array(
				'labels' => array(
					'name' => __( 'Products1' ),
					'singular_name' => __( 'Product1' )
				),
			'public' => true,
			'has_archive' => true,
			)
		);
		register_post_type( 'product2',
			array(
				'labels' => array(
					'name' => __( 'Products2' ),
					'singular_name' => __( 'Product2' )
				),
			'public' => true,
			'has_archive' => true,
			)
		);
	}
	
	add_action( 'init', 'people_init' );
	function people_init() {
		register_taxonomy(
			'custom_cat1',
			'category',
			array(
				'label' => __( 'Custom Cat 1' ),
				'rewrite' => array( 'slug' => 'cat1' ),
				'capabilities' => array(
					'assign_terms' => 'edit_guides',
					'edit_terms' => 'publish_guides'
				)
			)
		);
		register_taxonomy(
			'custom_cat2',
			'category',
			array(
				'label' => __( 'Custom Cat 2' ),
				'rewrite' => array( 'slug' => 'cat2' ),
				'capabilities' => array(
					'assign_terms' => 'edit_guides',
					'edit_terms' => 'publish_guides'
				)
			)
		);
	}
	*/
}

?>
