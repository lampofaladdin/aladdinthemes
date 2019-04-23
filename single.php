<?php
/**
 * WordStar index file
 * @category WordPress
 * @package  aladdinThemes
 * @author   aladdin
 * @license  MIT
 * */
get_header();
?>
    <main id="page-<?php the_ID(); ?>" role="main" class="<?php echo  is_home() || is_front_page()?"home-main":"common-main"; ?>" >
        <div class="content" id="single">
            <div class="container">
	            <?php // Show the selected frontpage content.
	            if ( have_posts() ) :
		            while ( have_posts() ) : the_post();
			            the_content();
		            endwhile;
	            endif; ?>
            </div>
        </div>
    </main>
<?php get_footer(); ?>