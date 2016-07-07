<?php 
include_once('../connect/connect.php');
include_once('../config/config.php');

if(isset($_POST['location'])) {
if($_POST['search'] != '') {
$kw = urlencode($_POST['search']);
$_SESSION['search']['optional'] = $_POST['location'];
$search = $kw;
}
header("location:".ru."search_result/".$search);
exit;
}

if(isset($_POST['price_search'])) {
if($_POST['from_price'] != '' && $_POST['to_price'] == '') {
$price_from =str_replace("$","",$_POST['from_price']); 
if($_POST['to_price'] == '')
{
$price_to = 0; 
} else {
$price_to =str_replace("$","",$_POST['to_price']);
}
$search = $price_from.'/'.$price_to;
} else if($_POST['to_price'] != '' && $_POST['from_price'] == '') {
$price_to =str_replace("$","",$_POST['to_price']);
if($_POST['from_price'] == '')
{
$price_from = 0; 
} else {
$price_from =str_replace("$","",$_POST['from_price']); 
}
$search = $price_from.'/'.$price_to;
} else if($_POST['to_price'] != '' && $_POST['from_price'] != '') {
$price_to =str_replace("$","",$_POST['to_price']); 
$price_from =str_replace("$","",$_POST['from_price']); 
$_SESSION['from_price']['optional1'] = $_POST['price_search'];
$search = $price_from.'/'.$price_to;
} 
header("location:".ru."search_result/".$search);
exit;
}
  ?>
					