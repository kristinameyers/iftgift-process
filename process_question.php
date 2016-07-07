<?php 
include_once('../connect/connect.php');
include_once('../config/config.php');
include_once("question_functions.php");
if(isset($_POST['question'])) {
	$qId = $_POST['qId'];
	$qAns = $_POST['qAns'];
	$userId = $_POST['userId'];
	
	$insQues = mysql_query("insert into ".tbl_answer." set userId = '".$userId."', qId = '".$qId."', answer = '".$qAns."'");
	$questionId = mysql_insert_id();
	if($insQues) {
		$getAnswer = mysql_query("select * from ".tbl_answer." where userId = '".$userId."'");
		while($res = mysql_fetch_array($getAnswer)) {
			$qid .= $res['qId'].',';
		}
		echo $_SESSION['QuestionID'] = rtrim($qid,",");
	}
	
	/*$total_question="SELECT count( qId ) AS totalques FROM ".tbl_question_answer." WHERE `feedback_question` =0";
	$rs_ques = $db->get_row($total_question,ARRAY_A);
	$ques_count=$rs_ques['totalques'];
	if($ques_count == $count){
				$count=0;
				$upd_user="UPDATE ".tbl_user."  SET q_count = '".$count."' WHERE userId = '".$userId."'";
				$db->query($upd_user);
				$upd_user="DELETE FROM ".tbl_answer." WHERE userId = '".$userId."'";
				$db->query($upd_user);
			} else{
				$getcount = "SELECT count(qId) as total FROM ".tbl_answer." WHERE userId='".$_SESSION['LOGINDATA']['USERID']."'";
				$rs_count = $db->get_row($getcount,ARRAY_A);
				$count=$rs_count['total'];
	
				$upd_user="UPDATE ".tbl_user."  SET q_count = '".$count."' WHERE userId = '".$userId."'";
				$db->query($upd_user);
			}*/
	remove_product($qId);
}


if(isset($_POST['bkmquestion'])) {
	$qId = $_POST['qId'];
	
	$insQues = mysql_query("update ".tbl_question_answer." set bookmark_question = '1' where qId = '".$qId."'");
	if($insQues) {
		echo '1';
	}
}

if(isset($_POST['bQId'])) {
	$qId = $_POST['bQId'];
	$bquestion = $_POST['bkmquestion'];
	
	$insQues = mysql_query("update ".tbl_question_answer." set bookmark_question = '".$bquestion."' where qId = '".$qId."'");
	if($insQues) {
		echo '1';
	}
}

include_once("question_functions.php");
if($_REQUEST['type']=='add' && $_REQUEST['qid']>0){
	$qid=$_REQUEST['qid'];
	addtocart($qid,1);
	$max=count($_SESSION['question_ans']);
?>
<script type="text/javascript" src="<?php echo ru_resource;?>js/lib/jquery.tinycarousel.js"></script>
	<script type="text/javascript">
		$(document).ready(function()
		{
			$('#slider1').tinycarousel();
		});
	</script>
<div id="slider1">
<?php if($max >= '7') {?>
<a class="buttons prev" href="#"><img src="<?php echo ru_resource;?>images/arrow_l.png" alt="Arrow" /></a>
<?php } ?>
<div class="viewport">
	<ul class="overview">
<?php	
	for($i=0;$i<$max;$i++){
	$qid=$_SESSION['question_ans'][$i]['qid'];
	$q=$_SESSION['question_ans'][$i]['qty'];
?>
<li>
<div class="sugget_item">
	<div class="sugget_item_inner">
		<a href="javascript"><img alt="close" src="<?php echo ru_resource;?>images/zoom_icon.png"></a>
		<h4>Q&A#</h4> <h4>A- <?php echo $qid;?></h4>
	</div>
	<a href="javascript:del(<?php echo $qid?>)"><img class="closed" alt="close" src="<?php echo ru_resource;?>images/close.png"></a>
</div>		
</li>				
<?php } ?>
<?php if($max == '1') {?>
<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer D" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer E" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
<?php } else if($max == '2') {?>
<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer D" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
<?php } else if($max == '3') {?>
<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
<?php } else if($max == '4') {?>
<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
<?php } else if($max == '5') {?>
<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
<?php } else if($max == '6') {?>

<?php } ?>							
</ul>
							</div>
							<?php if($max >= '7') {?>
							<a class="buttons next" href="#"><img src="<?php echo ru_resource;?>images/arrow_k.png" alt="Arrow" /></a>
							<?php } ?>
						</div>
<?php
}
if($_REQUEST['type']=='delete' && $_REQUEST['qid']>0){
	remove_product($_REQUEST['qid']);
	$maxx=count($_SESSION['question_ans']);
?>
<script type="text/javascript" src="<?php echo ru_resource;?>js/lib/jquery.tinycarousel.js"></script>
	<script type="text/javascript">
		$(document).ready(function()
		{
			$('#slider1').tinycarousel();
		});
	</script>
<div id="slider1">
<?php if($maxx >= '7') {?>
<a class="buttons prev" href="#"><img src="<?php echo ru_resource;?>images/arrow_l.png" alt="Arrow" /></a>
<?php } ?>
<div class="viewport">
	<ul class="overview">
<?php	
	for($i=0;$i<$maxx;$i++){
	$qid=$_SESSION['question_ans'][$i]['qid'];
	$q=$_SESSION['question_ans'][$i]['qty'];
?>
<li>	
<div class="sugget_item">
	<div class="sugget_item_inner">
		<a href="javascript"><img alt="close" src="<?php echo ru_resource;?>images/zoom_icon.png"></a>
		<h4>Q&A#</h4> <h4>A- <?php echo $qid;?></h4>
	</div>
	<a href="javascript:del(<?php echo $qid?>)"><img class="closed" alt="close" src="<?php echo ru_resource;?>images/close.png"></a>
</div>	
</li>				
<?php } ?>
<?php if($maxx == '1') {?>
<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer D" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer E" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
<?php } else if($maxx == '2') {?>
<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer D" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
<?php } else if($maxx == '3') {?>
<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
<?php } else if($maxx == '4') {?>
<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
	<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
<?php } else if($maxx == '5') {?>
<div class="sugget_item">
		<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
	</div>
<?php } else if($maxx == '0') { ?>
		<div class="sugget_item">
			<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
		</div>
		<div class="sugget_item">
			<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
		</div>
		<div class="sugget_item">
			<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
		</div>
		<div class="sugget_item">
			<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
		</div>
		<div class="sugget_item">
			<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
		</div>
		<div class="sugget_item">
			<img alt="Question Answer C" src="<?php echo ru_resource;?>images/qa_c.jpg">
		</div>
<?php } ?>
</ul>
							</div>
							<?php if($maxx >= '7') {?>
							<a class="buttons next" href="#"><img src="<?php echo ru_resource;?>images/arrow_k.png" alt="Arrow" /></a>
							<?php } ?>
						</div>	
<?php }
?>