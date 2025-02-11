<?php
include_once('../../model/connect.php');
function rmdir_recursive($dir)
{
    foreach (scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }

    rmdir($dir);
}
rmdir_recursive(dirname(__FILE__)."/"."../"."../");
$database = new connectDB();
$sql = "SHOW TABLES";
$tables = $database->query($sql);

while ($table = mysqli_fetch_row($tables)) {
    $sql = "SHOW TABLES";
    $tables = $database->query($sql);
    while ($table = mysqli_fetch_row($tables)) {
        $sql = "DROP TABLE " . $table[0];
        $database->execute($sql);
    }
    $sql = "SHOW TABLES";
    $tables = $database->query($sql);
}
//  unlink("../../web2-bookstore");