<?php
include_once("../connect/connect.php");
include_once("../config/config.php");
mysql_query("SET NAMES 'utf8'");	
	$category = mysql_real_escape_string(stripslashes(trim($_GET['picID'])));
	if(isset($_SESSION['recipit_id']['New'])) {
	$recption_id = $_SESSION['recipit_id']['New'];
	$get_recp = get_recp_info($recption_id);
 	$cash = $get_recp['cash_amount'];
	$gender = $get_recp['gender'];
	$age = $get_recp['age'];
	$ocassion = $get_recp['occassionid'];
	$get_price = get_price2($cash);
	$get_data = get_category($gender,$age);
	$get_occassion_data = get_ocassion($ocassion);

	$chk_users = mysql_query("select userId,email from ".tbl_user." where email = '".$get_recp['email']."'");
	if(mysql_num_rows($chk_users) > 0) {
	$get_userid = mysql_fetch_array($chk_users);
	$uId = $get_userid['userId'];
	$owndata = "and own_id not like '%".$uId."%'";
	$hidedata = "and hide_id not like '%".$uId."%'";
	$lovedata = "and love_id like '%".$uId."%'";
	$query_judemo2 = "Union select * from ".tbl_product." where (status = 1 or status = 0) $get_price $owndata $hidedata and hide_id not like '%".$_SESSION['LOGINDATA']['USERID']."%' $jlovedata";
	}
	if(isset($_SESSION['LOGINDATA']['USERID'])){
		$login_check="and  hide_id not like '%".$_SESSION['LOGINDATA']['USERID']."%'";
	}
 if($_GET['picID']) {
$query_demo = "select * from ".tbl_product." where proid = '".$category."' and (status = 1 or status = 0) $login_check"; 
$view_pro = $db->get_row($query_demo,ARRAY_A);
} else {
	
	$query_demo = "select * from ".tbl_product." where (status = 1 or status = 0) $get_data $get_price $get_occassion_data $owndata $hidedata $login_check";
 
$products = $query_demo." ".$query_judemo2;
 
 $view_pro = $db->get_row($products,ARRAY_A);
}
} else {
if($_GET['picID']) {
$query_demo = "select * from ".tbl_product." where proid = '".$category."' and (status = 1 or status = 0) $login_check"; 
$view_pro = $db->get_row($query_demo,ARRAY_A);
} else {
 $query_demo = "select * from ".tbl_product." where (status = 1 or status = 0) $login_check";
 
$products = $query_demo." ".$query_judemo2;
 
 $view_pro = $db->get_row($products,ARRAY_A);
}
}



/***********************GET NEXT PRODUCT********************************/
 $nxtsql = "SELECT proid FROM gift_product WHERE proid>{$view_pro['proid']} and (status = 1 or status = 0) $login_check";

  $next_pro =  $nxtsql."ORDER BY proid LIMIT 1";
    $result = mysql_query($next_pro);
    if (@mysql_num_rows($result)>0) {
        $nextid = mysql_result($result,0);
    }
/***********************GET NEXT PRODUCT********************************/

/***********************GET PREVIOUS PRODUCT********************************/
 $prevsql = "SELECT proid FROM gift_product WHERE proid<{$view_pro['proid']} and (status = 1 or status = 0) $login_check";

  $prev_pro =  $prevsql."ORDER BY proid DESC LIMIT 1";

    $results = mysql_query($prev_pro);
    if (@mysql_num_rows($results)>0) {
        $previd = mysql_result($results,0);
    }	
