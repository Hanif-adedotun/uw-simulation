<?php 
// File for applications methods 
include_once '../login.php';
include_once '../database.php';

// Initialize database class
$database = new DB;

// Set header type to json format
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
     $output = $database->GetUsers($tb_applications, $tb_app_fields);
     echo $output;
}else if($_SERVER['REQUEST_METHOD'] == 'POST'){
     $storage = 'uploads/';
     $filename = basename($_FILES['logo']['name']);
     $targetFilePath = $storage . $filename;
     $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
     $errmsg = '';
     if(!empty($_FILES['logo']['name'])){

          $allowType = array('jpg','png','jpeg','gif','pdf');

          if(in_array($fileType, $allowType)){

               if(move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)){
                    
                    echo json_encode(['image' => $filename]);

               }else{
                    $errmsg = "File upload failed, please try again.";
               }
          }else{
               $errmsg = "Only common image file types are supported, try again!";
          }
     }else{
          $errmsg = "Please select a file to upload.";
     }

     if(!empty($errmsg)){//if error message display 
          echo json_encode(['error' => $errmsg]);
     }
}
?>