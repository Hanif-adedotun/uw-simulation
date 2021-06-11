<?php 
// File for applications methods 
include_once '../login.php';
include_once '../database.php';

// Initialize database class
$database = new DB;

// Set header type to json format
header('Content-Type: application/json');

// Sanitize the header to secure server against any bad request
if (!isset($_SERVER['PHP_AUTH_USER'])) {
     header('WWW-Authenticate: Basic realm="Simulation Server"');
     header('HTTP/1.0 401 Unauthorized');
     echo json_encode(['error' => 'Not authenticated, try again with the required server header authentication']);
     exit;

} else if($_SERVER['PHP_AUTH_USER'] != $authName || $_SERVER['PHP_AUTH_PW'] != $authPW){
     header('HTTP/1.0 401 Unauthorized');
     echo json_encode(['error' => 'Not authenticated, Username or Password is incorrect']);
     exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET'){//Reuturns the php version of the 
     if(isset($_GET['version'])){
         echo json_encode(['PHP version' => phpversion()]);

     }else if(isset($_GET['logo'])){//Get logo in html form
          header('Content-Type: text/html');
          $database->getLogo($tb_applications, 'logo', $_GET['logo']);

     }else if(isset($_GET['email'])){//Return all the applications from a developer email
          $res = $database->getapp($tb_users, $_GET['email'], $tb_applications, $tb_app_fields);
          echo $res;

     }else if(isset($_GET['delete'])){//Delete an application from the table
          $res = $database->DelUser($tb_applications, 'id', $_GET['delete']);
          echo $res;
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
     //           //     echo $logo;
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
          return;
      }else{
          // $keys = array($js['email'], $js['logo'], $js['size'], $js['version'], $js['compatibility'], $js['downloads'], $js['downloads'], $js['status'], $js['age group'], $js['date of release']);
          $email = $js['email'];
          $name = $js['name'];
          $logo = $js['logo'];
          $size = $js['size'];
          $version = $js['version'];
          $compatibility = $js['compatibility'];
          $downloads = $js['downloads'];
          $status = $js['status'];
          $age = $js['age group'];
          $dor = $js['date of release'];
          // $devid = $js['dev id'];         
           

          $res = $database->addApp($tb_users, $email, $tb_applications,$name, $logo, $size, $version, $compatibility, $downloads, $status, $age, $dor);
          echo $res;
      }   

     if(!empty($errmsg)){//if error message display 
          http_response_code(500);
          echo json_encode(['error' => $errmsg]);
     }
}
//  switch (n) {
//      case label1:
//        code to be executed if n=label1;
//        break;
//      case label2:
//        code to be executed if n=label2;
//        break;
//      case label3:
//        code to be executed if n=label3;
//        break;
//        ...
//      default:
//        code to be executed if n is different from all labels;
//    } 
?>