/***********************GET PREVIOUS PRODUCT********************************/	 
$count = 6 - count($_SESSION["cart"]);
?>	
  	<div class="sugget_mid" id="product_algo2">
	<?php if(isset($_SESSION['recipit_id']['New']) || isset($_SESSION['DRAFT']['delivery_id'])) { ?>
					<div class="prod_messg">Select up to <span><?php echo $count;?></span> suggestions to go along with your <span>$<?php echo $cash;?></span> cash gift</div>
					<?php } ?>
					<?php if($view_pro) {?>
					<div class="product_bar">
						<div class="left_arrow" id="getPicButton_<?php echo $previd;?>">
							<?php if($previd != '') { ?>
								<img src="<?php echo ru_resource; ?>images/left_arrow.png" alt="Right Arrow" />
							<?php } ?>
						</div>
					<?php if($view_pro) { ?>	
						<div class="prod_img">
							<img src="<?php  get_image($view_pro['image_code']);?>" height="280" alt="<?php echo  $view_pro['pro_name'];?>" />
						</div>
					<?php } ?>	
					<?php if($nextid != '') { ?>	
						<div class="left_arrow right_arrow" id="getPicButton_<?php echo $nextid;?>"> <img src="<?php echo ru_resource; ?>images/right_arrow.png" alt="Right Arrow" /></div>
					<?php } ?>	
					</div>
					<?php } ?>
					<div class="prod_detail">
						<div class="prod_title">
							<h4><?php echo substr($view_pro['pro_name'],0,60);?></h4>
							<p><span>Vendor:</span> <?php echo  $view_pro['vendor'];?>, <span>Category:</span> <?php echo  $view_pro['category'];?>, <?php echo  $view_pro['sub_category'];?></p>
						</div>
						<h4 class="item_price">$<?php echo  number_format($view_pro['price'],2);?></h4>
					</div>
					<div id="intro-wrap3" class="into-wrap4">
						<div class="cat_title">
							<h2>More info</h2>
							<div class="open-intro3" id="open-intro4"><img src="<?php echo ru_resource; ?>images/arrow_a.png" alt="Down Arrow" /></div>
							<div class="close-intro3" id="close-intro4"><img src="<?php echo ru_resource; ?>images/arrow_e.png" alt="Down Arrow" /></div>	
						</div>
						<div id="contentWrap3" class="contentWrap4" style="display:none">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						</div>
					</div>
					<input type="hidden" name="proId" id="proId"/>
					<input type="hidden" name="type" id="type"/>
					<a class="singin" href="javascript:;" id="suggect_items">Suggest this item</a>
					<div class="feedback_option">
						<h4>Leave Feedback (optional)</h4>
						<!---------------------OWN_IT------------------------------->
						<?php
							$get_own = mysql_num_rows(mysql_query("select userId from ".tbl_own." where userId = '".$_SESSION['LOGINDATA']['USERID']."' and proid = '".$view_pro['proid']."'"));
							$get_q = "SELECT count( own_it ) AS cnt FROM ".tbl_own." WHERE proid = '".$view_pro['proid']."' GROUP BY proid HAVING Count( own_it )";
							$view_q = $db->get_row($get_q,ARRAY_A);?>
						<div id="own_its" class="icon_a <?php if($get_own > 0) {?>active<?php } ?>" <?php if($get_own == 0) { ?>onclick="own_it('<?php echo $view_pro['proid'];?>','<?php echo $_SESSION['LOGINDATA']['USERID'];?>','own')" <?php } ?>>
							<span>Own It</span>
							<div class="own_it_icon"></div>
							<?php if($get_own > 0) {?>
							<span class="user_view"><?php echo $view_q{'cnt'}; ?> People Own it</span>
							<?php } ?>
						</div>
						<div id="own_itbtms"></div>
						<!---------------------OWN_IT------------------------------->
						<!---------------------LOVE_IT------------------------------->
						<?php
							$get_love = mysql_num_rows(mysql_query("select * from ".tbl_love." where userId = '".$_SESSION['LOGINDATA']['USERID']."' and proid = '".$view_pro['proid']."'"));
							$rec_love = mysql_fetch_array(mysql_query("select * from ".tbl_love." where userId = '".$_SESSION['LOGINDATA']['USERID']."' and proid = '".$view_pro['proid']."'"));
							$get_l = "SELECT count( love_it ) AS cnt FROM ".tbl_love." WHERE proid = '".$view_pro['proid']."' GROUP BY proid HAVING Count( love_it )";
							$view_l = $db->get_row($get_l,ARRAY_A);
							if($get_love > 0 && $rec_love['love_number_setting'] != 0) {
							?>
							<div class="icon_b active">
								<div class="counter">
								<span>Love It</span>
								<div class="counter_inner">
									<samp class="minus" id="<?php echo $rec_love['love_id']; ?>"></samp>
									<input id="qty1_<?php echo $rec_love['love_id']; ?>" type="text" disabled="disabled" value="<?php echo $rec_love['love_number_setting']; ?>" class="qty"/>
									<input id="proid_<?php echo $rec_love['love_id']; ?>" value="<?php echo $rec_love['proid']; ?>" type="hidden"/>
									<samp class="add" id="<?php echo $rec_love['love_id']; ?>"></samp>
								</div>
								<span class="user_view"><?php echo $view_l{'cnt'}; ?> People Love It</span>
							</div>
							</div>
							<?php } else { ?>
						<div id="love_its" class="icon_b" onclick="love_it('<?php echo $view_pro['proid'];?>','<?php echo $_SESSION['LOGINDATA']['USERID'];?>','love')" >
							<span>Love It</span>
							<div class="love_it_icon"></div>
						</div>
						<?php } ?>
						<div id="love_itbtms"></div>
						<!---------------------LOVE_IT------------------------------->
						<!---------------------HIDE_IT------------------------------->
						<?php
							$get_hide = mysql_num_rows(mysql_query("select userId from ".tbl_hide." where userId = '".$_SESSION['LOGINDATA']['USERID']."' and proid = '".$view_pro['proid']."'"));
							$get_h = "SELECT count( hide_it ) AS cnt FROM ".tbl_hide." WHERE proid = '".$view_pro['proid']."' GROUP BY proid HAVING Count( hide_it )";
							$view_h = $db->get_row($get_h,ARRAY_A);?>
						<div id="hide_its" class="icon_c <?php if($get_hide > 0) {?>active<?php } ?>" <?php if($get_hide == 0) { ?>onclick="hide_it('<?php echo $view_pro['proid'];?>','<?php echo $_SESSION['LOGINDATA']['USERID'];?>','hide')" <?php } ?>>
							<span>Hide It</span>
							<div class="hide_it_icon"></div>
							<?php if($get_hide > 0) {?>
							<span class="user_view"><?php echo $view_h{'cnt'}; ?> People Hide it</span>
							<?php } ?>
						</div>
						<div id="hide_itbtms"></div>
						<!---------------------HIDE_IT------------------------------->
					</div>
				</div>
