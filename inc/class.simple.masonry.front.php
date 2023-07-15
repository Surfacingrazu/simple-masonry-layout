<?php

/**
 * 
 *
 * This class consists of methods for scripts and styles enqueue, and shortcode to generate Simple Masonry Layout
 * @package    Simple Masonry Layout
 * @subpackage Simple Masonry Layout Frontend 
 * @author Raju Tako
 */

class SimpleMasonryFront {

          public function __construct(){
                 add_action( 'wp_enqueue_scripts', array($this,'simple_masonry_enqueue_scripts'));
                 add_shortcode("simple_masonry",  array($this,'simple_masonry_shortcode'));
              }

          //Enqueue script and style

          public function simple_masonry_enqueue_scripts() { 
                    wp_register_style( 'sm-style', plugin_dir_url( __FILE__ ) . '../css/sm-style.css');
                    wp_register_style( 'darkbox-style', plugin_dir_url( __FILE__ ) . '../css/darkbox.css');
                    wp_register_style( 'font-awesome', ("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"));
                    wp_enqueue_style( 'sm-style');
                    wp_enqueue_style( 'darkbox-style');
                    wp_enqueue_style( 'font-awesome');
                    wp_enqueue_script( 'jquery' );
                    wp_enqueue_script( 'modernizr-script' );
                    wp_enqueue_script( 'jquery-masonry' );  
                    wp_register_script( 'modernizr-script', plugin_dir_url( __FILE__ ) . '../js/modernizr.custom.js', array('jquery'), '', false );
                    wp_register_script( 'classie-script', plugin_dir_url( __FILE__ ) . '../js/classie.js', array('jquery'), '', true );
                    wp_register_script( 'AnimOnScroll-script', plugin_dir_url( __FILE__ ) . '../js/AnimOnScroll.js', array('modernizr-script'), '', true );
                    wp_register_script( 'main-script', plugin_dir_url( __FILE__ ) . '../js/main.js', array('AnimOnScroll-script'), '', true );
                    wp_register_script( 'darkbox-script', plugin_dir_url( __FILE__ ) . '../js/darkbox.js', array('jquery'), '', true );
                    wp_enqueue_script( 'classie-script' );
                    wp_enqueue_script( 'AnimOnScroll-script' );
                    wp_enqueue_script( 'main-script' );
                    wp_enqueue_script( 'darkbox-script' );;

            }

            // pagination generation method 

            public function simple_masonry_pagination($numpages = '', $pagerange = '', $paged='') {

              if (empty($pagerange)) {
                $pagerange = 2;
              }

              /**
               * This first part of our function is a fallback
               * for custom pagination inside a regular loop that
               * uses the global $paged and global $wp_query variables.
               * 
               * It's good because we can now override default pagination
               * in our theme, and use this function in default queries
               * and custom queries.
               */
              global $paged;

              if (empty($paged)) {
               
                if ( get_query_var('paged') ) {
                      $paged = get_query_var('paged');
                  } elseif ( get_query_var('page') ) {
                      $paged = get_query_var('page');
                  } else {
                      $paged = 1;
                  }
                 
              }

              if ($numpages == '') {
                global $wp_query;
                $numpages = $wp_query->max_num_pages;
                if(!$numpages) {
                    $numpages = 1;
                }
              }

              /** 
               * We construct the pagination arguments to enter into our paginate_links
               * function. 
               */
              $pagination_args = array(
                'base'            => get_pagenum_link(1) . '%_%',
                'format'          => 'page/%#%',
                'total'           => $numpages,
                'current'         => $paged,
                'show_all'        => False,
                'end_size'        => 1,
                'mid_size'        => $pagerange,
                'prev_next'       => True,
                'prev_text'       => __('<i class="fa fa-chevron-left"></i>'),
                'next_text'       => __('<i class="fa fa-chevron-right"></i>'),
                'type'            => 'plain',
                'add_args'        => false,
                'add_fragment'    => ''
              );

              $paginate_links = paginate_links($pagination_args);

              if ($paginate_links) {
                echo "<nav class='sm-pagination'>";
                  echo $paginate_links;
                echo "</nav>";
              }


            }

          //shortcode generation method

