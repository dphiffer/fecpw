<?php

ini_set('display_errors', true);

get_header();

$page_request = $_SERVER['REQUEST_URI'];
echo "<h1>Sorry, nothing here.</h1>";
echo "<p>We could not find '$page_request' anywhere.";

get_footer();

?>
