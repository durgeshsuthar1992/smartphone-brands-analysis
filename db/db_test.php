<?php 
/**
* db_test.php
* Use this script to test your database installation 
* 
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/
require_once('db_lib.php');
$oDB = new db;
print '<strong>Twitter Database Tables</strong><br />';
$result = $oDB->select('SHOW TABLES');
while ($row = mysqli_fetch_row($result)) {
  print $row[0] . '<br />';
}
?>