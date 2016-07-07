<?php
//include_once("../config/config.php");
function get_image_name($pid){
		$result=mysql_query("select img from ".tbl_product." where proid=$pid") or die("select img from ".tbl_product." where proid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['img'];
	}
	function get_product_name($pid){
		$result=mysql_query("select pro_name from ".tbl_product." where proid=$pid") or die("select pro_name from ".tbl_product." where proid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['pro_name'];
	}
	function get_prices($pid){
		$result=mysql_query("select price from ".tbl_product." where proid=$pid") or die("select pro_name from ".tbl_product." where proid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['price'];
	}
	function get_pro_image($pid){
		$result=mysql_query("select image_code from ".tbl_product." where proid=$pid") or die("select pro_name from ".tbl_product." where proid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['image_code'];
	}
	function remove_product($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			if($max == 1) {
				if($pid==$_SESSION['cart'][$i]['proid']){
				//echo $_SESSION['cart'][$i];exit;
					unset($_SESSION['cart']);
					unset($_SESSION['flag']);
					unset($_SESSION['flag_cat']);
					unset($_SESSION['flags']);
					unset($_SESSION['flag_cats']);
					unset($_SESSION['catid']);
					break;
				}
			} else {
				if($pid==$_SESSION['cart'][$i]['proid']){
				//echo $_SESSION['cart'][$i];exit;
					unset($_SESSION['cart'][$i]);
					break;
				}
			}
		}
		$_SESSION['cart']=@array_values($_SESSION['cart']);
	}
	function get_order_total(){
		$max=count($_SESSION['cart']);
		$sum=0;
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['proid'];
			$q=$_SESSION['cart'][$i]['qty'];
			$price=get_prices($pid);
			$sum+=$price*$q;
		}
		return $sum;
	}
	function addtocart($pid,$q){
		if($pid<1 or $q<1) return;
		
		if(is_array($_SESSION['cart'])){
			
			if(product_exists($pid)) return;
			$max=count($_SESSION['cart']);
			if($max < 6) {
			$_SESSION['cart'][$max]['proid']=$pid;
			$_SESSION['cart'][$max]['qty']=$q;
			} else {
			?>
			<script type="text/javascript">
			$(function () {
				$('.overlay').show();
				$('#modal_email').toggle( "slow" );
			});
			</script>
			<div class="overlay" style="display:none"></div>
			<div class="element">
			<div class="modal" id="modal_email" style="display:none"><a style="cursor:pointer" onClick="close_div();"><img src="<?php echo ru_resource; ?>images/close_icon.png" alt="Closed Icon" /></a><img src="<?php echo ru_resource; ?>images/jester_icon_validation.png" alt="Validation Icon"  /><div class="valid_msg">To suggest an additional item you must replace one of the items in your iftCart.</span></div></div>
			</div>
			<?php 
			}
		}
		else{
			$_SESSION['cart']=array();
			$_SESSION['cart'][0]['proid']=$pid;
			$_SESSION['cart'][0]['qty']=$q;
		}
	}
	function product_exists($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		$flag=0;
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['proid']){
				$flag=1;
				break;
			}
		}
		return $flag;
	}

?>