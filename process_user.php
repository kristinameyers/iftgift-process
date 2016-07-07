<?php 
require_once("../connect/connect.php");
require_once("../config/config.php");
require_once("../common/imagecrop_functions.php");

unset($_SESSION['biz_userimg_err']);
unset($_SESSION['biz_userimg']);
//echo "<pre>";
//print_r($_POST); exit;

if (isset($_POST["upload"])) { 

	$userId = $_SESSION['LOGINDATA']['USERID'];
	$oldimage = $_POST['oldimage'];
	$userfile_name = $_FILES['image']['name'];
	$userfile_tmp = $_FILES['image']['tmp_name'];
	$userfile_size = $_FILES['image']['size'];
	$userfile_type = $_FILES['image']['type'];
	$filename = basename($_FILES['image']['name']);
	
	
	$upload_dir = "../media/user_image/".$userId.'/'; 				
	$upload_path = $upload_dir."/";				
	$large_image_prefix = $userfile_name; 			
	$thumb_image_prefix = "thumbnail";			
	$large_image_name = $large_image_prefix;     
	$thumb_image_name = $thumb_image_prefix;    
	$max_file = "3"; 							
	$max_width = "320";							
	$thumb_width = "100";						
	$thumb_height = "100";						

	$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
	$allowed_image_ext = array_unique($allowed_image_types);
	$image_ext = "";
	foreach ($allowed_image_ext as $mime_type => $ext) {
    	$image_ext.= strtoupper($ext)." ";
	}


	$large_image_location = $upload_path.$large_image_name;
	$thumb_image_location = $upload_path.$thumb_image_name;


	if(!is_dir($upload_dir)){
		mkdir($upload_dir, 0777);
		chmod($upload_dir, 0777);
	}

	$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
	//Only process if the file is a JPG, PNG or GIF and below the allowed limit
	if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
		
		foreach ($allowed_image_types as $mime_type => $ext) {
			//loop through the specified image types and if they match the extension then break out
			//everything is ok so go and check file size
			if($file_ext==$ext && $userfile_type==$mime_type){
				$error = "";
				break;
			}else{
				//$_SESSION['biz_userimg_err']['ext'] = "Only <strong>".$image_ext."</strong> images accepted for upload<br />";
				//header("location:".ru."photo_upload");exit;
			}
		}
		//check if the file size is above the allowed limit
		if ($userfile_size > ($max_file*1048576)) {
			$_SESSION['biz_userimg_err']['size']= "Images must be under ".$max_file."MB in size";
			header("location:".ru."photo_upload");exit;
		}
		
	}else{
		$_SESSION['biz_userimg_err']['image']= "Select an image for upload";
		header("location:".ru."photo_upload");exit;
	}
	//Everything is ok, so we can upload the image.
	if (strlen($error)==0){
		
		if (isset($_FILES['image']['name'])){
			//this file could now has an unknown file extension (we hope it's one of the ones set above!)
			$large_image_location = $large_image_location;
			$thumb_image_location = $thumb_image_location;
			
			//put the file ext in the session so we know what file to look for once its uploaded
			$_SESSION['user_file_ext']=".".$file_ext;
			
			move_uploaded_file($userfile_tmp, $large_image_location);
			chmod($large_image_location, 0777);
			
			$width = getWidth($large_image_location);
			$height = getHeight($large_image_location);
			//Scale the image if it is greater than the width set above
			if ($width > $max_width){
				$scale = $max_width/$width;
				$uploaded = resizeImage($large_image_location,$width,$height,$scale);
			}else{
				$scale = 1;
				$uploaded = resizeImage($large_image_location,$width,$height,$scale);
			}
			//Delete the thumbnail file so the user can create a new one
			if (file_exists($thumb_image_location)) {
				unlink($thumb_image_location);
			}
			
			@unlink ($upload_dir.$oldimage);
			
			mysql_query("update ".tbl_user." set user_image='$userfile_name' where userId = '$userId'");
			$check_points = "select * from ".tbl_userpoints." where userId = '".$userId."'";
			$view_points = $db->get_row($check_points,ARRAY_A);
			$points = $view_points['points'];
			$new_points = $points + 25;
			if($view_points) {
				$update_points = mysql_query("update ".tbl_userpoints." set points = '".$new_points."' where userId = '".$userId."'");
			} else {
				$insrt_points = mysql_query("insert into ".tbl_userpoints." set points = '25',userId = '".$userId."'");
			}	
			
		}
		//Refresh the page to show the new uploaded image
		header("location:".ru."photo_upload");
		exit();
	}
}


if (isset($_POST["upload_thumbnail"])) {
	
	$userfile_name = $_POST['image_name'];
	$userId = $_SESSION['LOGINDATA']['USERID'];
	$upload_dir = "../media/user_image/".$userId.'/'; 				
	$upload_path = $upload_dir."/";
	$large_image_prefix = $userfile_name;	
	$upload_dirs = "../media/user_image/".$userId.'/thumb/'; 				
	$upload_paths = $upload_dirs."/";		 			
	$thumb_image_prefix = $userfile_name;			
	$large_image_name = $large_image_prefix;     
	$thumb_image_name = $thumb_image_prefix;    
	$max_file = "3"; 							
	$max_width = "320";							
	$thumb_width = "100";						
	$thumb_height = "100";	
	
	if(!is_dir($upload_dirs)){
		mkdir($upload_dirs, 0777);
		chmod($upload_dirs, 0777);
	}
	
	$large_image_location = $upload_path.$large_image_name;
	$thumb_image_location = $upload_paths.$thumb_image_name;

	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	$w = $_POST["w"];
	$h = $_POST["h"];
	
	$scale = $thumb_width/$w;
	$cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
	header("location:".ru."photo_upload");
	exit;
}

?>