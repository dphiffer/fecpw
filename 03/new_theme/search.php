<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title><?php bloginfo('name'); ?> | <?php bloginfo('description'); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />
    <?php wp_head(); ?>
  </head>
  <body>
    <h1>Search results for '<?php echo $_GET['s']; ?>'</h1>
    <?php
    
    // Check if there even is any content
    if (have_posts()) {
      // The Loop
      while (have_posts()) {
        the_post();
        echo "<h1>";
        the_title();
        echo "</h1>";
        the_content();
      }
    } else {
      echo "Sorry, nothing here.\n";
    }
    
    ?>
  </body>
</html>
