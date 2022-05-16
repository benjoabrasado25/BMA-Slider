<?php
if(!function_exists('bma_placeholder_image')){
	function bma_placeholder_image(){
		return "<img src='". BMA_SLIDER_URL . "assets/images/default.jpg' class='img-fluid wp-post-image' />";
	}
}


if(!function_exists('bma_slider_options')){
	function bma_slider_options(){
		$show_bullets = isset(BMA_Slider_Settings::$options['bma_slider_bullets']) && BMA_Slider_Settings::$options['bma_slider_bullets'] == 1 ? true : false;

		wp_enqueue_script('bma-slider-options-js', BMA_SLIDER_URL.'vendor/flexslider/flexslider.js', array('jquery'), BMA_SLIDER_VERSION, true);

		wp_localize_script('bma-slider-options-js', 'SLIDER_OPTIONS', array(
			'controlNav'=> $show_bullets
		));

	}	
}
