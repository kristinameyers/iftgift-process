<?php 
include_once('../connect/connect.php');
include_once('../config/config.php');

if(isset($_POST['question'])) {
	$qId = $_POST['qId'];
	$qAns = $_POST['qAns'];
	$proid = $_POST['proid'];
	$userId = $_POST['userId'];
	$insQues = mysql_query("insert into ".tbl_answer." set userId = '".$userId."', qId = '".$qId."', answer = '".$qAns."', proId = '".$proid."'");
	$questionId = mysql_insert_id();
	
	if($insQues) {
		$getAnswer = mysql_query("select * from ".tbl_answer." where userId = '".$userId."'");
		while($res = mysql_fetch_array($getAnswer)) {
			$qid .= $res['qId'].',';
		}
		echo $_SESSION['QuestionID'] = rtrim($qid,",");
	}
	$total_question="SELECT count( qId ) AS totalques FROM ".tbl_question_answer." WHERE `feedback_question` =0";
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
	
					/*$getAnswer2 = mysql_query("select * from ".tbl_user." where userId = '".$userId."'");
						while($res = mysql_fetch_array($getAnswer2)) {
					$qid1 = $res['qId'];
					}*/
					//$datalist=$_SESSION['LOGINDATA']['QID']; CONCAT( '$qid1,' , '$qId')
				$upd_user="UPDATE ".tbl_user."  SET q_count = '".$count."' WHERE userId = '".$userId."'";
				$db->query($upd_user);
			/* same question not twice*/
			}
}

if(isset($_POST['bkmquestion'])) {
	$qId = $_POST['qId'];
	
	$insQues = mysql_query("update ".tbl_question_answer." set bookmark_question = '1' where qId = '".$qId."'");
	$questionId = mysql_insert_id();
	if($insQues) {
		echo '1';
	}
}

?>