<?php
include_once("../connect/connect.php");
include_once("../config/config.php");

$userId = $_GET['dId'];
$friend_info = @mysql_fetch_array(mysql_query("select * from ".tbl_user." where userId = ".$userId.""));
if($friend_info['user_image']) {	
		$user_image = ru."media/user_image/".$friend_info['userId']."/thumb/".$friend_info['user_image']; 
	} else {
		$user_image = ru_resource."images/avtar.jpg";
	}
$friend_points = @mysql_fetch_array(mysql_query("select * from ".tbl_userpoints." where userId = ".$userId.""));	

$friend_dev_detail = @mysql_fetch_array(mysql_query("select * from ".tbl_delivery." where recp_email = '".$friend_info['email']."' order by delivery_id desc"));	
$timestamps = strtotime($friend_dev_detail['dated']);
$dated = date('m/d/Y', $timestamps);
?>

		<div class="iftgift_tag">
			<div class="ift_tag_blue">
				<a href="<?php echo ru;?>step_1" class="send_ift"><span>TAQ YOU'RE IT!</span> Send and iftGift</a>
				<img src="<?php echo ru_resource; ?>images/gift_icon_b.png" alt="Gift Icon"/>
				<h5>LAST iftGift FROM <span><span class="tem">THEM</span> 07/06/13</span></h5>
			</div>
			<div class="friend_point">
				<h5><?php echo ucfirst($friend_info['first_name']).'&nbsp;'.ucfirst($friend_info['last_name']); ?></h5>
				<img src="<?php echo $user_image;?>" width="109" height="110" align="<?php echo ucfirst($friend_info['first_name']).'&nbsp;'.ucfirst($friend_info['last_name']); ?>" />
				<h5 class="like"><?php echo $friend_points['points']; ?> <span>Pts</span></h5>
				<img src="<?php echo ru_resource; ?>images/alarm_icon.jpg" alt="Alaram Icon" class="alram_icon" />
				<h5 class="birthday">Birthday <?php echo $friend_info['dob']; ?></h5>
			</div>
			<div class="ift_tag_blue ift_tag_pink">
				<a class="send_ift"><span>TAQ YOU'RE IT!</span> s'Jester Q&A</a>
				<img src="<?php echo ru_resource; ?>images/qa_icon.jpg" alt="Gift Icon"/>
				<h5>LAST Q&A FROM <span><span class="tem">THEM</span> 07/06/13</span></h5>
			</div>
			<img src="<?php echo ru_resource; ?>images/jester_v.png" alt="Jester Icon" class="jester_icon" />
			<div class="terms">
				<label>Count 'Em Out</label>
				<div class="squaredFour">
					<input type="checkbox" value="None" id="squared-Four" name="check">
					<label for="squared-Four"></label>
				</div>
			</div>
		</div>