<?php

/**
* Plugin Name: BMA Slider
* Plugin URI: facebook.com/benjo.abrasado
* Description: My very own slider
* Version: 1.0
* Requires at least: 5.6
* Author: Benjo Abrasado
* Author URI: facebook.com/benjo.abrasado
* License: GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: bma-slider
* Domain Path: /languages
*/

if(!defined('ABSPATH')){
	die('stop that!');
	exit;
}

if(!class_exists(' BMA_Slider')){
	class BMA_Slider{
		public function __construct(){
			// excecuted when intiating the class object
			$this->define_constants();
			$this->laod_textdomain();

			require_once(BMA_SLIDER_PATH.'functions/functions.php');

			add_action('admin_menu', array($this, 'add_menu'));

			require_once( BMA_SLIDER_PATH . '/post-types/class.bma-slider-cpt.php' );
			$BMA_Slider_Post_Type = new BMA_Slider_Post_Type();
			require_once( BMA_SLIDER_PATH . '/class.bma-slider-settings.php' );
			$BMA_Slider_Settings  = new BMA_Slider_Settings();
			require_once( BMA_SLIDER_PATH . '/shortcodes/class.bma-slider-shortcode.php' );
			$BMA_Slider_Shortcode = new BMA_Slider_Shortcode();

			add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 999);
			add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'), 999);
			//color picker
			add_action( 'admin_enqueue_scripts', array($this, 'bma_enqueue_color_picker') );


		}

		public function define_constants(){
			define('BMA_SLIDER_PATH', plugin_dir_path(__FILE__));
			define('BMA_SLIDER_URL', plugin_dir_url(__FILE__));
			define('BMA_SLIDER_VERSION', '1.0.0');
		}

		public static function activate(){
			//resetting 'rewrite_rules' in wp_options
			update_option('rewrite_rules', '');
			unregister_post_type('bma-slider');
		}

		public static function deactivate(){
			flush_rewrite_rules();
		}

		public static function uninstall(){
			delete_option('bma_slider_options');
			$posts = get_posts(
				array(
					'post_type'=>'bma-slider',
					'number_posts' => -1,
					'post_status' => 'any'
				)
			);

			foreach($posts as $post){
				wp_delete_post($post->ID, true);
			}
		}

		public static function laod_textdomain(){
			load_plugin_textdomain(
				'bm-slider',
				false,
				dirname(plugin_basename(__FILE__)).'/languages/'
			);
		}

		public function add_menu(){
			add_menu_page(
				__('BMA Slider Options', 'bma-slider'),
				'BMA Slider',
				'manage_options',
				'bma_slider_admin',
				array($this, 'bma_slider_settings_page'),
				'dashicons-images-alt2'
			);

			add_submenu_page(
				'bma_slider_admin',
				__('Manage Slides', 'bma-slider'),
				__('Manage Slides', 'bma-slider'),
				'manage_options',
				'edit.php?post_type=bma-slider',
				null,
				null
			);

			add_submenu_page(
				'bma_slider_admin',
				__('Add New Slide','bma-slider'),
				__('Add New Slide', 'bma-slider'),
				'manage_options',
				'post-new.php?post_type=bma-slider',
				null,
				null
			);


		}

		public function bma_slider_settings_page(){
			if(!current_user_can('manage_options')){
				return;
			}
			if(isset($_GET['settings-updated'])){
				add_settings_error("bma_slider_options", 'bma_slider_message', __('Settings Saved!', 'bma-slider'), 'success');
			}
			settings_errors('bma_slider_options');
			require(BMA_SLIDER_PATH. 'views/settings-page.php');
		}

		public function register_scripts(){


			wp_register_style('bma-slider-style-css', BMA_SLIDER_URL.'assets/css/style.css');
			wp_enqueue_style('bma-slider-style-css');
			wp_register_style('bma-slider-main-css', BMA_SLIDER_URL.'vendor/flexslider/flexslider.css');
			wp_enqueue_style('bma-slider-main-css');
			wp_register_script('bma-slider-main-jq', BMA_SLIDER_URL.'vendor/flexslider/jquery.flexslider-min.js', array('jquery'), BMA_SLIDER_VERSION, true);
			wp_register_script('bma-slider-options-js', BMA_SLIDER_URL.'vendor/flexslider/flexslider.js', array('jquery'), BMA_SLIDER_VERSION, true);
		}

		public function register_admin_scripts(){
			global $typenow;
			if($typenow == 'bma-slider'){
				wp_register_style('bma-slider-admin-css', BMA_SLIDER_URL.'assets/css/admin.css');
				wp_enqueue_style('bma-slider-admin-css');
			}
		}
		public function bma_enqueue_color_picker( $hook_suffix ) {
		    // first check that $hook_suffix is appropriate for your admin page
		    wp_enqueue_style( 'wp-color-picker' );
		    wp_enqueue_script( 'my-script-handle', BMA_SLIDER_URL.'assets/js/script.js', array( 'wp-color-picker' ), BMA_SLIDER_VERSION, true );
			// wp_register_script('bma-slider-main-script', BMA_SLIDER_URL.'assets/js/script.js', array('jquery'), BMA_SLIDER_VERSION, true);
		    // wp_enqueue_style( 'bma-slider-main-script' );


		}
	}
}
if( class_exists('BMA_Slider')){
	register_activation_hook(__FILE__, array( 'BMA_Slider', 'activate' ));
	register_deactivation_hook(__FILE__, array( 'BMA_Slider', 'deactivate' ));
	register_uninstall_hook(__FILE__, array( 'BMA_Slider', 'uninstall' ));

	//automatically calling the class method
	$bma_slider = new BMA_Slider();
}