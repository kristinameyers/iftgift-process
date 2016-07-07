<?php
include('../connect/connect.php');
include('../config/config.php');

if(isset($_SESSION['recipit_id']['New'])) {
$get_recipit = "select r.recp_first_name,r.recp_last_name,r.delivery_id,r.recp_email,r.cash_amount,r.occassionid,o.occasionid,o.occasion_name from ".tbl_delivery." as r left join ".tbl_occasion." as o on r.occassionid=o.occasionid where r.delivery_id = '".$_SESSION['recipit_id']['New']."'";
$view = $db->get_row($get_recipit,ARRAY_A);
	$userfname = $view['recp_first_name'];
} else {
	$userfname = $_SESSION['LOGINDATA']['NAME'];
}
//$get_question = $db->get_row("select * from ".tbl_question_answer." where  feedback_question = 0 ORDER BY RAND() LIMIT 1",ARRAY_A);
$get_question = $db->get_row("SELECT * FROM gift_question_answer WHERE gift_question_answer.feedback_question = 0 and gift_question_answer.qId NOT IN (SELECT qId FROM gift_answer where gift_answer.userId = '".$_SESSION['LOGINDATA']['USERID']."') ORDER BY RAND() LIMIT 1",ARRAY_A);
$question = $get_question['question_type'];

