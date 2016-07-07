<?php	$page = explode('/',$_SERVER['HTTP_REFERER']);
	include_once("../connect/connect.php");
	include_once("../config/config.php");
	mysql_query("SET NAMES 'utf8'");
	if(isset($_SESSION['recipit_id']['New'])) { 
		$recption_id = $_SESSION['recipit_id']['New'];
		$get_recp = get_recp_info($recption_id);
		$cash = $get_recp['cash_amount'];	
		if(isset($_SESSION['LOGINDATA']['USERID'])){
			$subcat_check="and  hide_id not like '%".$_SESSION['LOGINDATA']['USERID']."%'";
		}
		if(isset($_GET['picId'])) {
		$product_id = mysql_real_escape_string(stripslashes(trim($_GET['picId'])));
		$query_demo = "select * from ".tbl_product." where proid = '".$product_id."' and (status = 1 or status = 0) $subcat_check";
		$view_pro = $db->get_row($query_demo,ARRAY_A);
		$query_demos = "select * from ".tbl_product." where sub_category = '".mysql_real_escape_string(stripslashes(trim($view_pro['sub_category'])))."' and (status = 1 or status = 0)  $subcat_check";
		$view_product = $view_pro['sub_category'];
		} else if(isset($_GET['scatid'])) {
		$category = mysql_real_escape_string(stripslashes(trim($_GET['scatid'])));
		$categorys = mysql_query("select cat_name from ".tbl_category." where catid = '".$category."' and p_catid != '0'");
		$view_products = mysql_fetch_array($categorys);
		$view_product = $view_products['cat_name'];
		$query_demos = "select * from ".tbl_product." where sub_category = '".mysql_real_escape_string(stripslashes(trim($view_products['cat_name'])))."' and (status = 1 or status = 0) $subcat_check";
		$view_pro = $db->get_row($query_demos,ARRAY_A);
		}
	} else  { 
		$recption_id = $_SESSION['DRAFT']['delivery_id'];
		$get_recp = get_recp_info($recption_id);
		$cash = $get_recp['cash_amount'];	
		if(isset($_GET['picId'])) {
		$product_id = mysql_real_escape_string(stripslashes(trim($_GET['picId'])));
		$query_demo = "select * from ".tbl_product." where proid = '".$product_id."' and (status = 1 or status = 0) and  hide_id not like '%".$_SESSION['LOGINDATA']['USERID']."%'";
		$view_pro = $db->get_row($query_demo,ARRAY_A);
		$query_demos = "select * from ".tbl_product." where sub_category = '".mysql_real_escape_string(stripslashes(trim($view_pro['sub_category'])))."' and (status = 1 or status = 0)  and  hide_id not like '%".$_SESSION['LOGINDATA']['USERID']."%'";
		$view_product = $view_pro['sub_category'];
		} else if(isset($_GET['scatid'])) {
		$category = mysql_real_escape_string(stripslashes(trim($_GET['scatid'])));
		$categorys = mysql_query("select cat_name from ".tbl_category." where catid = '".$category."' and p_catid != '0'");
		$view_products = mysql_fetch_array($categorys);
		$view_product = $view_products['cat_name'];
		$query_demos = "select * from ".tbl_product." where sub_category = '".mysql_real_escape_string(stripslashes(trim($view_products['cat_name'])))."' and (status = 1 or status = 0)  and  hide_id not like '%".$_SESSION['LOGINDATA']['USERID']."%'";
		$view_pro = $db->get_row($query_demos,ARRAY_A);
		}
	}
/***********************GET NEXT PRODUCT********************************/
 $nxtsql = "SELECT proid FROM gift_product WHERE proid>{$view_pro['proid']} and (status = 1 or status = 0) $subcat_check";
  $next_pro =  $nxtsql."ORDER BY proid LIMIT 1";
    $result = mysql_query($next_pro);
    if (@mysql_num_rows($result)>0) {
        $nextid = mysql_result($result,0);
    }
/***********************GET NEXT PRODUCT********************************/

/***********************GET PREVIOUS PRODUCT********************************/
 $prevsql = "SELECT proid FROM gift_product WHERE proid<{$view_pro['proid']} and (status = 1 or status = 0) $subcat_check";
  $prev_pro =  $prevsql."ORDER BY proid DESC LIMIT 1";
    $results = mysql_query($prev_pro);
    if (@mysql_num_rows($results)>0) {
        $previd = mysql_result($results,0);
    }	
