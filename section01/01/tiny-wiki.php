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
    
    if (!empty($_REQUEST["content"])) {
      save_content($db, $_REQUEST["content"]);
      $content = htmlentities($_REQUEST["content"]);
    }
    
    function load_content($db) {
      $sql = "SELECT * FROM tinytable ORDER BY id DESC";
      $query = $db->query($sql);
      $results = $query->fetchAll();
      $row = $results[0];
      return $row["content"];
    }
    
    function save_content($db, $content) {
      $content = $db->quote($content);
      $sql = "INSERT INTO tinytable (content) VALUES ($content)";
      $db->query($sql);
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
    <form action="tiny-wiki.php" method="post">
      <input type="text" name="content" value="<?php echo $content; ?>" />
      <input type="submit" value="Update" />
    </form>
  </body>
</html>