$qId=$get_question['qId'];
	if($question == 'multiple') {  ?>
		<div class="modal modal_b">
		<a style="cursor:pointer" onClick="close_divs();">
			<img src="<?php echo ru_resource; ?>images/close_icon.png" alt="Closed Icon" />
		</a>
		<h3 class="use_nam"><?php echo str_replace('[MEMBER.FirstName]',ucfirst($userfname),$get_question['question']); ?></h3>
		<div class="ques_range_outer">
			<h5>Make your single choice selection by clicking on a color bar.</h5>
			<div class="ques_rangebar">
				<div class="range"></div>
				<table class="range_ans">
					<tr>
						<td id="answer1_<?php echo $get_question['answer1']; ?>" value="<?php echo $get_question['answer1']; ?>"><a href="javascript:;" class="color_a" id="answer1"></a></td>
						<td id="answer2_<?php echo $get_question['answer2']; ?>" value="<?php echo $get_question['answer2']; ?>"><a href="javascript:;" class="color_b" id="answer2"></a></td>
						<td id="answer3_<?php echo $get_question['answer3']; ?>" value="<?php echo $get_question['answer3']; ?>"><a href="javascript:;" class="color_c" id="answer3"></a></td>
						<td id="answer4_<?php echo $get_question['answer4']; ?>" value="<?php echo $get_question['answer4']; ?>"><a href="javascript:;" class="color_d" id="answer4"></a></td>
						<td id="answer5_<?php echo $get_question['answer5']; ?>" value="<?php echo $get_question['answer5']; ?>"><a href="javascript:;" class="color_e" id="answer5"></a></td>
						<td id="answer6_<?php echo $get_question['answer6']; ?>" value="<?php echo $get_question['answer6']; ?>"><a href="javascript:;" class="color_f" id="answer6"></a></td>
						<td id="answer7_<?php echo $get_question['answer7']; ?>" value="<?php echo $get_question['answer7']; ?>"><a href="javascript:;" class="color_g" id="answer7"></a></td>
						<td id="answer8_<?php echo $get_question['answer8']; ?>" value="<?php echo $get_question['answer8']; ?>"><a href="javascript:;" class="color_h" id="answer8"></a></td>
					</tr>
					<tr class="value">
						<td>
							<span><?php echo $get_question['answer1']; ?></span>
						</td>
						<td>
							<span><?php echo $get_question['answer2']; ?></span>
						</td>
						<td>
							<span><?php echo $get_question['answer3']; ?></span>
						</td>
						<td>
							<span><?php echo $get_question['answer4']; ?></span>
						</td>
						<td>
							<span><?php echo $get_question['answer5']; ?></span>
						</td>
						<td>
							<span><?php echo $get_question['answer6']; ?></span>
						</td>
						<td>
							<span><?php echo $get_question['answer7']; ?></span>
						</td>
						<td>
							<span><?php echo str_replace('[MEMBER.FirstName]',ucfirst($userfname),$get_question['answer8']); ?></span>
						</td>
					</tr>
				</table>
				<?php /*?><ul>
					<li id="answer1" value="<?php echo $get_question['answer1']; ?>"><a href="javascript:;" class="color_a" id="answer1" ></a><span><?php echo $get_question['answer1']; ?></span></li>
					<li id="answer2" value="<?php echo $get_question['answer2']; ?>"><a href="javascript:;" class="color_b" id="answer2"></a><span><?php echo $get_question['answer2']; ?></span></li>
					<li id="answer3" value="<?php echo $get_question['answer3']; ?>"><a href="javascript:;" class="color_c" id="answer3"></a><span><?php echo $get_question['answer3']; ?></span></li>
					<li id="answer4" value="<?php echo $get_question['answer4']; ?>"><a href="javascript:;" class="color_d" id="answer4"></a><span><?php echo $get_question['answer4']; ?></span></li>
					<li id="answer5" value="<?php echo $get_question['answer5']; ?>"><a href="javascript:;" class="color_e" id="answer5"></a><span><?php echo $get_question['answer5']; ?></span></li>
					<li id="answer6" value="<?php echo $get_question['answer6']; ?>"><a href="javascript:;" class="color_f" id="answer6"></a><span><?php echo $get_question['answer6']; ?></span></li>
					<li id="answer7" value="<?php echo $get_question['answer7']; ?>"><a href="javascript:;" class="color_g" id="answer7"></a><span><?php echo $get_question['answer7']; ?></span></li>
					<li id="answer8" value="<?php echo $get_question['answer8']; ?>"><a href="javascript:;" class="color_h" id="answer8"></a><span><?php echo str_replace('[MEMBER.FirstName]',ucfirst($_SESSION['LOGINDATA']['NAME']),$get_question['answer8']); ?></span></li>
				</ul><?php */?>
			</div>
		</div>
		<div class="range_btm">
			<div class="range_option bkm bkms" onclick="bookmark_question('<?php echo $get_question['qId']; ?>')" style="cursor:pointer">
				<!--<img src="<?php echo ru_resource; ?>images/icon_n.png" alt="Answer Questions Icon" />-->
				<span>Bookmark in Q&A Library</span>
			</div>
			<div class="range_option exit_qa" onclick="exit_question()" style="cursor:pointer">
				<!--<img src="<?php echo ru_resource; ?>images/icon_o.png" alt="Answer Questions Icon" />-->
				<span>Exit Q&A </span>
			</div>
			<div class="range_option bkm skp" onclick="skip_question()" style="cursor:pointer">
				<!--<img src="<?php echo ru_resource; ?>images/icon_p.png" alt="Answer Questions Icon" class="skip" />-->
				<span>Skip Question</span>
			</div>
			<input type="hidden" name="range" id="range" value=""  />
			<a href="javascript:;" id="confirm_question">Submit answer</a>
		</div>
	</div>
<script>
$(document).ready(function(){
	$(".ques_rangebar table.range_ans td").click(function(){
		var id = this.id;
		var ans = id.split('_');		
		var answer = ans[1];
		$('#range').attr("value",answer);
		$("a").removeClass("active");
		$("td a#"+ans[0]).addClass("active");
  });
});
</script>				
<?php	
	} else if($question == 'range') { 
		$answer1 =	$get_question['answer1'];
		$answer2 =	$get_question['answer2'];
	?>
<link rel="stylesheet" type="text/css" href="<?php echo ru_resource;?>css/jquery-ui.css">
<script>
$(document).ready(function() {
	$.extend( $.ui.slider.prototype.options, {
		animate: false ,
		stop: function(e,ui) {
			//ga("send", "event", "slider", "interact", this.id );
		}
	});
	var $slider1 = $("#mainDemo").slider({ value: 50,
		min: 10,
		max: 90,
		step: 10,
		change: function(event, ui) { 
        $("#range").attr("value",ui.value);
    } 
		
		 });
		$slider1.slider("pips");
	 var min = 10;
	 var max = 90;
	if(min == 10) {
		$(".ui-slider-pip-first .ui-slider-label").html("<?php echo $answer1; ?>");
	}  
	if(max == 90) {
		$(".ui-slider-pip-last .ui-slider-label").html("<?php echo $answer2; ?>");
	}
	
	$("#submit_answer").click(function(){
		$("#reality_check").slideDown();
		$("#submit_answer").hide();
  });
});
</script>
	<div class="modal modal_b">
		<a style="cursor:pointer" onClick="close_divs();">
			<img src="<?php echo ru_resource; ?>images/close_icon.png" alt="Closed Icon" />
		</a>
		<h3 class="use_nam"><?php echo str_replace('[MEMBER.FirstName]',ucfirst($userfname),$get_question['question']); ?></h3>
		<div class="ques_range_outer">
			<h5>Click the marker to move to appropriate point along scale.</h5>
		<div class="ques_rangebar single_ques">
			<div id="mainDemo" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all ui-slider-pips" aria-disabled="false">
					<table>
						<a href="#" class="ui-slider-handle ui-state-default ui-corner-all" style="left: 50%;"></a>
						<div class="range_scroll"></div>
						<input type="hidden" name="range" id="range" value="50"  />
						<td style="left:0%" class="ui-slider-pip ui-slider-pip-first ui-slider-pip-label ui-slider-pip-0" >
							<span class="ui-slider-line"></span>
							<span data-value="0" class="ui-slider-label">0</span>
						</td>
						<td style="left:12.5000%" class="ui-slider-pip ui-slider-pip-1">
							<span class="ui-slider-line"></span><span data-value="1" class="ui-slider-label">1</span>
						</td>
						<td style="left:25.0000%" class="ui-slider-pip ui-slider-pip-2">
							<span class="ui-slider-line"></span><span data-value="2" class="ui-slider-label">2</span>
						</td>
						<td style="left:37.5000%" class="ui-slider-pip ui-slider-pip-3">
							<span class="ui-slider-line"></span><span data-value="3" class="ui-slider-label">3</span>
						</td>
						<td style="left:50.0000%" class="ui-slider-pip ui-slider-pip-4">
							<span class="ui-slider-line"></span><span data-value="4" class="ui-slider-label">4</span>
						</td>
						<td style="left:62.5000%" class="ui-slider-pip ui-slider-pip-5">
							<span class="ui-slider-line"></span><span data-value="5" class="ui-slider-label">5</span>
						</td>
						<td style="left:75.0000%" class="ui-slider-pip ui-slider-pip-6">
							<span class="ui-slider-line"></span><span data-value="6" class="ui-slider-label">6</span>
						</td>
						<td style="left:87.5000%" class="ui-slider-pip ui-slider-pip-7">
							<span class="ui-slider-line"></span>
							<span data-value="7" class="ui-slider-label">7</span>
						</td>
						<td style="left:100.0000%" class="ui-slider-pip ui-slider-pip-8">
							<span class="ui-slider-line"></span><span data-value="8" class="ui-slider-label">8</span>
						</td>
					</table>
				</div>
			</div>
		</div>
		<div class="range_btm">
			<div class="range_option bkm bkms bqa" onclick="bookmark_question('<?php echo $get_question['qId']; ?>')" style="border:0; cursor:pointer">
				<!--<img src="<?php echo ru_resource; ?>images/icon_n.png" alt="Answer Questions Icon" />-->
				<span>Bookmark in Q&A Library</span>
			</div>
			<div class="range_option exit_qa" onclick="exit_question()" style="cursor:pointer">
				<!--<img src="<?php echo ru_resource; ?>images/icon_o.png" alt="Answer Questions Icon" />-->
				<span>Exit Q&A </span>
			</div>
			<div class="range_option bkm skp" onclick="skip_question()" style="cursor:pointer">
				<!--<img src="<?php echo ru_resource; ?>images/icon_p.png" alt="Answer Questions Icon" class="skip" />-->
				<span>Skip Question</span>
			</div>
			<a href="javascript:;" id="confirm_question">Submit answer</a>
		</div>
	</div>
<?php } ?>
<script>
$(function () {
	$('#confirm_question').on('click',function () {
		var qId = '<?php echo $get_question['qId']; ?>';
		var qAns = $('#range').val();
		var proid = $('#proid').val();
		var userId = '<?php echo $_SESSION['LOGINDATA']['USERID'];?>';
		var myData = 'qId='+qId+'&qAns='+qAns+'&proid='+proid+'&userId='+userId+'&question=1';
		$.ajax({
			url:'<?php echo ru;?>process/process_cartquestion.php',
			type:'POST',
			data:myData,
			success:function (response) {
				if(response) {
					setTimeout(function() { 
    					$('#modal_checkouts').hide();
						$('.overlay').hide('slow');
						//window.location = "<?php //echo $_SERVER['HTTP_REFERER']; ?>";
 					}, 1000);
				}
			}
		});
	})
});

