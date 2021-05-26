<?php 
// File for applications methods 
include_once '../login.php';
include_once '../database.php';

// Initialize database class
$database = new DB;

// Set header type to json format
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
     if(isset($_GET['version'])){
         echo json_encode(['PHP version' => phpversion()]);
     }else{
          $output = $database->GetUsers($tb_applications, $tb_app_fields);
          echo $output;
     }
     
}else if($_SERVER['REQUEST_METHOD'] == 'POST'){
     // File upload to database
     // $storage = 'uploads/';
     // $filename = basename($_FILES['logo']['name']);
     // $targetFilePath = $storage . $filename;
     // $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
     // $errmsg = '';
     // if(!empty($_FILES['logo']['name'])){

     //      $allowType = array('jpg','png','jpeg','gif','pdf');

     //      if(in_array($fileType, $allowType)){

     //           if(move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)){
     //               $logo = $filename;
     //               echo $logo;
     //           }else{
     //                $errmsg = "File upload failed, please try again.";
     //           }
     //      }else{
     //           $errmsg = "Only common image file types are supported, try again!";
     //      }
     // }else{
     //      $errmsg = "Please select a file to upload";
     // }

     // Get the other data
     $json = file_get_contents('php://input');
     $js = json_decode($json, true);
     

     if(empty($js)){//Empty data from db
          http_response_code(400);
          echo json_encode(['error' => 'No data sent to Server']);
      }else{
          $email = $js['email'];
          $logo = $js['logo'];
          $size = $js['size'];
          $version = $js['version'];
          $compatibility = $js['compatibility'];
          $downloads = $js['downloads'];
          $status = $js['status'];
          $age = $js['age group'];
          $dor = $js['date of release'];
          // $devid = $js['dev id'];         
           
          $res = $database->addApp($tb_users, $email);
          echo json_encode(['msg'=> $res]);
      }   

     if(!empty($errmsg)){//if error message display 
          http_response_code(500);
          echo json_encode(['error' => $errmsg]);
     }
}
?>