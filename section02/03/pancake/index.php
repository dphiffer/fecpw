<?php

ini_set('display_errors', true);

get_header();

while (have_posts()) {
  the_post();
  ?>
    <div class="post">
      <h3>
        <a href="<?php the_permalink(); ?>">
          <?php the_title(); ?>
        </a>
      </h3>
      <?php the_content(); ?>
      <small><?php the_tags(); ?></small>
    </div>
  <?php
}

get_footer();

?>
