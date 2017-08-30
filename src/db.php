<?php

  namespace nathangrove\DBObject;
  
  # db.php is a database abstraction layer for mysql using the mysqli driver

  # a class that will return a singleton connection to the DB
  class db {

      var $conn;
      var $log_level;
      var $log;

      function __construct($host, $username, $password, $db, $log_level = '', $log_dir = "dblogs") {

          if ($log_level != ''){
             mkdir($log_dir);
          }

          $this->log_level = $log_level;
          $this->log = $log_dir . "/db.log";

          $conn = mysqli_connect($host, $username, $password, $db);

          if (!$conn) {
              throw new Exception('Database connection failed');
          } else {
              $this->conn = $conn;
              # we will store it in an obscure global variable
              $GLOBALS['db_dbconn'] = $this;
          }
          return true;
      }

      function _log($level, $msg){

          if (($this->log_level == 'ERROR' && $level == 'ERR')
              || ($this->log_level == 'LOG' && ($level == 'LOG' || $level == 'ERR')
              || ($this->log_level == 'VERBOSE'))
          ){
              $fw = fopen($this->log,'a');
              fwrite($fw,"[ " . date("Y-m-d H:i:s") . " ] $msg\n");
              fclose($fw);
          }
      }
  }


?>