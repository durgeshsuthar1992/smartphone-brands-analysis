<?php
/**
* db_lib.php
* General purpose database library for use by all 140dev code
* 
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.30
*/
class db
{
  public $dbh;
  public $error;
  public $error_msg;    
  
  // Create a database connection for use by all functions in this class
  function __construct() {

    require_once('db_config.php');
    
    if($this->dbh = mysqli_connect($db_host, 
      $db_user, $db_password, $db_name)) { 
            
      // Set every possible option to utf-8
      mysqli_query($this->dbh, 'SET NAMES "utf8"');
      mysqli_query($this->dbh, 'SET CHARACTER SET "utf8"');
      mysqli_query($this->dbh, 'SET character_set_results = "utf8",' .
        'character_set_client = "utf8", character_set_connection = "utf8",' .
        'character_set_database = "utf8", character_set_server = "utf8"');
    } else {
      // Log an error if the connection fails
      $this->error = true;
      $this->error_msg = 'Unable to connect to DB';
      $this->log_error('__construct','attempted connection to ' . $db_name);
    }
        
    date_default_timezone_set(TIME_ZONE);
  }
  
  // Call this after each DB request to test for and log errors
  // Supply the calling function name and query, so they can be logged
  private function error_test($function,$query) {
    
    // Record the last error state in the object, 
    // so code using objects of this class can read it
    if ($this->error_msg = mysqli_error($this->dbh)) {
        $this->log_error($function,$query);
        $this->error = true;
    } else {
        $this->error = false;
    }
    return $this->error;
  }
    
  // Write any errors into a text log
  // Include the date, calling script, function called, and query
  private function log_error($function,$query) {
    $fp = fopen('error_log.txt','a');
    fwrite($fp, date(DATE_RFC822) . ' | ' . 
      $_SERVER["SCRIPT_NAME"] . ' -> ' . $function . 
      ' | ' . $this->error_msg . ' | ' . $query . "\n");
    fclose($fp); 
  }
    
  // Create a standard data format for insertion of PHP dates into MySQL
  public function date($php_date) {
    return date('Y-m-d H:i:s', strtotime($php_date));	
  }
    
  // All text added to the DB should be cleaned with mysqli_real_escape_string
  // to block attempted SQL insertion exploits
  public function escape($str) {
    return mysqli_real_escape_string($this->dbh,$str);
  }
    
  // Test to see if a specific field value is already in the DB
  // Return false if no, true if yes
  public function in_table($table,$where) {
    $query = 'SELECT * FROM ' . $table . 
      ' WHERE ' . $where;
    $result = mysqli_query($this->dbh,$query);
    $this->error_test('in_table',$query); 
    return mysqli_num_rows($result) > 0;
  }

  // Perform a generic select and return a pointer to the result
  public function select($query) {
    $result = mysqli_query( $this->dbh, $query );
    $this->error_test("select",$query);
    return $result;
  }
    
  // Add a row to any table
  public function insert($table,$field_values) {
    $query = 'INSERT INTO ' . $table . ' SET ' . $field_values;
    mysqli_query($this->dbh,$query);
    $this->error_test('insert',$query);
  }
    
  // Update any row that matches a WHERE clause
  public function update($table,$field_values,$where) {
    $query = 'UPDATE ' . $table . ' SET ' . $field_values . 
      ' WHERE ' . $where;
    mysqli_query($this->dbh,$query);
   $this->error_test('update',$query);
  }  
}  
?>