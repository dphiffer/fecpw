<?php

get_header();

?>

<div id="content">

<?php

if (have_posts()) {
  while (have_posts()) {
    the_post();
    the_content();
  }
} else {
  echo "Sorry, nothing available right now. Try again later?";
}
?>

</div>
<div id="sidebar">

  <?php get_sidebar(); ?>

</div>
<br class="clear" />

<?php

get_footer();

?>
