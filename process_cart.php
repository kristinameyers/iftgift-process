<?php
error_reporting(0);
include_once('../connect/connect.php');
include_once('../config/config.php');
include_once('../common/function.php');
include_once("cart_functions.php");
//echo '<pre>';
//print_r($_GET);exit; 
if($_REQUEST['type']=='add' && $_REQUEST['proid']>0){
	 $pid=$_REQUEST['proid']; 
	$userId = $_SESSION['LOGINDATA']['USERID'];
	/*--------------Function Used For Gift Suggestion Points-------------------*/
		user_suggest_points($userId);
	/*--------------Function Used For Gift Suggestion Points-------------------*/
	addtocart($pid,1);

	if(isset($_SESSION['DRAFT']['delivery_id'])){
	$drft=mysql_query("SELECT * From ".tbl_delivery." where delivery_id = '".$_SESSION['DRAFT']['delivery_id']."'");
	if(mysql_num_rows($drft) > 0){
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['proid'];
			 $pro[] = array('proid' => "$pid");	
		}	
		$json = mysql_real_escape_string(json_encode($pro));
		$Qry =mysql_query("update ".tbl_delivery." set step='step_2a',draft = '1', proid ='$json' where delivery_id = '".$_SESSION['DRAFT']['delivery_id']."'");
	}
}

	$max=count($_SESSION['cart']);
	for($i=0;$i<$max;$i++){
	$pid=$_SESSION['cart'][$i]['proid'];
	$q=$_SESSION['cart'][$i]['qty'];
	$pname=get_product_name($pid);
	$image=get_pro_image($pid);
	$imges=get_image_name($pid);
?>
		<div class="sugget_item">
			<?php /*?><img src="<?php  get_image($image);?>" width="92" height="92" alt="Suggection Item A"/><?php */?>
			<?php if($imges != ''){ ?>
					<img src="<?php  echo $imges;?>" width="92" height="92" alt="Suggection Item A"/>
					<?php }else{ ?>
					<img src="<?php  get_image($image);?>" width="92" height="92" alt="Suggection Item A"/>
			<?php } ?>
			<a href="javascript:del(<?php echo $pid?>)"><img src="<?php echo ru_resource; ?>images/close.png" alt="close" class="closed" /></a>
		</div>
		<?php }  if($max == '1') { ?>
			
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		
	<?php } else if($max == '2') { ?>
	<input type="hidden" name="proid" id="proid" value="<?php echo $pid ?>" />
	<div class="sugget_item">
		<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
	</div>
	<div class="sugget_item">
		<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
	</div>
	<div class="sugget_item">
		<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
	</div>
	<div class="sugget_item">
		<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
	</div>
	<?php } else if($max == '3') { ?>
	<div class="sugget_item">
		<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
	</div>
	<div class="sugget_item">
		<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
	</div>
	<div class="sugget_item">
		<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
	</div>
	<?php } else if($max == '4') { ?>
	<input type="hidden" name="proid" id="proid" value="<?php echo $pid ?>" />
	<div class="sugget_item">
		<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
	</div>
	<div class="sugget_item">
		<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
	</div>
	<?php } else if($max == '5') { ?>
	<div class="sugget_item">
		<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
	</div>
	<?php } else if($max == '6') { ?>
	<div class="modal" id="suggestion_6">
		<a style="cursor:pointer" onClick="close_div4();">
			<img src="<?php echo ru_resource; ?>images/close_icon.png" alt="Closed Icon" />
		</a>
		<img src="<?php echo ru_resource; ?>images/jester_at.jpg" alt="Validation Icon"  />
		<div class="valid_msg">You&rsquo;ve chosen 6 suggestions!<br />Let&rsquo;s finish your iftGift and get this show on the road...<br /><br />
			<?php if(!isset($_SESSION['LOGINDATA']['USERID'])){?>
				<?php if($_SESSION['recipit_id']['New'] && $_SESSION['cart']) { ?>
					<a href="javascript:;" onclick="new_registers()" class="orange">Checkout</a>
				<?php } ?>
			<?php } else {?>
				<?php if($_SESSION['recipit_id']['New'] && $_SESSION['cart']) {?>
					<a href="<?php echo ru; ?>delivery_detail">Click to Checkout</a>
				<?php } else if (isset($_SESSION['DRAFT']['delivery_id']) && $_SESSION['cart']){ ?>
					<a href="<?php echo ru; ?>delivery_detail/<?php echo base64_encode($_SESSION['DRAFT']['delivery_id']); ?>">Click to Checkout</a>
				<?php } else {?>
					<a href="<?php echo ru; ?>buildcheckoutshop">Click to Checkout</a>
				<?php } ?>
			<?php } ?>	
		</div>
	</div>
<?php } ?>

		<?php if(!isset($_SESSION['LOGINDATA']['USERID'])){?>
			<?php if($_SESSION['recipit_id']['New'] && $_SESSION['cart']) { ?>
				<a href="javascript:;" onclick="new_registers()" class="orange">Checkout</a>
			<?php } ?>
		<?php } else {?>
			<?php if($_SESSION['recipit_id']['New'] && $_SESSION['cart']) {  ?>
				<a href="<?php echo ru ?>delivery_detail" class="orange">Checkout</a>
				<?php if(isset($_SESSION['LOGINDATA']['USERID'])){?>
				<a href="javascript:;" onclick="SaveDraft(<?php echo $_SESSION['recipit_id']['New']; ?>)" class="orange save_resume">Save & Resume Later</a>
			<?php } } else if (isset($_SESSION['DRAFT']['delivery_id']) && $_SESSION['cart']){ ?>
				<a href="<?php echo ru; ?>delivery_detail/<?php echo base64_encode($_SESSION['DRAFT']['delivery_id']); ?>" class="orange">Checkout</a> 
				<?php if(isset($_SESSION['LOGINDATA']['USERID'])){?>
				<a href="javascript:;" onclick="SaveDraft(<?php echo $_SESSION['DRAFT']['delivery_id']; ?>)" class="orange save_resume">Save & Resume Later</a>
			<?php  } } else { ?>
				<a href="<?php echo ru ?>buildcheckoutshop" class="orange">Checkout</a>
			<?php } ?>
		<?php } ?>
		<div class="overlay" style="display:none"></div>
		<?php if(($_SESSION['recipit_id']['New'] != '' && $max == '1') || ($_SESSION['DRAFT']['delivery_id'] != '' && $max == '1')) { ?>
		<style>
			.modal{top:24%}
		</style>
	<div class="modal" id="suggestion_1">
			<a style="cursor:pointer" href="javascript:;" onclick="close_div3();">
				<img src="<?php echo ru_resource; ?>images/close_icon.png" alt="Closed Icon" />
			</a>
			<img src="<?php echo ru_resource; ?>images/jester_au.jpg" alt="Validation Icon" class="vaild_jester"  />
			<div class="valid_msg">You&rsquo;ve made a great first suggestion!<br />Feel free to add another five.<br /><br />You can also click the &quot;Checkout&quot; button at anytime to complete your iftGift.<br /><br /><a href="javascript:;" onclick="close_div3();">Click here to continue</a></div>
		</div>
	<?php } ?>	
		<div id="modal_checkouts"></div>
