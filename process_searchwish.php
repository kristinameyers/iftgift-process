<?php 
include_once('../connect/connect.php');
include_once('../config/config.php');

//print_r($_REQUEST);
	$keyword = $_GET['key'];
	$day     = $_GET['day'];
	$month     = $_GET['month'];
	$year     = $_GET['year'];
	$days     = $_GET['days'];
	$months     = $_GET['months'];
	$years     = $_GET['years'];
	
	$from_date = $year.'-'.$month.'-'.$day;
	
	$to_date   = $years.'-'.$months.'-'.$days;
	
	$query = $db->get_results("select l.love_id,l.proid,l.userId,l.dated,l.love_number_setting,p.proid,p.pro_name,p.price,p.image_code from ".tbl_love." as l, ".tbl_product." as p where p.proid = l.proid and l.userId = '".$_SESSION['LOGINDATA']['USERID']."' and p.pro_name like '%".mysql_real_escape_string(stripslashes(trim($keyword)))."%' and l.dated between '".$from_date."' and '".$to_date."'",ARRAY_A);
	if($query) {
	foreach($query as $product) { 
		$get_l = "SELECT count( love_it ) AS cnt FROM ".tbl_love." WHERE proid = '".$product['proid']."' GROUP BY proid HAVING Count( love_it )";
		$love_count = $db->get_row($get_l,ARRAY_A);
?>
	<li class="record">
		<div class="from">
			<div class="counter">
				<div class="counter_inner">
					<samp class="minus" id="<?php echo $product['love_id']; ?>"></samp>
					<input id="qty1_<?php echo $product['love_id']; ?>" type="text" value="<?php echo $product['love_number_setting']; ?>" class="qty"/>
					<input id="proid_<?php echo $product['love_id']; ?>" value="<?php echo $product['proid']; ?>" type="hidden"/>
					<samp class="add" id="<?php echo $product['love_id']; ?>"></samp>
				</div>
				<span><?php echo $love_count{'cnt'}; ?> People Love It</span>
			</div> 
			<div class="list_img">
				<img src="<?php  get_image($product['image_code']);?>" width="105" height="105" alt="<?php echo ucfirst($product['pro_name']); ?>" />
				<a href="#" class="orange">Add to Cart</a> 
			</div>
		</div>
		<div class="occas"><?php echo substr(ucfirst($product['pro_name']),0,10); ?></div>
		<div class="occas">$<?php echo number_format($product['price'],2); ?></div>
		<div class="notft">
		<?php 
			$created_timestamp = strtotime($product['dated']);
			$child1 = date('m/d/Y', $created_timestamp);
			echo $child1; 
		?>
		</div>
		<div class="resp_btn">
			<a href="#" class="orange unwraped pink">SEND TO CLIQUE</a> 
			<a href="javascript:;" onclick="del_wishlist('<?php echo $product['proid']; ?>','<?php echo $product['love_id']; ?>');" class="orange unwraped">Remove</a>
		</div>
	</li>
<?php } 
	} else { 
?>	
	<span style="margin-left:300px;">There are not results for this search.</span>
<?php } ?>
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
		var myData = "love_num="+love_num+"&love_id="+love_id+"&num_seting=num_seting";
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
		
		if(currentVal != '' && currentVal != 0 && love_id != '') {
			//alert(currentVal);
			var myData = "love_num="+love_num+"&love_id="+love_id+"&num_seting=num_seting";
			$.ajax({
				url:"<?php echo ru;?>process/process_product.php",
				type: "GET",
				data: myData,
				success:function (response) {
					if(response) {
						location.reload();
					}
				}
			});
		} else if(currentVal == 0) {
			var dId = $("#proid_"+love_id).val();
			var uId = '<?php echo $userId; ?>';
			var loveId = love_id;
			$.ajax({
			url: '<?php echo ru;?>process/process_itemselect.php?dId='+dId+'&uId='+uId+'&loveId='+loveId,
			type: 'get', 
			success: function(output) {
			if(output == 'Success')
			{
				window.location = "<?php echo ru?>wishlist";
			}
			}
			});
		}
	});
});
</script>