function exit_question() {
	$('#modal_checkouts').hide();
	$('.overlay').hide('slow');
}

function skip_question() {
	$.ajax({
			url: "<?php echo ru;?>process/get_cartquestion.php",
			type: "POST",
			success:function(output) {
				$('.overlay').show();
				$('#modal_checkouts').html(output);
			}
		});
}

function bookmark_question(qId) {
	var QId = qId;
	var myData = 'qId='+QId+'&bkmquestion=1';
	$.ajax({
		url:'<?php echo ru;?>process/process_question.php',
			type:'POST',
			data:myData,
			success:function (response) {
			if(response) {
				$(".bkms").addClass('active');
			}
		}
	});
}

function close_divs()
{
	jQuery(document).ready(function () {
	jQuery(".modal").slideUp("slow");
	jQuery(".overlay").css("display","none");
	});
}	
/*function convert_smart_quotes($question)
{
$search = array(chr(145),
chr(146),
chr(147),
chr(148),
chr(151));
 
$replace = array("'",
"'",
'"',
'"',
'-');
 
return str_replace($search, $replace, $question);
}*/
</script>
<style>
/*.overlays{position:fixed; top:0; left:0; height:100%; width:100%; background:url(resource/images/overlay_bg.png); z-index:9999999}*/
.single_ques .ui-slider .ui-slider-pip{top:22px}
.single_ques .ui-slider-horizontal .ui-slider-handle{top:-37px}
.modal span.ui-slider-label{ font-size:.7em}
</style>