<?php
}
if($_REQUEST['type']=='delete' && $_REQUEST['proid']>0){
	remove_product($_REQUEST['proid']);
	$maxx=count($_SESSION['cart']);
	for($i=0;$i<$maxx;$i++){
	$pid=$_SESSION['cart'][$i]['proid'];
	$q=$_SESSION['cart'][$i]['qty'];
	
	$pname=get_product_name($pid);
	$image=get_pro_image($pid);	
	$imges=get_image_name($pid);
?>
	<div class="sugget_item">
		<?php if($imges != ''){ ?>
			<img src="<?php  echo $imges;?>" width="92" height="92" alt="Suggection Item A"/>
		<?php }else{ ?>
			<img src="<?php  get_image($image);?>" width="92" height="92" alt="Suggection Item A"/>
		<?php } ?>
		<?php /*?><img src="<?php  get_image($image);?>" width="92" height="92" alt="Suggection Item A"/><?php */?>
		<a href="javascript:del(<?php echo $pid?>)"><img src="<?php echo ru_resource; ?>images/close.png" alt="close" class="closed" /></a>
	</div>
	<?php } if($maxx == '1') { ?>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
	<?php } else if($maxx == '2') { ?>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
	<?php } else if($maxx == '3') { ?>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
	<?php } else if($maxx == '4') { ?>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
	<?php } else if($maxx == '5') { ?>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
	<?php } else if($maxx == '0' && $_SERVER['HTTP_REFERER'] == ru.'step_2a') { ?>
		<?php /*?><script>
		window.location = '<?php echo ru;?>step_2a';
		</script><?php */?>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
	<?php } else if($maxx == '0' && $_SERVER['HTTP_REFERER'] == ru.'shopproduct') { ?>
		<script>
		window.location = '<?php echo ru;?>shopproduct';
		</script>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>
		<div class="sugget_item">
			<img src="<?php echo ru_resource; ?>images/suggt_item_empty.jpg" alt="Suggection Item A"/>
		</div>	
<?php } ?>
		<?php if(!isset($_SESSION['LOGINDATA']['USERID'])){?>
			<?php if($maxx == '0') { ?>
				<a href="javascript:;" onclick="chk_checkout()" class="orange">Checkout</a>
			<?php } else if($_SESSION['recipit_id']['New'] && $_SESSION['cart']) { ?>
				<a href="javascript:;" onclick="new_registers()" class="orange">Checkout</a>
			<?php } ?>
		<?php } else {?>
			<?php if($maxx == '0') { ?>
			<a href="javascript:;" onclick="chk_checkout()" class="orange">Checkout</a>
			<?php if(isset($_SESSION['LOGINDATA']['USERID'])){?>
			<a href="javascript:;" onclick="chk_checkout()" class="orange save_resume">Save & Resume Later</a>
			<?php } }  else if($_SESSION['recipit_id']['New']) { ?>
			<a href="<?php echo ru ?>delivery_detail" class="orange">Checkout</a>
			<?php if(isset($_SESSION['LOGINDATA']['USERID'])){?>
			<a href="javascript:;" onclick="SaveDraft(<?php echo $_SESSION['recipit_id']['New']; ?>)" class="orange save_resume">Save & Resume Later</a>
			<?php } } else if($_SESSION['DRAFT']['delivery_id']) {?>
			<a href="<?php echo ru ?>delivery_detail/<?php echo base64_encode($_SESSION['DRAFT']['delivery_id']); ?>" id="no_cart_btns" class="orange">Checkout</a>
			<?php if(isset($_SESSION['LOGINDATA']['USERID'])){?>
			<a href="javascript:;" onclick="SaveDraft(<?php echo $_SESSION['DRAFT']['delivery_id'] ; ?>)" class="orange save_resume">Save & Resume Later</a>
			<?php }}  else { ?>
			<a href="<?php echo ru ?>buildcheckoutshop" class="orange">Checkout</a>
		<?php } }?>
<?php
} 	
?>

