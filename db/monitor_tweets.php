<?php
/**
* monitor_tweets.php
* Check for new tweets in the tweets table and report by email if they aren't found
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.20
*/
require_once('140dev_config.php');
require_once('db_lib.php');
$oDB = new db;

if ($oDB->error) {
  // Report a DB connection error 
  error_email('Twitter Database Error', 'Unable to connect to Twitter database');
  exit;
}

// Check for new tweets arriving in the tweets table
// TWEET_ERROR_INTERVAL is defined in 140dev_config.php
$query = 'SELECT COUNT(*) AS cnt FROM tweets ' .
  'WHERE created_at > DATE_SUB(NOW( ), ' .
  'INTERVAL ' . TWEET_ERROR_INTERVAL . ' MINUTE)';
$result = $oDB->select($query);
$row = mysqli_fetch_assoc($result);

// If there are no new tweets
if ($row['cnt']==0) {
  // Get the date and time of the last tweet added
  $query = 'SELECT created_at FROM tweets ' .
    'ORDER BY created_at DESC LIMIT 1';
  $result = $oDB->select($query);
  $row = mysqli_fetch_assoc($result);
  $time_since = (time() - strtotime($row['created_at']))/60;
  $time_since = (int) $time_since;
  $error_message = 'No tweets added for ' . $time_since . " minutes."; 
  $subject = 'Twitter Database Server Error';
  error_email($subject,$error_message);
}

// Email the error message
function error_email($subject,$message) {
  $to = TWEET_ERROR_ADDRESS;
  $from = "From: Twitter Database Server Monitor <" . 
    TWEET_ERROR_ADDRESS . ">\r\n";
  mail($to,$subject,$message,$from);
}
?>