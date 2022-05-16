<?php

if(!class_exists('BMA_Slider_Settings')){
	class BMA_Slider_Settings{
		public static $options;
		public function __construct(){
			self::$options = get_option('bma_slider_options');
			add_action('admin_init', array($this, 'admin_init'));
		}
		public function admin_init(){
			register_setting(
				'bma_slider_group',
				'bma_slider_options',
				array($this, 'bma_slider_validate')
			);
			add_settings_section(
				'bma_slider_main_section',
				esc_html__('How does this work?', 'bma-slider'),
				null,
				'bma_slider_page1' //you can add it to general too
			);

			add_settings_section(
				'bma_slider_second_section',
				esc_html__('Other Plugin Options', 'bma-slider'),
				null,
				'bma_slider_page2' //you can add it to general too
			);			

			add_settings_field(
				'bma_slider_shortcode',
				esc_html__('Shortcode', 'bma-slider'),
				array($this, 'bma_slider_shortcode_callback'),
				'bma_slider_page1',
				'bma_slider_main_section',
				array(
					'label_for' => 'bma_slider_shortcode'
				)				

			);

			add_settings_field(
				'bma_slider_title',
				esc_html__('Slider Title', 'bma-slider'),
				array($this, 'bma_slider_title_callback'),
				'bma_slider_page2',
				'bma_slider_second_section',
				array(
					'label_for' => 'bma_slider_title'
				)				
			);

			add_settings_field(
				'bma_slider_display_title',
				esc_html__('Display Title','bma-slider'),
				array($this, 'bma_slider_display_title_callback'),
				'bma_slider_page2',
				'bma_slider_second_section',
				array(
					'label_for' => 'bma_slider_display_title'
				)				
			);			


			add_settings_field(
				'bma_slider_bullets',
				esc_html__('Display Bullets','bma-slider'),
				array($this, 'bma_slider_bullets_callback'),
				'bma_slider_page2',
				'bma_slider_second_section',
				array(
					'label_for' => 'bma_slider_bullets'
				)				
			);			

			add_settings_field(
				'bma_slider_style',
				esc_html__('Style', 'bma-slider'),
				array($this, 'bma_slider_style_callback'),
				'bma_slider_page2',
				'bma_slider_second_section',
				array(
					'items'=> array(
						'style-1', 'style-2'
					),
					'label_for' => 'bma_slider_style'
				)
			);

			add_settings_field(
				'bma_slider_background_overlay',
				esc_html__('Background Overlay', 'bma-slider'),
				array($this, 'bma_slider_background_overlay_callback'),
				'bma_slider_page2',
				'bma_slider_second_section',
				array(
					'label_for' => 'bma_slider_background_overlay'
				)				
			);


			add_settings_field(
				'bma_slider_overlay_opacity',
				esc_html__('Overlay Opacity %', 'bma-slider'),
				array($this, 'bma_slider_overlay_opacity_callback'),
				'bma_slider_page2',
				'bma_slider_second_section',
				array(
					'label_for' => 'bma_slider_overlay_opacity'
				)				
			);


		}

		public function bma_slider_shortcode_callback(){
			?>
			<span><?php _e('Use the shortcode [bma_slider] to display.', 'bma-slider')?></span>
			<?php
		}
		public function bma_slider_title_callback($items){
			?>
				<input
				type="text"
				name="bma_slider_options[bma_slider_title]"
				id="bma_slider_title"
				value="<?php echo isset(self::$options['bma_slider_title']) ? esc_attr(self::$options['bma_slider_title']) : '' ?>"
				>
			<?php
		}
		public function bma_slider_overlay_opacity_callback($items){
			?>
				<input
				type="number"
				name="bma_slider_options[bma_slider_overlay_opacity]"
				id="bma_slider_overlay_opacity"
				value="<?php echo isset(self::$options['bma_slider_overlay_opacity']) ? esc_attr(self::$options['bma_slider_overlay_opacity']) : 0 ?>"
				min="0"
				max="100"
				>
			<?php
		}
		public function bma_slider_background_overlay_callback($items){
			?>
				<input
				type="text"
				name="bma_slider_options[bma_slider_background_overlay]"
				id="bma_slider_background_overlay"
				value="<?php echo isset(self::$options['bma_slider_background_overlay']) ? esc_attr(self::$options['bma_slider_background_overlay']) : '' ?>"
				data-default-color="#ffffff"
				class="my-color-field"
				>
			<?php
		}
		public function bma_slider_bullets_callback($items){
			?>
				<input
				type="checkbox"
				name="bma_slider_options[bma_slider_bullets]"
				id="bma_slider_bullets"
				value="1"
				<?php
					if(isset(self::$options['bma_slider_bullets'])){
						checked( '1' , self::$options['bma_slider_bullets'], true);
					}
				?>
				>
				<label for="bma_slider_bullets"><?php _e('Display bullets?', 'bma-slider')?></label>
			<?php
		}

		public function bma_slider_display_title_callback($items){
			?>
				<input
				type="checkbox"
				name="bma_slider_options[bma_slider_display_title]"
				id="bma_slider_display_title"
				value="1"
				<?php
					if(isset(self::$options['bma_slider_display_title'])){
						checked( '1' , self::$options['bma_slider_display_title'], true);
					}
				?>
				>
				<label for="bma_slider_display_title"><?php _e('Display Title?', 'bma-slider')?></label>
			<?php
		}

		public function bma_slider_style_callback($items){
			?>
				<select
					id="bma_slider_style"
					name="bma_slider_options[bma_slider_style]"
				>
					<?php
						foreach($items['items'] as $item):
					?>

					<option value="<?php echo esc_attr($item); ?>"
						<?php
							isset(self::$options['bma_slider_style']) ? selected($item, self::$options['bma_slider_style'], true) : '';
						?>
					>
						<?php echo esc_html(ucfirst($item)); ?>
					</option>

					<?php endforeach; ?>
				</select>
			<?php
		}

		public function bma_slider_validate($input){
			$new_input = array();
			foreach($input as $key => $value){
				switch($key){
					case 'bma_slider_title':
						if(empty($value)){
							add_settings_error("bma_slider_options", 'bma_slider_message', esc_html__('Please put some text on title', 'bma-slider'), 'error');
							settings_errors('bma_slider_options');
							$value = __('Please put some text', 'bma-slider');
						}
						$new_input[$key] = sanitize_text_field($value);
					break;
					default:
						$new_input[$key] = sanitize_text_field($value);
					break;
				}
			}
			return $new_input;
		}
	}	
}

