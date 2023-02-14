<?php
# Config
$conn = mysqli_connect('localhost', 'root', 'root', 'denote');
$iv = "0000000000000000"; // string of numbers, length 16
if (strlen($iv) !== 16) {
    die("Sorry, your IV key length != 16");
}
$delete_old_notes = true; # Auto-delete notes older than 30 days

$base_url = 'https://base-api-url/denote?id='; // Path to index.php
# End Config
$version = '0.4-beta';
