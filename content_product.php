<?php
	//print_r($_SESSION);
	//unset($_SESSION['cart']);
	include_once("../connect/connect.php");
	include_once("../config/config.php");
	if(isset($_SESSION['recipit_id']['New'])){ echo "abc new";
		$recption_id = $_SESSION['recipit_id']['New'];
		$get_recp = get_recp_info($recption_id);
 		$cash = $get_recp['cash_amount'];
		$gender = $get_recp['gender'];
		$age = $get_recp['age'];
		$ocassions = $get_recp['occassionid'];
	}  else if(isset($_SESSION['DRAFT'])) { //echo "abc Draft";
		$recption_id = $_SESSION['DRAFT']['delivery_id'];
		$get_recp = get_recp_info($recption_id);
 		$cash = $get_recp['cash_amount'];
		$gender = $get_recp['gender'];
		$age = $get_recp['age'];
		$ocassions = $get_recp['occassionid'];
	}
 	
	if($_SESSION['cart']) { //echo "abc Cart";
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['proid'];
			$cart_query = "AND proid > $pid";
		}	
	}
	
	$getoccss = explode("_",$ocassions);
	if($ocassions == 'other_'.$getoccss[1]){
		$ocassion = $getoccss[1];
	}else{ 
		$ocassion = $ocassions;
	}
	$get_occs = @mysql_fetch_array(mysql_query("select occasion_name from ".tbl_occasion." where status = 1 and occasionid = '".$ocassion."'"));
	if($get_occs['occasion_name'] == 'Newly Born' || $get_occs['occasion_name'] == 'Expecting') {} else {
		$get_data = get_category($gender,$age);
		$get_price = get_price($cash);
	}
	$get_occassion_data = get_ocassion($ocassion);
	
	$chk_users = mysql_query("select userId,email from ".tbl_user." where email = '".$get_recp['email']."'");
	if(mysql_num_rows($chk_users) > 0) {
		$get_userid = mysql_fetch_array($chk_users);
		$uId = $get_userid['userId'];
		$owndata = "and own_id not like '%".$uId."%'";
		$hidedata = "and hide_id not like '%".$uId."%'";
		$lovedata = "and love_id like '%".$uId."%'";
		$query_udemo = "Union select * from ".tbl_product." where (status = 1 or status = 0) $get_data $get_price $owndata $hidedata $lovedata ORDER BY FIND_IN_SET('".$uId."',love_id) DESC";
	}
	if(isset($_SESSION['LOGINDATA']['USERID'])){
		$login_check="and (own_id not like '%".$_SESSION['LOGINDATA']['USERID']."%' and hide_id not like '%".$_SESSION['LOGINDATA']['USERID']."%')";
	}
	 $query_demo = "select * from ".tbl_product." where (status = 1 or status = 0) $get_data $cart_query $get_price $get_occassion_data $login_check";
	
 	$select_products =  $query_demo." ".$query_udemo;
	$view_product = $db->get_row($select_products,ARRAY_A);	
/***********************GET NEXT PRODUCT********************************/
	 $nxtsql = "SELECT proid FROM ".tbl_product." WHERE proid>{$view_product['proid']} $get_data $get_price $get_occassion_data and (status = 1 or status = 0) $login_check";
 	if(@mysql_num_rows($chk_users) > 0) {
		if(isset($_SESSION['LOGINDATA']['USERID'])){
			$login_check2="and hide_id not like '%".$_SESSION['LOGINDATA']['USERID']."%'";
		}
 		$nxtsqls = "UNION SELECT proid FROM ".tbl_product." WHERE proid>{$view_product['proid']} $get_data $get_price and (status = 1 or status = 0) $owndata $hidedata $login_check2 $lovedata ORDER BY proid LIMIT 1";
 	}
 	$next_pro =  $nxtsql." ".$nxtsqls;
    $result = mysql_query($next_pro);
    if (@mysql_num_rows($result)>0) {
        $nextid = mysql_result($result,0);
    }
/***********************GET NEXT PRODUCT********************************/

/***********************GET PREVIOUS PRODUCT********************************/
	$prevsql = "SELECT proid FROM ".tbl_product." WHERE proid<{$view_product['proid']} $get_data $get_price $get_occassion_data and (status = 1 or status = 0) $login_check";
	if(@mysql_num_rows($chk_users) > 0) {
		$prevsqls = "UNION SELECT proid FROM ".tbl_product." WHERE proid<{$view_product['proid']} $get_data $get_price and (status = 1 or status = 0) $owndata $hidedata $login_check2 $lovedata ORDER BY proid DESC LIMIT 1";
	}
