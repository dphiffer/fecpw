<?php
/*
Template Name: My Custom Template
*/

// Set up the global $post object
global $post;

// Include all children of this page
// See: http://codex.wordpress.org/Function_Reference/query_posts
query_posts("post_parent=$post->ID&post_type=page&orderby=menu_order&order=ASC");

/*

The above one-liner is equivalent to this other syntax:

query_posts(array(
  'post_parent' => $post->ID,
  'post_type' => 'page'
  'orderby' => 'menu_order',
  'order' => 'ASC'
));

*/

get_header();

if (have_posts()) {
  while (have_posts()) {
    the_post();
    the_content();
  }
} else {
  echo "Error, no posts here!";
}

get_footer();

?>
