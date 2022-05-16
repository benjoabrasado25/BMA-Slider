<?php

if( !class_exists('BMA_Slider_Post_Type')){
	class BMA_Slider_Post_Type{
		public function __construct(){
			add_action('init', array( $this, 'create_post_type' ));
			add_action('save_post', array($this, 'save_post'), 10);
			add_action('add_meta_boxes',  array($this, 'add_meta_boxes'));
			//add columns
			add_filter('manage_bma-slider_posts_columns', array($this, 'bma_slider_cpt_columns'));
			//add value
			add_action('manage_bma-slider_posts_custom_column', array($this, 'bma_slider_custom_columns'), 10, 2);
			add_filter('manage_edit-bma-slider_sortable_columns', array($this, 'bma_slider_sortable_columns'));
		}

		public function create_post_type(){
			register_post_type(
				'bma-slider',
				array(
					'label' => esc_html__('Slider', 'bma-slider'),
					'description' => esc_html__('Sliders', 'bma-slider'),
					'labels' => array(
						'name' => esc_html__('Sliders', 'bma-slider'),
						'singular_name' => esc_html__('Slider', 'bma-slider')
					),
					'public' => true,
					'supports' => array('title', 'editor', 'thumbnail'),
					'hierarchical' => false,
					'show_ui' => true,
					'show_in_menu' => false,
					'menu_position' => 5,
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'can_export' => true,
					'has_archive' => false,
					'exclude_from_search' => false,
					'publicly_queryable' => true,
					'show_in_rest' => true,
					'menu_icon' => 'dashicons-images-alt2',
					// 'register_meta_box_cb' => array($this, add_meta_boxes)
				)
			);
		}

		public function bma_slider_sortable_columns($columns){
			$columns['bma_slider_link_text'] = 'bma_slider_link_text';
			return $columns;
		}

		public function bma_slider_custom_columns($column, $post_id){
			switch($column){
				case 'bma_slider_link_text':
					echo esc_html(get_post_meta($post_id, 'bma_slider_link_text', true));
				break;
			}
			switch($column){
				case 'bma_slider_link_url':
					echo esc_url(get_post_meta($post_id, 'bma_slider_link_url', true));
				break;
			}
		}

		public function bma_slider_cpt_columns($columns){
			$columns['bma_slider_link_text'] = esc_html__('Link Text', 'bma-slider');
			$columns['bma_slider_link_url'] = esc_html__('Link Url', 'bma-slider');
			return $columns;
		}

		public function add_meta_boxes(){
			add_meta_box(
				'bma_slider_meta_box',
				esc_html__('Link Options', 'bma-slider'),
				array($this, 'add_inner_meta_boxes'),
				'bma-slider',
				'normal', //position, you can also use side
				'high', //priority
				array('foo'=>'bar') //passing arguments
			);
		}

		public function add_inner_meta_boxes($post, $test){
			//$test['args']['foo']
			require_once(BMA_SLIDER_PATH . 'views/bma-slider_metabox.php');
		}

		public function save_post( $post_id ){
			//stop if not admin
			// if( isset( $_POST['bma_slider_nonce'])){
			// 	//second value is name=
			// 	if(!wp_verify_nonce($_POST['bma_slider_nonce'], 'bma_slider_nonce')){
			// 		return;
			// 	}
			// }
			
			//stop auto saving
			if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
				return;
			}

			//check if you're in the right custom post type
			if(isset($_POST['post_type']) && $_POST['post_type'] === 'bma-slider'){
				// if capability is not true stop
				if(!current_user_can('edit_page', $post_id) && !current_user_can('edit_post', $post_id)){
					return;
				}
			}




			//saving the metadata
			if(isset( $_POST['action']) && $_POST['action'] === 'editpost'){
				$old_link_text = get_post_meta('$post_id', 'bma_slider_link_text', true);
				$new_link_text = sanitize_text_field($_POST['bma_slider_link_text']);
				$old_link_url = get_post_meta('$post_id', 'bma_slider_link_url', true);;
				$new_link_url = sanitize_text_field($_POST['bma_slider_link_url']);

				if(empty($new_link_text)){
					update_post_meta($post_id, 'bma_slider_link_text', esc_html__('Add Some Text', 'bma-slider'));
				}
				else{
				update_post_meta($post_id, 'bma_slider_link_text', $new_link_text, $old_link_text);
				}
				if(empty($new_link_text)){
					update_post_meta($post_id, 'bma_slider_link_url', '#add-some-url');
				}
				else{
					update_post_meta($post_id, 'bma_slider_link_url', $new_link_url, $old_link_url);
				}
			}
		}
	}	
}

