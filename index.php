<html>
<title> Search Page </title>
<h1> Find Your Coupons </h1>
<body>
	Coupon Type
	<!-- <input type="text" id="coupontype">
	
	
	<br><br> -->
	
	<select id="coupontype">
  <option value="deal">Deals</option>
  <option value="coupon">Coupons</option>
  <option value="both">Both</option>
	</select>
	<br> <br>



	Stores   
	<input type="text" id="stores" onkeyup="showHint(this.value)">
	<p>Suggestions: <span id="txtHint"></span></p>
	<br><br>
	Categories
	<input type="text" id="categories" onkeyup="showHintCat(this.value)"> 
	<p>Suggestions: <span id="txtHintcat"></span></p>
	<br><br>
	<button onclick="search()">Search</button>
	<br><br>
</body>
<?php
	session_start();
	
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);
	require_once('databaseconn.php');
	
	$connhandle = dbconnection :: getinstance() -> connhandle;
	//$connhandle = new mysqli($servername, $username, $password, $dbname);
	//$_SESSION['dbhandle'] = $connhandle;
	if ($connhandle -> connect_error) {
		echo "not connected <br>";
	} else {
	//	echo "connected<br>";
	}

	$query = "SELECT CouponID,WebsiteID,Description from coupon limit 60";
	$result = $connhandle -> query($query);
?>
<p id = "debugtext"> </p>
<h2> These are Coupons <br> </h2>
<p id = "coupons">
	<?php
	while($row = $result -> fetch_assoc()) {
		echo $row['CouponID'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['WebsiteID'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['Description']; 
		echo "<br><br>";
	}
	
	?>
</p>
<?php
	$query = "select categoryid,name from couponcategories";
	$result = $connhandle -> query($query);
	$i = 0;
	while($row = $result -> fetch_assoc()) {
				$categoryid[$i] = $row['categoryid']; 
				$category[$i] = $row['name'];
				//echo "<br><br>";
				$i++;	
	}
	$_SESSION['categories'] = $category;
	$_SESSION['categoryid'] = $categoryid;
	dbconnection :: close();
	//global $stores;
	//$handle = dbconnection :: getinstance() -> connhandle;
	/*$query = "select websiteid,websitetitle from website";
	$result = $connhandle -> query($query);
	//$query = "select websiteid from website";
	//$result2 = $connhandle -> query($query);
	$i = 0;
	while($row = $result -> fetch_assoc()) {
				$websites[$i] = $row['websitetitle']; 
				$websiteid[$i] = $row['websiteid'];
				//echo "<br><br>";
				$i++;	
	}


	$_SESSION["stores"] = $websites;
	$_SESSION['storeid'] = $websiteid;
	//echo var_dump($_SESSION['storeid'])."<br>";  
	if(isset($_SESSION['stores'])) {
		echo "it is set";
	} else {
		echo "it is not set";
	}

	$query = "select categoryid,name from couponcategories";
	$result = $connhandle -> query($query);
	$i = 0;
	while($row = $result -> fetch_assoc()) {
				$categoryid[$i] = $row['categoryid']; 
				$category[$i] = $row['name'];
				//echo "<br><br>";
				$i++;	
	}
	$_SESSION['categories'] = $category;
	$_SESSION['categoryid'] = $categoryid;
	//echo var_dump($_SESSION['categories'])."<br>";

	/*if(isset($GLOBALS['stores'])) {
		echo "yes stores is there";
	} else {
		echo "no stores is not";
	}*/

	

	//include 'load_initial_info.php';


//	echo Singleton::getinstance() -> i

	/*$x = new info123();
	
	$x -> seti(10);
	echo "i in index.php is ";
	echo info123 :: $i;*/

	/*$query = "select distinct websitetitle from website";
	$result = $connhandle -> query ($query);
			
	$x = Singleton :: getinstance($result);
	echo  $x -> i;*/







	/*$connhandle -> close();
	echo "conn closed";*/
	/*
	if ($connhandle -> query($query) === TRUE) {
		echo "yes"; 
	} else {
		echo "no";
	}*/
 
?>


<script> 
	function search() {
    var cpntype = document.getElementById("coupontype").value;
    var store = document.getElementById("stores").value;
    var category = document.getElementById("categories").value;

    //document.getElementById("debugtext").innerHTML = cpntype;

    //document.getElementById("txtHint").innerHTML = "flipkart";
	
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("coupons").innerHTML = xmlhttp.responseText;
         }
    }
    store = store.replace('&', '%26');
    category = category.replace('&', '%26');
    xmlhttp.open("GET", "getSearchResults.php?cpntype=" + cpntype + "&store=" + store + "&category=" + category, true);
    xmlhttp.send();


	}

	function showHint(str) {
		//document.getElementById("txtHint").innerHTML="ok here";
		/*
		if (str.length==0) { 
    		document.getElementById("txtHint").innerHTML="";
    		return;
		} else {
    		var xmlhttp=new XMLHttpRequest();
    		xmlhttp.onreadystatechange=function() {
        	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            	document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
            	//document.getElementById("txtHint").innerHTML="ok bye";
        	}
    	}
    	xmlhttp.open("GET","gethints.php?q="+str,true);
    	xmlhttp.send();
    	*/
    	var cpntype = document.getElementById("coupontype").value;
    	if (str.length == 0) { 
        	document.getElementById("txtHint").innerHTML = "";
        	return;
    	} else {
        	var xmlhttp = new XMLHttpRequest();
        	xmlhttp.onreadystatechange = function() {
            	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                	document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            	}
        	}
        	xmlhttp.open("GET", "gethints.php?q=" + str + "&cpntype=" + cpntype, true);
        	xmlhttp.send();
    	}
	} 

	function showHintCat(str) {
		var cpntype = document.getElementById("coupontype").value;
    	var store = document.getElementById("stores").value;
		if (str.length==0) { 
    		document.getElementById("txtHintcat").innerHTML="";
    		return;
		} else {
    		var xmlhttp=new XMLHttpRequest();
    		xmlhttp.onreadystatechange=function() {
        	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            	document.getElementById("txtHintcat").innerHTML=xmlhttp.responseText;
            	//document.getElementById("txtHint").innerHTML="ok bye";
        	}
    	}
    	//var url = encodeURIComponent("getcathints.php?q=" + str + "&store=" + store + "&cpntype=" + cpntype);
    	str = str.replace('&', '%26');
    	store = store.replace('&', '%26');
    	

    	/*var url = "getcathints.php?q=" + str + "&store=" + store + "&cpntype=" + cpntype;
    	document.getElementById("debugtext").innerHTML=url;*/

    	xmlhttp.open("GET","getcathints.php?q=" + str + "&store=" + store + "&cpntype=" + cpntype,true);
    	xmlhttp.send();
	}   

	}
</script>
</html>


