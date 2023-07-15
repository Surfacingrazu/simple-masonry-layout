<?php

function post_per_page_simple($check_spp){

  if (is_numeric($check_spp) && $check_spp > 0) : 

       $check_spp; 

  else:   

    $check_spp = get_option('posts_per_page') ; 

  endif; 

  return $check_spp;

}



function simple_masonry_shortcode($atts, $content = null) {
        extract(shortcode_atts(array(
                
                'sm_post_type'        => 'post',
                'gallery'             => 'no'
               
                 ), $atts));



        global $post;
        $sm_post = get_posts( array(

        'posts_per_page'   => post_per_page_simple(get_option('simple_post_per_page')),
                'orderby'          => get_option('simple_post_orderby'),
                'order'            => get_option('simple_post_order'),
                'post_type'        => $sm_post_type,
                'post_status'      => 'publish'
        
        ));

       
  ?>


  <div class="blog_masonry_numcol">
        <div class="container content grid-boxes">            
          <?php 
 
           foreach ($sm_post as $post) : 
                  setup_postdata($post);

                $thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
                $sm_date =  get_the_date( get_option('date_format'), $post->ID );
            ?>
  
            <?php if ($gallery == 'no' || $thumbnail) { ?>
            <div class="grid-boxes-in">
                <a href="<?php the_permalink();?>">
                  <?php
                     if($thumbnail) :
                      echo '<img class="img-responsive" src="' . $thumbnail . '" > ';
                     endif;
                    ?>
              </a>
               
               <?php if($gallery == 'no'){ ?>
                <div class="grid-boxes-caption">
                    <h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>                
                    <div class="list-inline grid-boxes-news">
                       <div class="meta"> 
                        <span class="meta-part">
                      <?php if(get_option('simple_post_author')) : ?>
                          <span class="meta-poster">
                          <i class="icon-author"></i><a href="<?php echo get_author_posts_url( get_the_author_meta($post->post_author), get_the_author_meta( 'user_nicename', $post->post_author ) ); ?>"><?php  esc_attr(the_author_meta( 'display_name', $post->post_author ));?></a></span> 
                      <?php endif;?>
                         <span class="meta-date"> <i class="icon-date"> 
                        </i><a href="<?php echo esc_url(get_day_link(get_post_time('Y','',$post->ID), get_post_time('m','',$post->ID), get_post_time('j','',$post->ID))); ?>"><?php echo esc_attr($sm_date); ?></a> </span> 
                       <?php if(get_option('sm_post_comment')) : ?>
                         <span class="meta-likes"><i class="icon-comments"></i><?php echo get_comments_number($post->ID);?></span> 
                       <?php endif;?>
                      </span>
                     </div>
                    </div>                 
                  <?php  echo '<p>' . wp_trim_words( get_the_content(), 20 ) . '</p>'; ?>
                </div>
              <?php } ?>
                
            </div>
            <?php }?>
    <?php endforeach ; ?>

    <?php wp_reset_postdata(); ?>
              
        </div><!--/container-->
    </div>


<?php
}
add_shortcode("simple_masonry", "simple_masonry_shortcode");