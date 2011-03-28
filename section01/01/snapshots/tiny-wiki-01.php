<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Tiny wiki</title>
  </head>
  <body>
    <?php
    
    $content = ""; // We need to load the content!
    
    ?>
    <form action="tiny-wiki-01.php" method="post">
      <input type="text" name="content" value="<?php echo $content; ?>" />
      <input type="submit" value="Update" />
    </form>
  </body>
</html>