/***********************GET PREVIOUS PRODUCT********************************/
$count = 6 - count($_SESSION["cart"]);	 
?>	

<div class="loaderr" >
   <center>
       <img class="loading-image" src="<?php echo ru_resource; ?>images/spinner2.gif" alt="loading.." >
   </center>
</div>
 <div class="overlayz" style="display:none"></div>


  <div class="sugget_mid" id="product_algo">
  <?php if(isset($_SESSION['recipit_id']['New']) || isset($_SESSION['DRAFT']['delivery_id'])) {  ?>
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
						<?php if($view_pro['img'] != ''){ ?>
							<img src="<?php echo   $view_pro['img'];?>" height="280" alt="<?php echo  $view_pro['pro_name'];?>" />
							<?php }else{ ?>
							<img src="<?php  get_image($view_pro['image_code']);?>" height="280" alt="<?php echo  $view_pro['pro_name'];?>" />
						<?php } ?>
							<?php /*?><img src="<?php  get_image($view_pro['image_code']);?>" height="280" alt="<?php echo  $view_pro['pro_name'];?>" /><?php */?>
						</div>
					<?php } ?>	
					<?php if($nextid != '') { ?>	
						<div class="left_arrow right_arrow" id="getPicButton_<?php echo $nextid;?>"> <img src="<?php echo ru_resource; ?>images/right_arrow.png" alt="Right Arrow" /></div>
					<?php } ?>	
					</div>
					<div class="prod_detail">
						<div class="prod_title">
							<h4><?php echo substr($view_pro['pro_name'],0,50).'...';?></h4>
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
						<?php if($view_pro['description'] != ''){ ?>
						<div id="contentWrap3" class="contentWrap4" style="display:none">
						<p><?php echo $view_pro['description']; ?></p>
						</div>
						<?php } ?>
					</div>
					<input type="hidden" name="proId" id="proId"/>
					<input type="hidden" name="type" id="type"/>
					<?php  include('../inc/tbl_ext.php');
				 		 $test=$_SERVER['HTTP_REFERER'];
						 $getrs = explode("/",$test);  //echo $getrs[3];
						 $url_iamges=$view_pro['image_code'];
						 $affiliate_URLS  =getdataA2('<a href= ',' ><IMG',$url_iamges); 
						
						 if($getrs[3]== 'shopproduct') {?>
						<!--<a class="singin" href="javascript:;" id="suggect_itemss">Purchase</a>-->
							<?php if($view_pro['img'] != '') { ?>
								<a href="<?php echo $view_pro['image_code']; ?>"  target="_blank">Purchase</a>	
							<?php }else{?>
								<a href="<?php echo $affiliate_URLS; ?>"  target="_blank">Purchase</a>	
							<?php } ?>
						<?php } else if(isset($_SESSION['SHOPPRODUCT']) && $_SESSION['recipit_id']['New'] == '' && $_SESSION['DRAFT']['delivery_id'] == '' ){ ?>
							<!--<a class="singin" href="javascript:;" id="suggect_itemss">Purchase</a>-->
							<?php if($view_pro['img'] != '') { ?>
								<a href="<?php echo $view_pro['image_code']; ?>"  target="_blank">Purchase</a>	
							<?php }else{?>
								<a href="<?php echo $affiliate_URLS; ?>"  target="_blank">Purchase</a>	
							<?php } ?>
						<?php }  else {?>	
							<a class="singin" href="javascript:;" id="suggect_itemss">Suggest this item</a>
						<?php }  ?>				
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
					<?php } ?>
					<?php if($view_pro == 0) {?>
				<div class="sugget_mid" id="message_div">
					<div class="feedback_option jester_img">
						<img src="<?php echo ru_resource; ?>images/jester_aa.jpg" alt="Jester Image"/>
					</div>
				</div>	
				<?php } ?>	
					<div class="show_option control_item">
		<h4>Show <span class="i">i</span><span class="f">f</span><span class="t">t</span> Wish Items</h4>
		<div class="terms">
			<img src="<?php echo ru_resource; ?>images/heart_icon_a.jpg" alt="Heart Icon"/>
			<div class="item_check">
				<div class="squaredFour">
					<input type="radio" value="mine" <?php if($page == 'mine') { ?> checked="checked" <?php } ?> id="thirteen" name="iftwish" class="mine" />
					<label for="thirteen"></label>
				</div>
				<label class="title">Mine</label>
			</div>
		</div>
		<div class="terms terms_b">
			<img src="<?php echo ru_resource; ?>images/heart_icon_b.jpg" alt="Heart Icon"/>
			<div class="item_check">
				<div class="squaredFour">
					<input type="radio" value="theirs" <?php if($page == 'theris') { ?> checked="checked" <?php } ?> id="fourteen" name="iftwish" class="theris" />
					<label for="fourteen"></label>
				</div>
				<label class="title">Theirs</label>
			</div>
		</div>
	</div>
				</div>
				<?php	//$page[3] == 'search_result' || $page[3] == 'theris'  || $page[3] == 'mine'
					if($page[3] == 'search_result' || $page[3] == 'theris'  || $page[3] == 'mine'){ 
						if($view_pro) {
				?>
				<div class="sugget_left sugget_right">
					<div class="show_option">
						<div class="cat_title">
							<h2><span>Category:</span> <?php echo ucfirst($view_product); ?></h2>
						</div>
						<span class="view">Click image to view item:</span>
						<!-- content -->
						<ul class="content mCustomScrollbar">
							<?php
								$view_allproduct = $db->get_results($query_demos,ARRAY_A);
								if($view_allproduct) {
								foreach($view_allproduct as $allproducts) {
							?>
							<li onclick="get_product2('<?php echo $allproducts['proid']; ?>')"><?php if($allproducts['img'] != ''){ ?><img src="<?php echo $allproducts['img']; ?>" alt="<?php echo $allproducts['pro_name']; ?>" width="92" height="92" id="countButton_<?php echo $allproducts['proid']; ?>"/><?php }else{ ?><img src="<?php echo get_image($allproducts['image_code']); ?>" alt="<?php echo $allproducts['pro_name']; ?>" width="92" height="92" id="countButton_<?php echo $allproducts['proid']; ?>"/><?php } ?><?php /*?><img src="<?php get_image($allproducts['image_code']); ?>" alt="<?php echo $allproducts['pro_name']; ?>" width="92" height="92" id="countButton_<?php echo $allproducts['proid']; ?>" /><?php */?></li>
							<?php } } ?>	
						</ul>
					</div>
				</div>
				<?php } } ?>
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
var myData = 'picId='+myPicId;
jQuery.ajax({
    url: "<?php echo ru;?>process/get_subcatpro.php",
	type: "GET",
    dataType:'html',
	data:myData,
    success:function(response)
    {
		$('#prev_nxt_product').html(response);
		$('#load_product').hide();
    }
    });
}

