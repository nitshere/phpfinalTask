<?php
	require_once('databaseconn.php');

	class model {
		private $connhandle;

		public function getcoupons($num = 60) {
			//$query = "select coupon"
			
			$query = "SELECT CouponID,WebsiteID,Description from coupon where"; 
			$query = $query . " (date(coupon.expiry) > CURDATE() or (date(coupon.expiry) = CURDATE() and time(coupon.expiry) >= CURTIME()))";
			if (is_numeric($num)) {
				$query = $query . " limit " . $num;
			} else {
				$query = $query . " limit 60";
			}
			$connhandle = dbconnection :: getinstance() -> connhandle;
			$result = $connhandle -> query($query);
			echo $connhandle -> error;
			return $result;
		}

		public function SearchCoupons($cpntype, $store, $category, $givecount = false) {
			if ($cpntype == "deal") {
				$deal = 1;
			} else if ($cpntype == "coupon"){
				$deal = 0;
			} else {
				$deal = -1;
			}
			if ($givecount) {
				$query = "select count(couponcategoryinfo.couponid) from couponcategoryinfo ";
			} else {
				$query = "select couponcategoryinfo.categoryid, coupon.couponid, coupon.description, website.websiteid, website.websitetitle, coupon.expiry from couponcategoryinfo ";
			}

			$query = $query . "inner join coupon on couponcategoryinfo.couponid = coupon.couponid";

			if (isset($category) && $category != null) {
				//echo "category set";
				$query = $query . " and couponcategoryinfo.categoryid in ";
				$query = $query . " (select categoryid from couponcategories where name = '" . htmlspecialchars($category) . "')"; 
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
			if (!$givecount) {
				$query = $query . " limit 60";
			} else {
				$query = $query . "";
			}
			//echo $query;
			$connhandle = dbconnection :: getinstance() -> connhandle;
			//echo $query;
			$result = $connhandle -> query($query);
			echo $connhandle -> error;
			
			if ($givecount) {
				//echo var_dump($result -> fetch_assoc()['count(couponcategoryinfo.couponid)']);
				//return $result -> fetch_assoc()['count(couponcategoryinfo.couponid)'];
				return mysqli_num_rows($result);
			} else {	
				return $result;
			}
		}

		public function getStoreHints ($q) {
			$query = "select websiteid,websitetitle from website where websitetitle LIKE '" . htmlspecialchars($q) . "%'" . " limit 1";
			$dbinstance = dbconnection :: getinstance();
			$connhandle  = $dbinstance -> connhandle;
			$result = $connhandle -> query($query);
			return $result;
		}

		public function getCategoryHints($q) {
			$query = "select categoryid,name from couponcategories where name LIKE '" . htmlspecialchars($q) . "%'" . " limit 1";
			$dbinstance = dbconnection :: getinstance();
			$connhandle  = $dbinstance -> connhandle;
			$result = $connhandle -> query($query);
			return $result;
		}
	}
?>
