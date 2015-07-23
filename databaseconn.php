<?php
	require_once('constants.php');
	class dbconnection {
		public $connhandle;
		private static $instance;

		private function __construct() {
			try {
				//echo "singleton construct called";
				$this -> connhandle = new mysqli(servername, username, password, dbname);
			} catch (Exception $ex) {
				echo "exception: " . $ex -> getMessage();
			}
		}

		public static function getinstance() {
			if (!isset(self::$instance)) {
				self :: $instance = new dbconnection();
			} else {
				//echo "singleton construct not called";
			}
			return self :: $instance;
		}

		public static function close() {
			//$this -> connhandle -> close();
			$connhandle  = null;
		}


		/*public function connect($servername, $username, $psw, $dbname) {
			$this -> connhandle = new mysqli($servername, $username, $psw, $dbname);
			//echob$connhandle -> error;
			//print_r $connhandle;
			if ($this -> connhandle -> connect_error) {
				echo "not connected <br>";
			} else {
				echo "connected<br>";

			}
			return $this -> connhandle;
		}

		public function close() {
			$this -> connhandle -> close();
			echo "connection closed<br>";
		}

		public function insertExecute($query) {
			echo "here";
			//print_r($connhandle);
			if ($this -> connhandle -> query($query) === TRUE) {
				echo "query executed";
			} else {
				echo "not execd";
				echo $this -> connhandle -> error;
			}
		}

		public function selectExecute($query) {
			//$result = null;

			$result = $this -> connhandle -> query($query);
			/*if (isset($result)) {
				echo "set";
			}*/
		/*	while($row = $result -> fetch_assoc()) {
				echo $row['id']." ".$row['name']; 
				echo "<br>";
			}
		}*/

	}

?>