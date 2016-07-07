<?php 
include_once("../connect/connect.php");
include_once("../config/config.php");

if (isset($_GET['wishlist'])) {
	if (isset($_SESSION['sort_by'])) {
		if (isset($_SESSION['sort_by']['by']) && $_SESSION['sort_by']['by'] == $_GET['wishlist']) {
			switch($_SESSION['sort_by']['ad']) {
				case 'DESC':
				$_SESSION['sort_by']['ad'] = 'ASC';
				break;
			}
		} 
	} else {
		$_SESSION['sort_by'] = array(
			'by' => $_GET['wishlist'],
			'ad' => 'ASC'
		);
	}
	
	echo json_encode(array('error' => false));
	
} 


if (isset($_GET['wishlistdesc'])) {
	if (isset($_SESSION['sort_by'])) {
		if (isset($_SESSION['sort_by']['by']) && $_SESSION['sort_by']['by'] == $_GET['wishlistdesc']) {
			switch($_SESSION['sort_by']['ad']) {
				case 'ASC':
				$_SESSION['sort_by']['ad'] = 'DESC';
				break;
			}
		} 
	} else {
		$_SESSION['sort_by'] = array(
			'by' => $_GET['wishlistdesc'],
			'ad' => 'DESC'
		);
	}
	
	echo json_encode(array('error' => false));
	
}

?>