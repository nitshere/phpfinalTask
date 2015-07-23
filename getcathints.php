<?php
// Array with names
session_start();
require_once('databaseconn.php');
// get the q parameter from URL

$servername = "localhost";
	$username = "nits1";
	$password = "1234";
	$dbname = "coupondunia";


$q = $_REQUEST["q"];
//echo $q;
$store = strtolower ($_REQUEST['store']);
$cpntype = strtolower ($_REQUEST['cpntype']);

if ($cpntype == "deal") {
        $deal = 1;
} else if ($cpntype == "coupon"){
        $deal = 0;
} else {
        $deal = -1;
}

$hint = "";

// lookup all hints from array if $q is different from "" 
$i = 0;
if ($q !== "") {
    $q = strtolower($q);
    $len=strlen($q);
    $index = 0;
    foreach($_SESSION['categories'] as $name) {

        if (stristr($q, substr($name, 0, $len))) {
        //if (preg_match("[^" . $q . "]", $name)) {
            if ($hint === "") {
                $hint = $name;
                
            	break;
            	
            }

            /*else {
                $hint .= ", $name";
            }*/
        }
        $index++;
    }
}
$idx = -1;
// Output "no suggestion" if no hint was found or output correct values 
echo $hint === "" ? "no suggestion" : $hint;
echo " (";
//echo "<br>" . $_SESSION['storeid'][$index] . "<br>";
if (!($hint === "")) {
    $idx = $_SESSION['categoryid'][$index];

//$connhandle = new mysqli($servername, $username, $password, $dbname);

//this query works
//$query = "select distinct couponid from couponcategoryinfo where categoryid = " . $idx;

$query = "select count(*) from couponcategoryinfo ";
    $query = $query . "inner join coupon on couponcategoryinfo.couponid = coupon.couponid";
    if ($idx != -1) {
        $query = $query . " and couponcategoryinfo.categoryid = " . htmlspecialchars($idx);
    }
    if ($deal != -1) {
        $query = $query . " and coupon.isdeal = " . htmlspecialchars($deal);
    }
    //$query = $query . " and coupon.isdeal = " . htmlspecialchars($deal);
    $query = $query . " inner join website on coupon.websiteid = website.websiteid";

    if ($store != null) {
    $query =  $query . " and website.websitetitle = '" . htmlspecialchars($store) . "'";
    }
 


//echo $query;
//$query = "select couponid from coupon where websiteid = " . $idx;
$connhandle = dbconnection :: getinstance() -> connhandle;
$result = $connhandle -> query($query);
echo $connhandle -> error;
//echo var_dump($result -> fetchassoc);
//print_r ($result -> fetch_assoc());
//echo 
$row = $result -> fetch_assoc();
//echo var_dump($row);
echo $row['count(*)'];
}
//echo mysqli_num_rows($result);
echo " )<br>";
dbconnection :: close();
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