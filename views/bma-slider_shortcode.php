<?php if(isset(BMA_Slider_Settings::$options['bma_slider_display_title'])){?>
<h3><?php echo ( ! empty ( $content ) ) ? esc_html( $content ) : esc_html( BMA_Slider_Settings::$options['bma_slider_title'] ); ?></h3>
<?php }?>
<div class="bma-slider flexslider <?php echo (isset(BMA_Slider_Settings::$options['bma_slider_style'])) ? esc_attr(BMA_Slider_Settings::$options['bma_slider_style']) : 'style-1'?>">
    <ul class="slides">
        <?php 

            $args = array(
                'post_type' => 'bma-slider',
                'post_status' => 'publish',
                'post__in' => $id,
                'orderby' => $orderby
            );

            $bma_query = new WP_Query($args);
            if($bma_query->have_posts()):
                while($bma_query->have_posts()):$bma_query->the_post();
                    $button_text = get_post_meta( get_the_ID(), 'bma_slider_link_text' ,true);
                    $button_url = get_post_meta( get_the_ID(), 'bma_slider_link_url' ,true);
        ?>
        <li>
            <?php
            if(has_post_thumbnail()){
                the_post_thumbnail('full', array('class' => 'img-fluid'));
            }
            else{
                echo bma_placeholder_image();
            }

            ?>

            <div class="after" style="background-color: <?php echo BMA_Slider_Settings::$options['bma_slider_background_overlay'];?>; opacity: <?php echo BMA_Slider_Settings::$options['bma_slider_overlay_opacity'] / 100;?>;"></div>


            <div class="bmas-container">
                <div class="slider-details-container">
                    <div class="wrapper">
                        <div class="slider-title">
                            <h2><?php echo the_title(); ?></h2>
                        </div>
                        <div class="slider-description">
                            <div class="subtitle"><?php the_content();?></div>
                            <a class="link bma-button" href="<?php echo $button_url;?>"><?php echo $button_text;?></a>
                        </div>
                    </div>
                </div>              
            </div>
            
        </li>
        <?php
            endwhile;
            wp_reset_postdata();
            endif;
        ?>
    </ul>
</div>