<?php 

/* 
 * This is the default template for displaying a single room. 
 * 
 * It can be overriden and customized by copying it to a theme directory
 * making the desired modifications.
 */
get_header(); 
?>

<?php get_template_part('includes/top_info'); ?>

<div id="primary" class="content-area">
  <main id="main" class="site-main" role="main">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
            <header class="entry-header">
			  <h1 class="single_room_title entry-title"><?php the_title(); ?></h1>
            </header>
		    <div class="entry-content">	
				<div id="room_images_container">
					<?php  
						$alternate_shortcode = get_post_meta($id, 'meta_room_alterate_image_shortcode', true);
						if ( ! empty( $alternate_shortcode )) {
							echo do_shortcode( $alternate_shortcode );
						}
						else {
							echo do_shortcode('[room_images]'); 
						}
					?>
				</div>
				
				<div id="room_desc_container">
					<?php  echo do_shortcode('[room_slogan]'); ?>
					<?php  echo do_shortcode('[room_desc]'); ?>					
				</div>
				
				<div class="room_first_widget_area">
					<?php dynamic_sidebar('room_first_widget_area'); ?>
				</div>
				

				<div class="room_amenities hsp_two_third">
					<?php echo do_shortcode('[room_amenities]'); ?>
				</div>
				<div class="booking_form-wa hsp_one_third">
					<div class="room_second_widget_area">
						<?php dynamic_sidebar('room_second_widget_area') ;?>
					</div>
				</div>
								
				<div id="pricings_container">
					<?php  echo do_shortcode('[pricings]'); ?>
				</div>
				<div class="room_third_widget_area">
					<?php dynamic_sidebar('room_third_widget_area'); ?>
				</div> 
            </div> <!-- entry-content -->           
		</article> <!-- end .entry -->
	<?php endwhile; endif; ?>
   </main>
</div> <!-- end #content -->
<?php get_footer(); ?>
