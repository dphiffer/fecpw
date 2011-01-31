<?php

// Set up the database connection and store the current page's URL
$db = connect_to_database();
$url = $_SERVER['REQUEST_URI'];

// The CSS class for the content
$class = 'content';

// Save content and reload the page if the user is saving a new revision
if (isset($_POST["content"])) {
  $id = save_content($db, $_POST["content"]);
  header("Location: tiny-wiki.php?id=$id");
  exit;
}

// The user can request a specific revision, or the latest one in the DB
if (!empty($_REQUEST['id'])) {
  // Load specific content based on the id parameter in the URL
  $id = $_REQUEST['id'];
  $content = load_content_by_id($db, $id);
  if ($id == load_newest_id($db)) {
    $class .= ' newest'; // Append 'newest' class to the content
  }
} else {
  // Load the newest content from the database (i.e., id is not set in the URL)
  $id = load_newest_id($db);
  $content = load_newest_content($db);
  $class .= ' newest'; // Append 'newest' class to the content
}

// We need a link to click on even if the content is empty
if (empty($content)) {
  $content = '(empty)';
}

// Returns the most recent content
function load_newest_content($db) {
  $sql = "SELECT * FROM tinytable ORDER BY id DESC";
  $row = load_content($db, $sql);
  return $row['content'];
}

// Returns the most recent ID
function load_newest_id($db) {
  $sql = "SELECT * FROM tinytable ORDER BY id DESC";
  $row = load_content($db, $sql);
  return $row['id'];
}

// Returns a specific row's content
function load_content_by_id($db, $id) {
  $sql = "SELECT * FROM tinytable WHERE id = " . intval($id);
  $row = load_content($db, $sql);
  return $row['content'];
}

// General load_content() function, SQL command is passed in
function load_content($db, $sql) {
  // This empty associative array will be returned if nothing is found
  $empty = array(
    'id' => 0,
    'content' => ''
  );
  
  // Run the SQL query
  $query = $db->query($sql);
  
  if (empty($query)) {
    // Something went wrong, we probably just need to set up the database table
    setup_database($db);
    return $empty;
  }
  
  // Fetch the results of our query
  $results = $query->fetchAll();
  if (empty($results)) {
    return $empty;
  }
  
  // Return the first result
  return $results[0];
}

// Save content back to the database
function save_content($db, $content) {
  $content = $db->quote($content);
  $sql = "INSERT INTO tinytable (content) VALUES ($content)";
  $db->query($sql);
  return $db->lastInsertId();
}

// Establishes a detabase connection
function connect_to_database() {
  // Storing these values in a separate value makes them easier to edit
  include 'config.php';
  $dsn = "mysql:host=$host;port=$port;dbname=$name";
  return new PDO($dsn, $user, $pass);
}

// Creates a new table with two columns
function setup_database($db) {
  $db->query("
    CREATE TABLE tinytable (
      id INTEGER PRIMARY KEY AUTO_INCREMENT,
      content TEXT
    )
  ");
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Tiny wiki</title>
    <link rel="stylesheet" href="tiny-wiki.css" />
  </head>
  <body>
    <a href="<?php echo $url ?>" class="<?php echo $class; ?>" data-id="<?php echo $id; ?>"><?php echo $content; ?></a>
    <form action="tiny-wiki.php" method="post">
      <input type="text" name="content" class="content" id="input" value="<?php echo $content; ?>" />
      <input type="submit" value="Update" id="submit" />
    </form>
    <div id="bottom">Hint: press left/right arrow keys. With apologies to <a href="http://barackobamaisyournewbicycle.com/">BOIYNB</a></div>
    <script src="jquery.min.js"></script>
    <script src="tiny-wiki.js"></script>
  </body>
</html>
