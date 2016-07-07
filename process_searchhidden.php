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
	
	$query = $db->get_results("select h.hide_id,h.proid,h.userId,h.dated,p.proid,p.pro_name,p.price,p.image_code from ".tbl_hide." as h, ".tbl_product." as p where p.proid = h.proid and h.userId = '".$_SESSION['LOGINDATA']['USERID']."' and p.pro_name like '%".mysql_real_escape_string(stripslashes(trim($keyword)))."%' and h.dated between '".$from_date."' and '".$to_date."'",ARRAY_A);
	if($query) {
	foreach($query as $product) { 
		$get_l = "SELECT count( hide_it ) AS cnt FROM ".tbl_hide." WHERE proid = '".$product['proid']."' GROUP BY proid HAVING Count( hide_it )";
		$hide_count = $db->get_row($get_l,ARRAY_A);
?>
	<li class="record">
		<div class="from">
			<div class="counter owned hidn">
				<div class="counter_inner">
					<img alt="Owned Icon" src="<?php echo ru_resource;?>images/icon_k.jpg" />
				</div>
				<span><?php echo $hide_count{'cnt'}; ?> People Hide It</span>
			</div> 
			<div class="list_img">
				<img src="<?php  get_image($product['image_code']);?>" width="105" height="105" alt="<?php echo ucfirst($product['pro_name']); ?>" />
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
			<div class="squaredFour hiden">
				<input type="checkbox" name="iftwish" id="<?php echo $product['hide_id'];?>">
				<label for="<?php echo $product['hide_id'];?>"></label>
			</div>
			<a href="javascript:;" onclick="del_hiddenlist('<?php echo $product['proid']; ?>','<?php echo $product['hide_id']; ?>');" class="orange unwraped">Reinstate</a>
		</div>
	</li>
<?php } 
	} else { 
?>	
	<span style="margin-left:300px;">There are not results for this search.</span>
<?php } ?>