          public function simple_masonry_shortcode($atts, $content = null) {

                        ob_start();

                        global $post;

                        extract(shortcode_atts(array(
                                
                                'sm_post_type'        => 'post',
                                'gallery'             => 'no',
                                'sm_category_name'    => ''
                               
                                 ), $atts));


                        $paged = get_query_var( 'paged' ) ?: ( get_query_var( 'page' ) ?: 1 );

                        $sm_args = array(
                                    'posts_per_page'   => get_option('simple_post_per_page'),
                                    'orderby'          => get_option('simple_post_orderby'),
                                    'order'            => get_option('simple_post_order'),
                                    'post_type'        => $sm_post_type,
                                    'category_name'    => $sm_category_name,
                                    'suppress_filters' => false,
                                    'post_status'      => 'publish',
                                    'paged'            => $paged,
                                    'meta_key'         => $gallery == 'yes' ? '_thumbnail_id' : ''
                              );

                        $wp_query = new WP_Query($sm_args);

                   if ( $wp_query->have_posts() ) :
                       
                     ?>

                    <div class="smblog_masonry_numcol" >
                     
                        <div class="sm-grid sm-effect" id="sm-grid-layout">  

                          <?php while ($wp_query->have_posts()) : $wp_query->the_post() ;

                                $thumbnail = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
                                $sm_date =  get_the_date( get_option('date_format'), $post->ID );
                                
                            ?>
                  
                            <?php if ($gallery == 'no' || $thumbnail) { ?>
                            <div class="grid-sm-boxes-in post-<?php echo $post->ID;?>">
                               <div class="grid-sm-border">
                                  <?php

                                    if($thumbnail) :

                                     if (get_option('simple_post_darkbox')) : 

                                       echo '<img class="img-responsive" src="' . $thumbnail . '" data-darkbox="'. $thumbnail .'"
                                        data-darkbox-description="<b>' .get_the_title().'</b>"> ';

                                      else:

                                        echo '<a href ="'.get_the_permalink().'"><img class="img-responsive" src="' . $thumbnail . '"></a>';

                                     endif;

                                    endif;
                                    ?>

                             <?php if ($gallery == 'yes') : ?>  

                               <?php if (get_option('sm_post_title')) : ?>

                                <div class="sm-gallery-title"> 
                                  <a href="<?php the_permalink();?>">                  
                                     <span class="sm-gallery-textPart"><?php the_title();?></span>
                                     <span class="sm-gallery-arrow"><?php echo '<img src="' . plugins_url( '../images/arrow.png', __FILE__ ) . '" > ';?></span> 
                                  </a>                 
                                </div>

                              <?php endif; ?>

                            <?php endif ?>
                               
                               <?php if ($gallery == 'no') { ?>
                                <div class="sm-grid-boxes-caption">
                                    <div class="sm-post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></div>                
                                    <div class="sm-list-inline sm-grid-boxes-news">
                                       <div class="sm-meta"> 
                                        <span class="sm-meta-part">
                                      <?php if (get_option('simple_post_author')) : ?>
                                          <span class="sm-meta-poster">
                                          <i class="sm-icon-author"></i><a href="<?php echo get_author_posts_url( get_the_author_meta($post->post_author), get_the_author_meta( 'user_nicename', $post->post_author ) ); ?>"><?php  esc_attr(the_author_meta( 'display_name', $post->post_author ));?></a></span> 
                                      <?php endif;?>
                                         <span class="sm-meta-date"> <i class="sm-icon-date"> 
                                        </i><a href="<?php echo esc_url(get_day_link(get_post_time('Y','',$post->ID), get_post_time('m','',$post->ID), get_post_time('j','',$post->ID))); ?>"><?php echo esc_attr($sm_date); ?></a> </span> 
                                       <?php if (get_option('sm_post_comment')) : ?>
                                         <span class="sm-meta-likes"><i class="sm-icon-comments"></i><?php echo get_comments_number($post->ID);?></span> 
                                       <?php endif;?>
                                      </span>
                                     </div>
                                    </div>  
                                   <div class="sm-grid-boxes-quote">
                                    <?php if (has_excerpt ($post->ID)) the_excerpt(); else echo '</p>' . strip_shortcodes(wp_trim_words( get_the_content(), 20 )) . '</p>'; ?>
                                   </div>
                                </div>
                              <?php } ?>
                                
                              </div>
                          </div>
                            <?php }?>

                    <?php endwhile ; ?>
                 
                        </div>
                      
                    <?php if (method_exists($this, 'simple_masonry_pagination')) {
                               $this->simple_masonry_pagination($wp_query->max_num_pages,"",$paged);
                          } 
                    ?>
                    
                    <?php wp_reset_postdata(); ?>
                 
                    </div>

                <?php endif;?>

                <?php

                    return ob_get_clean();

                }


}

new SimpleMasonryFront;


