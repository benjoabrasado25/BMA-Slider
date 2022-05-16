<?php
	$meta = get_post_meta($post->ID);
	$link_text = get_post_meta($post->ID, 'bma_slider_link_text', true);
	$link_url = get_post_meta($post->ID, 'bma_slider_link_url', true);
	$meta = get_post_meta($post->ID);
?>
<input type="hidden" name="bma_slider_nonce" value="<?php wp_create_nonce('bma_slider_nonce'); ?>">
<table class="form-table bma-slider-metabox"> 
    <tr>
        <th>
            <label for="bma_slider_link_text"><?php esc_html_e('Link text', 'bma-slider'); ?></label>
        </th>
        <td>
            <input 
                type="text" 
                name="bma_slider_link_text" 
                id="bma_slider_link_text" 
                class="regular-text link-text"
                value="<?php echo ( isset($link_text) ) ? esc_html($link_text) : ''; ?>"
                required
            >
        </td>
    </tr>
    <tr>
        <th>
            <label for="bma_slider_link_url"><?php esc_html_e('Link URL', 'bma-slider'); ?></label>
        </th>
        <td>
            <input 
                type="url" 
                name="bma_slider_link_url" 
                id="bma_slider_link_url" 
                class="regular-text link-url"
                value="<?php echo ( isset($link_text) ) ? esc_url($link_url) : ''; ?>"
                required
            >
        </td>
    </tr>               
</table>