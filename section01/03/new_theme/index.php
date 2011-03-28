<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php bloginfo('name'); ?> | <?php bloginfo('description'); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />
    <?php wp_head(); ?>
  </head>
  <body>
    <?php
    
    // The Loop
    while (have_posts()) {
      the_post();
      echo "<h1>";
      the_title();
      echo "</h1>";
      the_content();
    }
    
    dynamic_sidebar();
    
    ?>
  </body>
</html>