$prev_pro =  $prevsql." ".$prevsqls;
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
 <div class="overlay" style="display:none"></div>

<div class="prod_messg">Select up to <span><?php echo $count;?></span> suggestions to go along with your <span>$<?php echo $cash;?></span> cash gift</div>
<?php	
	if($view_product) {
?>
		<div class="product_bar">
			<div class="left_arrow" id="getPicButton_<?php echo $previd;?>">
				<?php if($previd != '') { ?>
					<img src="<?php echo ru_resource; ?>images/left_arrow.png" alt="Right Arrow" />
				<?php } ?>
			</div>
		<?php if($view_product) { ?>	
			<div class="prod_img">
			<?php if($view_product['img'] != ''){ ?>
				<img src="<?php echo $view_product['img'];?>" height="280" alt="<?php echo  $view_product['pro_name'];?>" />
				<?php }else{ ?>
				<img src="<?php  get_image($view_product['image_code']);?>" height="280" alt="<?php echo  $view_product['pro_name'];?>" />
			<?php } ?>
				<?php /*?><img src="<?php  get_image($view_product['image_code']);?>" height="280" alt="<?php echo  $view_product['pro_name'];?>" /><?php */?>
			</div>
		<?php } ?>	
		<?php if($nextid != '') { ?>	
			<div class="left_arrow right_arrow" id="getPicButton_<?php echo $nextid;?>"> <img src="<?php echo ru_resource; ?>images/right_arrow.png" alt="Right Arrow" /></div>
		<?php } ?>	
		</div>
		<div class="prod_detail">
			<div class="prod_title">
				<h4><?php echo substr($view_product['pro_name'],0,50).'...';?></h4>
				<p><span>Vendor:</span> <?php echo  $view_product['vendor'];?>, <span>Category:</span> <?php echo  $view_product['category'];?>, <?php echo  $view_product['sub_category'];?></p>
			</div>
			<h4 class="item_price">$<?php echo  number_format($view_product['price'],2);?></h4>
		</div>
		<div id="intro-wrap3">
			<div class="cat_title">
				<h2>More info</h2>
				<div class="open-intro3"><img src="<?php echo ru_resource; ?>images/arrow_a.png" alt="Down Arrow" /></div>
				<div class="close-intro3"><img src="<?php echo ru_resource; ?>images/arrow_e.png" alt="Down Arrow" /></div>	
			</div>
			<?php if($view_pro['description'] != ''){ ?>
			<div id="contentWrap3" style="display:none">
				<p><?php echo $view_pro['description']; ?></p>
			</div>
			<?php } ?>
		</div>
		<input type="hidden" name="proId" id="proId"/>
		<input type="hidden" name="type" id="type"/>
		<a href="javascript:;" id="suggect_items">Suggest this items</a>								
		<div class="feedback_option">
			<h4>Leave Feedback (optional)</h4>
			<!---------------------OWN_IT------------------------------->
			<?php
				$get_own = mysql_num_rows(mysql_query("select userId from ".tbl_own." where userId = '".$_SESSION['LOGINDATA']['USERID']."' and proid = '".$view_product['proid']."'"));
				$get_q = "SELECT count( own_it ) AS cnt FROM ".tbl_own." WHERE proid = '".$view_product['proid']."' GROUP BY proid HAVING Count( own_it )";
				$view_q = $db->get_row($get_q,ARRAY_A);?>
			<div id="own_it" class="icon_a <?php if($get_own > 0) {?>active<?php } ?>" <?php if($get_own == 0) { ?>onclick="own_it('<?php echo $view_product['proid'];?>','<?php echo $_SESSION['LOGINDATA']['USERID'];?>','own')" <?php } ?>>
				<span>Own It</span>
				<div class="own_it_icon"></div>
				<?php if($get_own > 0) {?>
				<span class="user_view"><?php echo $view_q{'cnt'}; ?> People Own it</span>
				<?php } ?>
			</div>
			<div id="own_itbtm"></div>
			<!---------------------OWN_IT------------------------------->
			<!---------------------LOVE_IT------------------------------->
			<?php
				$get_love = mysql_num_rows(mysql_query("select * from ".tbl_love." where userId = '".$_SESSION['LOGINDATA']['USERID']."' and proid = '".$view_product['proid']."'"));
				$rec_love = mysql_fetch_array(mysql_query("select * from ".tbl_love." where userId = '".$_SESSION['LOGINDATA']['USERID']."' and proid = '".$view_product['proid']."'"));
				$get_l = "SELECT count( love_it ) AS cnt FROM ".tbl_love." WHERE proid = '".$view_product['proid']."' GROUP BY proid HAVING Count( love_it )";
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
				<div id="love_it" class="icon_b" onclick="love_it('<?php echo $view_product['proid'];?>','<?php echo $_SESSION['LOGINDATA']['USERID'];?>','love')">
				<span>Love It</span> 
				<div class="love_it_icon"></div>
			</div>
					<?php } ?>
				<div id="love_itbtm"></div>
				<!---------------------LOVE_IT------------------------------->
				<!---------------------HIDE_IT------------------------------->
				<?php
					$get_hide = mysql_num_rows(mysql_query("select userId from ".tbl_hide." where userId = '".$_SESSION['LOGINDATA']['USERID']."' and proid = '".$view_product['proid']."'"));
					$get_h = "SELECT count( hide_it ) AS cnt FROM ".tbl_hide." WHERE proid = '".$view_product['proid']."' GROUP BY proid HAVING Count( hide_it )";
					$view_h = $db->get_row($get_h,ARRAY_A);?>
				<div id="hide_it" class="icon_c <?php if($get_hide > 0) {?>active<?php } ?>" <?php if($get_hide == 0) { ?>onclick="hide_it('<?php echo $view_product['proid'];?>','<?php echo $_SESSION['LOGINDATA']['USERID'];?>','hide')" <?php } ?>>
					<span>Hide It</span>
					<div class="hide_it_icon"></div>
					<?php if($get_hide > 0) {?>
					<span class="user_view"><?php echo $view_h{'cnt'}; ?> People Hide it</span>
					<?php } ?>
				</div>
				<div id="hide_itbtm"></div>
				<!---------------------HIDE_IT------------------------------->
			</div>
