<?php
// Array with names
session_start();
require_once('databaseconn.php');
// get the q parameter from URL
//echo "yesvrf";
/*$servername = "localhost";
	$username = "nits1";
	$password = "1234";
	$dbname = "coupondunia";*/


$q = $_REQUEST["q"];
$cpntype = $_REQUEST["cpntype"];

if ($cpntype == "deal") {
        $deal = 1;
} else if ($cpntype == "coupon"){
        $deal = 0;
} else {
        $deal = -1;
}

$hint = "";

//$handle = dbconnection :: getinstance() -> connhandle;
// lookup all hints from array if $q is different from "" 
$i = 0;
if ($q !== "") {
    $q = addslashes(strtolower($q));
    $len=strlen($q);
    $index = 0;

    $query = "select websiteid,websitetitle from website where websitetitle LIKE '" . htmlspecialchars($q) . "%'" . " limit 1";
    $connhandle = dbconnection :: getinstance() -> connhandle;
    $result = $connhandle -> query($query);
    echo $connhandle -> error;
    $row = $result -> fetch_assoc();
    if ($row) {
        echo $row['websitetitle'];
        $idx = $row['websiteid'];
        $query = "select count(distinct couponid) from coupon where websiteid = " . $idx;
        if ($deal != -1) {
            $query = $query . " and isdeal = " . $deal;
        } 
        $query = $query . " and (date(expiry) > CURDATE() or (date(expiry) = CURDATE() and time(expiry) >= CURTIME()))";

        //$query = $query . " and (date(expiry) > CURDATE()";
        $result = $connhandle -> query($query);
        echo $connhandle -> error;
        echo " (";
        echo $result -> fetch_assoc()['count(distinct couponid)'];
        echo " )";    
    } else {
        echo "no suggestions";
    }    


    /*foreach($_SESSION['stores'] as $name) {

        //if (stristr($q, substr($name, 0, $len))) {
        //if (stristr($q, $name)) {
        if (preg_match("[^" . $q . "]", $name)) {
            if ($hint === "") {
                $hint = $name;
                
            	break;
            	
            }

            /*else {
                $hint .= ", $name";
            }*/
    /*    }
        $index++;
    }*/
    dbconnection :: close();
}
//$handle = dbconnection :: getinstance() -> connhandle;

// Output "no suggestion" if no hint was found or output correct values 
//echo "<br>" . $_SESSION['storeid'][$index] . "<br>";
//$connhandle -> close();

//$connhandle = dbconnection :: getinstance() -> connhandle;

//echo $_REQUEST["type"];
//echo $result;
//echo $result -> fetch_assoc()[];
 /*if (isset($_SESSION['storeid'])) {
	echo "is set";
} else {
	echo "not set";
}
print_r $result -> fetchassoc();*/

?>