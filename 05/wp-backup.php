<?php

$backup_dir = dirname(__FILE__);
$num_backups = 3;

if (empty($argv[1])) {
   die("Usage: wp-backup.php [dir]\n");
}

$target = $argv[1];
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
  } else {
    die("Could not find wp-config.php\n");
  }
}

$date = date('Ymdhis');
$dir = "$backup_dir/$name-$date";

$wp_config = file_get_contents($wp_config_path);

preg_match("/define\('DB_HOST', '([^']+)'\);/", $wp_config, $matches);
$dbhost = $matches[1];

preg_match("/define\('DB_NAME', '([^']+)'\);/", $wp_config, $matches);
$dbname = $matches[1];

preg_match("/define\('DB_USER', '([^']+)'\);/", $wp_config, $matches);
$dbuser = $matches[1];

preg_match("/define\('DB_PASSWORD', '([^']+)'\);/", $wp_config, $matches);
$dbpass = $matches[1];

echo "Creating directory $dir\n";
exec("/bin/mkdir -p $dir");
echo "Copying files from $target to $dir/public\n";
exec("/bin/cp -R $target $dir/public");
echo "Dumping database $dbname\n";
exec("/usr/bin/mysqldump -h $dbhost -u $dbuser -p$dbpass $dbname > $dir/$dbname.dump");
echo "Zipping files into $name-$date.zip\n";
exec("cd $backup_dir && /usr/bin/zip -r $backup_dir/$name-$date.zip $name-$date");
echo "Deleting directory $dir\n";
exec("/bin/rm -rf $dir");
echo "Done\n";

$files = array();
$dh = opendir($backup_dir);
while ($file = readdir($dh)) {
  if (preg_match("/$name-\d+\.zip/", $file)) {
    $files[] = $file;
  }
}
sort($files);

if (count($files) > $num_backups) {
  echo "Deleting all but the $num_backups most recent backups\n";
  $expired = array_slice($files, 0, count($files) - $num_backups);
  foreach ($expired as $file) {
    unlink("$backup_dir/$file");
  }
}

?>