<?php
	require_once('model_class.php');
	require_once('view.php');

	$modelobj = new model();
	
	if (isset($_REQUEST['todo'])) {
		if ($_REQUEST['todo'] == "storehint") {
			$q = $_REQUEST['q'];
			$cpntype = $_REQUEST["cpntype"];
			$category = $_REQUEST['category'];
			$website = $modelobj -> getStoreHints($q);
			$hint = $website -> fetch_assoc()['websitetitle'];
			
			if ($hint == null) {
				$hint = "no suggestions";
				$count = null;
			} else {
				$count = $modelobj -> SearchCoupons($cpntype, $hint, $category, true);
				if ($count == null) {
					$count = '0';
				}
			}
			showhints($hint, $count);
		} elseif ($_REQUEST['todo'] == "categoryhint") {
			$q = $_REQUEST['q'];
			$cpntype = $_REQUEST["cpntype"];
			$store = $_REQUEST["store"];
			$category = $modelobj -> getCategoryHints($q);
			$hint = $category -> fetch_assoc()['name'];

			if ($hint == null) {
				$hint = "no suggestions";
				$count = null;
			} else {
				$count = $modelobj -> SearchCoupons($cpntype, $store, $hint, true);
				if ($count == null) {
					$count = '0';
				}
			}
			showhints($hint, $count);
		} elseif ($_REQUEST['todo'] == "search") {
			$store = strtolower ($_REQUEST['store']);
			$cpntype = strtolower ($_REQUEST['cpntype']);
			$category = $_REQUEST['category'];

			$coupons = $modelobj -> SearchCoupons($cpntype, $store, $category, false);
			while($row = $coupons -> fetch_assoc()) {
				show ($row['websitetitle'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['description'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['couponid'].$row['expiry']); 
				show ("<br><br>");
			}

		}
	} else {
		$coupons = $modelobj -> getcoupons(60);
		while($row = $coupons -> fetch_assoc()) {
			show ($row['CouponID'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['WebsiteID'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . $row['Description']); 
			show ("<br><br>");
		}
	}
?>