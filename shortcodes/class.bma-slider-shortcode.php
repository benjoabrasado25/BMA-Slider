<?php
if(!class_exists('BMA_Shortcode')){
	class BMA_Slider_Shortcode{
		public function __construct(){
			add_shortcode('bma_slider', array($this, 'add_shortcode'));
		}

		public function add_shortcode($atts = array(), $content = null, $tag = ''){
			$atts = array_change_key_case((array) $atts, CASE_LOWER);
			extract(shortcode_atts(
				array(
					'id'=>'',
					'orderby'=>'date'
				),
				$atts,
				$tag
			));

			if(!empty($id)){
				$id = array_map('absint', explode(',', $id));
			}
			ob_start();
			require(BMA_SLIDER_PATH. 'views/bma-slider_shortcode.php');
			wp_enqueue_script('bma-slider-main-jq');
			wp_enqueue_script('bma-slider-options-js');
			wp_enqueue_style('bma-slider-main-css');
			wp_enqueue_style('bma-slider-style-css');
			bma_slider_options();
			return ob_get_clean();
		}
	}
}
