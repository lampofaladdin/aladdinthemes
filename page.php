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
		<?php // Show the selected frontpage content.
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				get_template_part( '/view/page', get_page_slug());
			endwhile;
		endif; ?>
    </main>
<?php get_footer(); ?>