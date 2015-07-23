<?php
	session_start();

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);

	require_once('databaseconn.php');
	$store = strtolower ($_REQUEST['store']);
	$cpntype = strtolower ($_REQUEST['cpntype']);
	$category = $_REQUEST['category'];

	/*echo var_dump($cpntype);
	if ($cpntype != null) {
		echo "cpntype set";
	} if (isset($store)) {
		echo "store set";
	} if (isset($category)) {
		echo "categ set";
	}*/
	//echo $store . " " . $cpntype . " " . $category;
	if ($cpntype == "deal") {
		$deal = 1;
	} else if ($cpntype == "coupon"){
		$deal = 0;
	} else {
		$deal = -1;
	}

	/*$servername = "localhost";
	$username = "nits1";
	$password = "1234";
	$dbname = "coupondunia";*/

	//$connhandle = new mysqli($servername, $username, $password, $dbname);
	$connhandle = dbconnection :: getinstance() -> connhandle;
	if ($category != null) {
	$query = "select categoryid from couponcategories where name = '" . $category . "'";
	//echo $query;
	//$query = "select couponid from coupon where websiteid = " . $idx;
	$result = $connhandle -> query($query);
	$categoryid = $result -> fetch_assoc()['categoryid'];
	//echo $categoryid;
	}

	/*$query = "select couponcategoryinfo.categoryid, coupon.couponid, coupon.description, website.websiteid, website.websitetitle from couponcategoryinfo ";
	$query = $query . "inner join coupon on couponcategoryinfo.couponid = coupon.couponid and couponcategoryinfo.categoryid = " . htmlspecialchars($categoryid);
	$query = $query . " and coupon.isdeal = " . htmlspecialchars($deal);
	$query = $query . " inner join website on coupon.websiteid = website.websiteid and website.websitetitle = '" . htmlspecialchars($store) . "' limit 60";*/

	
	$query = "select couponcategoryinfo.categoryid, coupon.couponid, coupon.description, website.websiteid, website.websitetitle, coupon.expiry from couponcategoryinfo ";
	$query = $query . "inner join coupon on couponcategoryinfo.couponid = coupon.couponid";

	if (isset($categoryid)) {
		$query = $query . " and couponcategoryinfo.categoryid = " . htmlspecialchars($categoryid);
	}

	if ($deal != -1) {
		$query = $query . " and coupon.isdeal = " . htmlspecialchars($deal);
	}
	$query = $query . " and (date(coupon.expiry) > CURDATE() or (date(coupon.expiry) = CURDATE() and time(coupon.expiry) >= CURTIME()))";
	//$query = $query . " and coupon.isdeal = " . htmlspecialchars($deal);
	//$query = $query . " group by couponcategoryinfo.couponid";
	$query = $query . " inner join website on coupon.websiteid = website.websiteid";

	if ($store != null) {
	$query =  $query . " and website.websitetitle = '" . htmlspecialchars($store) . "'";
	}
	$query = $query . " group by couponcategoryinfo.couponid";	
	$query = $query . " limit 60";




	$result = $connhandle -> query($query);
	echo $connhandle -> error;
	//echo var_dump($result);
	//$row  = $result -> fetch_assoc();
	//echo $row['websitetitle'];
	while ($row = $result -> fetch_assoc()) {
		echo $row['websitetitle'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['description'] . "          " . "&nbsp;&nbsp;&nbsp;" . $row['couponid'] . "&nbsp;&nbsp;&nbsp;" . $row['expiry'];
		echo "<br><br>";
	}

	dbconnection :: close();



	/*SELECT Customers.CustomerName, Orders.OrderID
	FROM Customers
	INNER JOIN Orders
	ON Customers.CustomerID=Orders.CustomerID*/


	/*if (isset($GLOBALS['stores'])) {
		echo true123;
	} else {
		echo rgrrv;
	}
	while($row = $GLOBALS['stores'] -> fetch_assoc()) {
		echo $row['websitetitle'] . " " . " " . $row['WebsiteID']; 
		echo "<br><br>";
	}*/

	/*include 'load_initial_info.php';

	//$x = new info123();
	//&x -> seti(10);
	//echo info123 :: $i;
	//echo info123 :: $i;
	//echo "here";
	$obj = Singleton :: getinstance($store);
	if (isset($obj)) {
		echo "yes";
	} else {
		echo "no";
	}
	if ($obj == null) {
		echo "it is null";
	}





	// -> stores -> fetch_assoc();

	if (isset($abc)) {
		echo "is set";
	} else {
		echo "not set";
	}
	echo $abc['websitetitle'];*/
	//echo var_dump($_SESSION['stores'])."<br>";  
	/*if(isset($_SESSION['stores'])) {
		echo "it is set";
	} else {
		echo "it is not set";
	}
	for ($i = 0; $i < 10; $i++) {
		echo $_SESSION['stores'][$i]; 
	}*/


	//$row = $_SESSION["stores"] -> fetch_assoc();
	//echo $row['websitetitle']; 

?>