<?php }?>	
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
    url: "<?php echo ru;?>process/get_product.php",
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


function own_it(proid,uid,type)
{
	var proId = proid;
	var userId = uid;
	var type = type;
	$.ajax({
	url: '<?php echo ru;?>process/process_product.php?proid='+proId+'&userId='+userId+'&type='+type,
	type: 'get', 
	success: function(output) {
	$('#own_it').hide();
	$('#own_itbtm').html(output);
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
	$('#love_it').hide();
	$('#love_itbtm').html(output);
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
	$('#hide_it').hide();
	//$('#hide_itbtm').html(output);
	window.location = "<?php echo ru;?>step_2a";
	}
	});
}


$('.open-intro3').click(function() {
		$('#intro-wrap3').animate({
		//opacity: 1,
		
	  }, function(){
		// Animation complete.
	  });
		$('.open-intro3').hide();
		$('.close-intro3').show();
		$('#contentWrap3').slideUp('fast');
	});
	$('.close-intro3').click(function() {
		$('#intro-wrap3').animate({
		//opacity: 0.25,
		
	  }, function() {
		// Animation complete.
	  });
		$('.open-intro3').show();
		$('.close-intro3').hide();
		$('#contentWrap3').slideDown('slow');
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

/***********************SUGGECT ITEM******************************/
$(function () {
	$('#suggect_items').on('click',function(e) {
		$('#proId').val('<?php echo $view_product['proid'];?>');
		$('#type').val('add');
		var maxcount = '<?php echo count($_SESSION['cart']);?>'; 
		var myData = 'proid=<?php echo $view_product['proid'];?>&type=add';
		$.ajax({
			url: "<?php echo ru;?>process/process_cart.php",
			type: "GET",
			data: myData,
			success:function(output) { 
			  
					$('#cart_suggest').html(output);
					$('#load_product').show();
					$("#load_product").load("<?php echo $ru;?>process/content_product.php");
					$('#product_algo').hide();
					$('#no_cart').hide();
					$('#no_cart_btn').hide();
					$('#no_cart_btn2').hide();
					e.preventDefault();
				
			},
			 beforeSend: function(){
					$('.overlay').show();
					$('.loaderr').show();  
					  
					},
    		complete: function(){
				$('.loaderr').hide();
				$('.overlay').hide();  			
			    }
		});
	});
});	
</script>
<script src="http://connect.facebook.net/en_US/all.js">
   </script>
   <script>
     FB.init({ 
       appId:'338285303000069', cookie:true, 
       status:true, xfbml:true 
     });

function FacebookInviteFriends()
{
FB.ui({ method: 'apprequests', 
   message: 'My diaolog...'},
   function(response) {
            self.close();
			window.location = "<?php echo ru;?>step_2a";
        }
   );
   
}
</script>