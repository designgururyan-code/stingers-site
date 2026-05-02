<?php
/**
 * Fallback template
 */
get_header();
?>
<section class="pb"><div class="wrap">
  <h1><?php the_title(); ?></h1>
</div></section>
<section class="section"><div class="wrap">
  <?php if (have_posts()) : while (have_posts()) : the_post(); the_content(); endwhile; endif; ?>
</div></section>
<?php get_footer();
