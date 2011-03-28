<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Basic form</title>
  </head>
  <body>
    <?php
    
    $query = "";
    if (isset($_REQUEST["query"])) {
      $query = htmlentities($_REQUEST["query"], 2, "UTF-8");
      echo "<h1>You wrote: '$query'</h1>\n";
    }
    
    ?>
    <form action="basic-form.php" >
      <input type="text" name="query" value="<?php echo $query; ?>" />
      <input type="submit" value="Kablooey" />
    </form>
  </body>
</html>
