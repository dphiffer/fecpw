<?php

$num_backups = 3;
$backup_dir = dirname(__FILE__);

if (function_exists('date_default_timezone_set')) {
  date_default_timezone_set('America/New_York');
}

$exec_list = array(
  'mysqldump',
  'cp',
  'mkdir',
  'zip',
  'rm'
);

foreach ($exec_list as $exec) {
  $result = null;
  exec("which $exec", $result);
  if (empty($result)) {
    die("Error: could not find '$exec'. Is it installed?\n");
  }
  list($$exec) = $result;
}

if (file_exists("$backup_dir/public") && file_exists("$backup_dir/public/wp-admin")) {
  $target = "$backup_dir/public";
} else if (empty($argv[1])) {
  die("Usage: php wp-backup.php [target directory]\n");
} else {
  $target = $argv[1];
}

$name = basename($target);

if ($name == 'public') {
  $name = basename(dirname($target));
}

if (substr($target, -1, 1) == '/') {
  $target = substr($target, 0, -1);
}

if (!file_exists($target)) {
  die("No such file $target\n");
}

$wp_config_path = "$target/wp-config.php";
if (!file_exists($wp_config_path)) {
  if (file_exists(dirname($target) . "/wp-config.php")) {
    $wp_config_path = dirname($target) . "/wp-config.php";
    $wp_config_alt_path = true;
  } else {
    die("Could not find wp-config.php\n");
  }
}

$date = date('Y_m_d');
$n = 1;
$dir = "$backup_dir/$name-$date-$n";
while (file_exists("$dir.zip")) {
  $n++;
  $dir = "$backup_dir/$name-$date-$n";
}

$wp_config = file_get_contents($wp_config_path);
$wp_config = str_replace('"', "'", $wp_config);

preg_match("/define\('DB_HOST',\s*'([^']+)'/", $wp_config, $matches);
$dbhost = $matches[1];

preg_match("/define\('DB_NAME',\s*'([^']+)'/", $wp_config, $matches);
$dbname = $matches[1];

preg_match("/define\('DB_USER',\s*'([^']+)'/", $wp_config, $matches);
$dbuser = $matches[1];

preg_match("/define\('DB_PASSWORD',\s*'([^']*)'/", $wp_config, $matches);
$dbpass = $matches[1];

if (!empty($dbpass)) {
  $dbpass = " -p$dbpass";
}

preg_match("/table_prefix\s*=\s*'([^']+)'/", $wp_config, $matches);
$dbprefix = $matches[1];

$db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
$query = $db->query("SHOW TABLES");
$results = $query->fetchAll();
$tables = array();
foreach ($results as $record) {
  if (strpos($record[0], $dbprefix) === 0) {
    $tables[] = $record[0];
  }
}
$tables = implode(' ', $tables);

echo "$mkdir -p $dir\n";
exec("$mkdir -p $dir");

echo "$cp -R $target $dir/public\n";
exec("$cp -R $target $dir/public");

if (!empty($wp_config_alt_path)) {
  echo "$cp $wp_config_path $dir/wp-config.php\n";
  exec("$cp $wp_config_path $dir/wp-config.php");
}

echo "$mysqldump -h $dbhost -u $dbuser$dbpass $dbname $tables > $dir/$dbname.dump\n";
exec("$mysqldump -h $dbhost -u $dbuser$dbpass $dbname $tables > $dir/$dbname.dump");

echo "cd $backup_dir && $zip -r $backup_dir/$name-$date-$n.zip $name-$date-$n\n";
exec("cd $backup_dir && $zip -r $backup_dir/$name-$date-$n.zip $name-$date-$n");

echo "$rm -rf $dir\n";
exec("$rm -rf $dir");

$files = array();
$dh = opendir($backup_dir);
while ($file = readdir($dh)) {
  if (preg_match("/$name-[0-9_]+-\d+\.zip/", $file)) {
    $files[] = $file;
  }
}
sort($files);

if (count($files) > $num_backups) {
  $expired = array_slice($files, 0, count($files) - $num_backups);
  foreach ($expired as $file) {
    echo "$rm $backup_dir/$file\n";
    exec("$rm $backup_dir/$file");
  }
}

?>
