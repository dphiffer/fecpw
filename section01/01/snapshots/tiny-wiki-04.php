<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Tiny wiki</title>
  </head>
  <body>
    <?php

    $db = connect_to_database();
    $content = load_content($db);
    
    function load_content($db) {
      // Load content from the database
      return "";
    }
    
    function connect_to_database() {
      $host = "127.0.0.1";
      $port = 8889;
      $user = "root";
      $pass = "root";
      $name = "tinydb";
      $dsn = "mysql:host=$host;port=$port;dbname=$name";
      return new PDO($dsn, $user, $pass);
    }
    
    ?>
    <form action="tiny-wiki-04.php" method="post">
      <input type="text" name="content" value="<?php echo $content; ?>" />
      <input type="submit" value="Update" />
    </form>
  </body>
</html>
