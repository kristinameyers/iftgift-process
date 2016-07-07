<?php
include_once("../connect/connect.php");
include_once("../config/config.php");
if(isset($_GET['type']) && $_GET['type']=='own')
{
	
	$proId = filter_var($_GET['proid'], FILTER_SANITIZE_STRING);
	$userId = filter_var($_GET['userId'], FILTER_SANITIZE_STRING);
	
	//echo $query = "SELECT * FROM ".tbl_own." WHERE userId = '".$userId."' AND proid = '".$proId."'";
 	$inst = mysql_query("INSERT INTO ".tbl_own." SET own_it = '1', proid = '".$proId."', userId = '".$userId."', dated = now()");
	
	$query = mysql_fetch_array(mysql_query("SELECT * FROM ".tbl_product." WHERE proid = '".$proId."'"));
	$ownId = $query['own_id'];
 	if($ownId == '0' || $ownId == '')
	{
		$upd_qry = mysql_query("UPDATE ".tbl_product." SET status = '1', own_id = '".$userId."' WHERE proid = '".$proId."'");
	} 
	else {
		
		$Id = $ownId.','.$userId;
	   //$cusId =	rtrim($id, ', ');
	   $upd_qry = mysql_query("UPDATE ".tbl_product." SET status = '1', own_id = '".$Id."' WHERE proid = '".$proId."'");
	}
	
	if($inst)
	{
		/*--------------Function Used For Gift Own It Points-------------------*/
		user_own_points($userId);
		/*--------------Function Used For Gift Own It Points-------------------*/
	$get_q = "SELECT count( own_it ) AS cnt FROM ".tbl_own." WHERE proid = '".$proId."' GROUP BY proid HAVING Count( own_it )";
	$view_q = $db->get_row($get_q,ARRAY_A);
	?>
		<div class="icon_a active"><span>Own It</span><div class="own_it_icon"></div><span class="user_view"><?php echo $view_q{'cnt'}; ?> People Own it</span></div>
	<?php
	}	
}


if(isset($_GET['type']) && $_GET['type']=='love')
{
	
	$proId = filter_var($_GET['proid'], FILTER_SANITIZE_STRING);
	$userId = filter_var($_GET['userId'], FILTER_SANITIZE_STRING);
	
	
	$query = mysql_query("SELECT * FROM ".tbl_love." WHERE userId = '".$userId."' AND proid = '".$proId."'");
	
	if(@mysql_fetch_array($query) == 0) {
	
 	$inst = mysql_query("INSERT INTO ".tbl_love." SET love_it = '1', proid = '".$proId."', userId = '".$userId."', love_number_setting = '3' , dated = now()");
	$latest_id = mysql_insert_id();
	}
	
	$query = mysql_fetch_array(mysql_query("SELECT * FROM ".tbl_product." WHERE proid = '".$proId."'"));
	$loveId = $query['love_id'];
 	if($loveId == '0' || $loveId == '')
	{
		$upd_qry = mysql_query("UPDATE ".tbl_product." SET status = '1', love_id = '".$userId."' WHERE proid = '".$proId."'");
	} 
	else {
		
		$Id = $loveId.','.$userId;
	   //$cusId =	rtrim($id, ', ');
	   $upd_qry = mysql_query("UPDATE ".tbl_product." SET status = '1', love_id = '".$Id."' WHERE proid = '".$proId."'");
	}
	
	if($inst)
	{
		/*--------------Function Used For Gift Love It Points-------------------*/
		user_love_points($userId);
		/*--------------Function Used For Gift Love It Points-------------------*/
	$get_lrec = @mysql_fetch_array(mysql_query("SELECT * FROM ".tbl_love." WHERE userId = '".$userId."' AND love_id = '".$latest_id."'"));	
	$get_l = "SELECT count( love_it ) AS cnt FROM ".tbl_love." WHERE proid = '".$proId."' GROUP BY proid HAVING Count( love_it )";
	$view_l = $db->get_row($get_l,ARRAY_A);
	?>
	<div class="icon_b active">
		<div class="counter">
			<span>Love It</span>
			<div class="counter_inner">
				<samp class="minus" id="<?php echo $get_lrec['love_id']; ?>"></samp>
				<input id="qty1_<?php echo $get_lrec['love_id']; ?>" disabled="disabled" type="text" value="<?php echo $get_lrec['love_number_setting']; ?>" class="qty"/>
				<input id="proid_<?php echo $get_lrec['love_id']; ?>" value="<?php echo $get_lrec['proid']; ?>" type="hidden"/>
				<samp class="add" id="<?php echo $get_lrec['love_id']; ?>"></samp>
			</div>
			<span class="user_view"><?php echo $view_l{'cnt'}; ?> People Love It</span>
		</div>
	</div>	
		<?php /*?><div class="icon_b active"><span>Love It</span><div class="love_it_icon"></div><span class="user_view"><?php echo $view_l{'cnt'}; ?> People Love it</span></div><?php */?>
	<?php
	}	
		
}