<?php /*?><?php  if($max == '2' || $max == '4') { ?>
<script type="text/javascript">
	$(function () {
		$.ajax({
			url: "<?php echo ru;?>process/get_cartquestion.php",
			type: "POST",
			success:function(output) {
				$('.overlay').show();
				$('#modal_checkouts').html(output);
			}
		});
	});
</script>
<?php } else<?php */?> <?php if(($_SESSION['recipit_id']['New'] != '' && $max == '1') || ($_SESSION['DRAFT']['delivery_id'] != '' && $max == '1')) { ?>
<script type="text/javascript">
	$(function () {
		$('.overlay').show();
		$('#suggestion_1').slideDown(2000);
	});
	
	function close_div3() {
		$('#suggestion_1').slideUp();
		$('.overlay').hide();
	}
</script>
<?php } else if($max == '6') { ?>
<script type="text/javascript">
	$(function () {
		$('.overlay').show();
		$('#suggestion_6').slideDown(2000);
	});
	
	function close_div4() {
		$('#suggestion_6').slideUp();
		$('.overlay').hide();
	}
</script>
<?php } ?>
<?php  if($max == '2' || $max == '4') { ?>
<style>
.modal .ui-slider-horizontal .ui-slider-handle{ float:none; display:table}
.modal .ques_rangebar a.ui-slider-handle{margin:18px 0 0 -0.45em !important}
.modal .ui-slider-pip-last span.ui-slider-label{margin-left:0; width:auto; text-align:left}
</style>		
<?php } ?>


