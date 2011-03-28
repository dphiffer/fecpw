<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Tiny wiki</title>
  </head>
  <body>
    <?php

    $content = load_content();
    
    function load_content() {
      // Load content from the database
      return "";
    }
    
    ?>
    <form action="tiny-wiki-02.php" method="post">
      <input type="text" name="content" value="<?php echo $content; ?>" />
      <input type="submit" value="Update" />
    </form>
  </body>
</html>