if(isset($_GET['type']) && $_GET['type']=='hide')
{
	
	$proId = filter_var($_GET['proid'], FILTER_SANITIZE_STRING);
	$userId = filter_var($_GET['userId'], FILTER_SANITIZE_STRING);
	
	$query = mysql_fetch_array(mysql_query("SELECT * FROM ".tbl_product." WHERE proid = '".$proId."'"));
	$customer_id = $query['hide_id'];
 	if($customer_id == '0' || $customer_id == '')
	{
		$upd_qry = mysql_query("UPDATE ".tbl_product." SET status = '1', hide_id = '".$userId."' WHERE proid = '".$proId."'");
	} 
	else {
		
		$Id = $customer_id.','.$userId;
	   //$cusId =	rtrim($id, ', ');
	   $upd_qry = mysql_query("UPDATE ".tbl_product." SET status = '1', hide_id = '".$Id."' WHERE proid = '".$proId."'");
	}
	$inst = mysql_query("INSERT INTO ".tbl_hide." SET hide_it = '1', proid = '".$proId."', userId = '".$userId."' , dated = now()");
	if($inst)
	{
	
		/*--------------Function Used For Gift Hide It Points-------------------*/
		user_hide_points($userId);
		/*--------------Function Used For Gift Hide It Points-------------------*/
		
	$get_h = "SELECT count( hide_it ) AS cnt FROM ".tbl_hide." WHERE proid = '".$proId."' GROUP BY proid HAVING Count( hide_it )";
	$get_h = $db->get_row($get_h,ARRAY_A);
	?>
		<div class="icon_c active"><span>Hide It</span><div class="hide_it_icon"></div><span class="user_view"><?php echo $get_h{'cnt'}; ?> People Hide it</span></div>
	<?php
	}
		
}

if(isset($_GET['num_seting']) && $_GET['num_seting']=='num_seting') {
	
		$loveid = $_GET['loveid'];
		$love_num = $_GET['love_num'];
		$pro_id = $_GET['proid'];
		$uId[] = $_GET['uId'];
		if($love_num == 0) {
			$get_pro = $db->get_row("select * from ".tbl_product." where proid = '".$pro_id."'",ARRAY_A);
			$userId = explode(',',$get_pro['love_id']);
			$result = array_diff($userId, $uId);
			$love_ids = implode(',',$result);
			$del = mysql_query("update ".tbl_product." set love_id = '".$love_ids."' where proid = '".$pro_id."'");
			if($del)
			{
				$upd_qry = mysql_query("delete from ".tbl_love." where love_id = '".$loveid."'");
			}	
		} else {
				$upd_qry = mysql_query("UPDATE ".tbl_love." SET love_number_setting = '".$love_num."' WHERE love_id = '".$loveid."'");
		}
		if($upd_qry) {
			echo "success";
		}
}
?>

<script>
$(function () {
	$('.add').on('click',function(event){
		var love_id = this.id;
		var qty=$("#qty1_"+love_id).val();
		var currentVal = parseInt(qty);
		if(currentVal == 5) {
			var love_num = parseInt(currentVal);
			$("#qty1_"+love_id).unbind( event );
		} else if (currentVal == 0) {
			var love_num = parseInt(currentVal + 3);
			$("#qty1_"+love_id).val(love_num);
		}  
		else if (!isNaN(currentVal)) {
			var love_num = parseInt(currentVal + 1);
			$("#qty1_"+love_id).val(love_num);
		}
		
		if((currentVal != '' || currentVal == 0) && love_id != '') {
			//alert(currentVal);
			var myData = "love_num="+love_num+"&loveid="+love_id+"&num_seting=num_seting";
			$.ajax({
				url:"<?php echo ru;?>process/process_product.php",
				type: "GET",
				data: myData,
				success:function (response) {
					if(response) {
						//location.reload();
					}
				}
			});
		}
	});
	
	$('.minus').on('click',function(event){
		var love_id = this.id;
		var qty=$("#qty1_"+love_id).val();
		var currentVal = parseInt(qty);
		if (!isNaN(currentVal) && currentVal > 0) {
			var love_num = parseInt(currentVal - 1);
			$("#qty1_"+love_id).val(love_num);
		}
		
		if(currentVal != '' && love_id != '') {
			var dId = $("#proid_"+love_id).val();
			var uId = '<?php echo $userId; ?>';
			var myData = "love_num="+love_num+"&loveid="+love_id+"&num_seting=num_seting&proid="+dId+"&uId="+uId;
			$.ajax({
				url:"<?php echo ru;?>process/process_product.php",
				type: "GET",
				data: myData,
				success:function (response) {
					if(response && love_num == 0) {
						//alert(love_num ) 
						location.reload();
					}
				}
			});
		} 
	});
});		
</script>