function get_product2(pid)
{ 
	var count = $.cookie('count');
	
	if (count != null)
	{
		count = parseInt(count) + 1;
		$.cookie("count", count, {
		   expires : date           
		});
	}
	
	var proId = pid;
	
	$.ajax({
		url: '<?php echo ru;?>process/get_subcatpro.php?picId='+proId,
		type: 'get', 
		success: function(output) {
			$('#prev_nxt_product').html(output);
			$('#product_algo').hide();
			$("#countButton_"+proId).addClass("active_border");
			if ((count == 5 || count == 10)) {
				$('.overlay').show();
				$('#schedule_awards_div').slideDown();
				setTimeout(function() { 
					$('#schedule_awards_div').slideUp();
					$('.overlay').hide();
					/*$.ajax({
					url: "<?php echo ru;?>process/get_cartquestion.php",
					type: "POST",
					success:function(output) {
						$('#modal_checkouts').html(output);
					}
				});*/
				}, 10000);
				return false;
			}
		}
	});
}

/***********************SUGGECT ITEM******************************/
$(function () {
	$('#suggect_itemss').on('click',function () {
		$('#proId').val('<?php echo $view_pro['proid'];?>');
		$('#type').val('add');
		var maxcount = '<?php echo count($_SESSION['cart']);?>';
		var myData = 'proid=<?php echo $view_pro['proid'];?>&type=add';
		$.ajax({
			url: "<?php echo ru;?>process/process_cart.php",
			type: "GET",
			data: myData,
			success:function(output) {
					$('#cart_suggest').html(output);
					$('#load_product').hide();
					$('#no_cart').hide();
					$('#no_cart_btn').hide();
					$('#no_cart_btn2').hide();
					$('#no_cart_btn3').hide();	
			},
			beforeSend: function(){
					$('.overlayz').show();
					$('.loaderr').show();  
					  
			},
			complete: function(){
					$('.loaderr').hide();
					$('.overlayz').hide();  			
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
