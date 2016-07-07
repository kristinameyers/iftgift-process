<?php 
include_once('../connect/connect.php');
include_once('../config/config.php');

if(isset($_POST['keyword'])) {
if($_POST['keyword'] != '') {
$kw = urlencode($_POST['keyword']);
$search = $kw;
}
header("location:".ru."question_library/".$search);
exit;
}
?>					