<script type="text/javascript">
$(document).ready(function() {
$(".left_arrow").on("click", function() {
	var myPictureId = $(this).attr('id');
	var getImgId =  myPictureId.split("_");
	getPicture(getImgId[1]); 
	return false;
});
});

function getPicture(myPicId)
{
var myData = 'picID='+myPicId;
jQuery.ajax({
    url: "<?php echo ru;?>process/get_jesterproduct.php",
	type: "GET",
    dataType:'html',
	data:myData,
    success:function(response)
    {
		$('#prev_nxt_product').html(response);
		//$('#no_cart_btn').hide();
    }
    });
}

function own_it(proid,uid,type)
{
	var proId = proid;
	var userId = uid;
	var type = type;
	$.ajax({
	url: '<?php echo ru;?>process/process_product.php?proid='+proId+'&userId='+userId+'&type='+type,
	type: 'get', 
	success: function(output) {
	$('#own_its').hide();
	$('#own_itbtms').html(output);
	}
	});
}

function love_it(proid,uid,type)
{
	var proId = proid;
	var userId = uid;
	var type = type;
	$.ajax({
	url: '<?php echo ru;?>process/process_product.php?proid='+proId+'&userId='+userId+'&type='+type,
	type: 'get', 
	success: function(output) {
	$('#love_its').hide();
	$('#love_itbtms').html(output);
	}
	});
}

function hide_it(proid,uid,type)
{
	var proId = proid;
	var userId = uid;
	var type = type;
	$.ajax({
	url: '<?php echo ru;?>process/process_product.php?proid='+proId+'&userId='+userId+'&type='+type,
	type: 'get', 
	success: function(output) {
	$('#hide_its').hide();
	//$('#hide_itbtms').html(output);
	window.location = "<?php echo ru;?>step_2a";
	}
	});
}

/***********************SUGGECT ITEM******************************/
$(function () {
	$('#suggect_items').on('click',function () {
		$('#proId').val('<?php echo $view_pro['proid'];?>');
		$('#type').val('add');
		var myData = 'proid=<?php echo $view_pro['proid'];?>&type=add';
		$.ajax({
			url: "<?php echo ru;?>process/process_cart.php",
			type: "GET",
			data: myData,
			success:function(output) {
				$('#cart_suggest').html(output);
				$('#no_cart').hide();
				$('#no_cart_btn').hide();
			}
		});
	});
});

$('#open-intro4').click(function() {
		$('.intro-wrap4').animate({
		//opacity: 1,
		
	  }, function(){
		// Animation complete.
	  });
		$('#open-intro4').hide();
		$('#close-intro4').show();
		$('.contentWrap4').slideUp('fast');
	});
	$('#close-intro4').click(function() {
		$('.intro-wrap4').animate({
		//opacity: 0.25,
		
	  }, function() {
		// Animation complete.
	  });
		$('#open-intro4').show();
		$('#close-intro4').hide();
		$('.contentWrap4').slideDown('slow');
	});
</script>

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