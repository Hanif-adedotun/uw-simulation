<?php
include_once '../database.php';
include_once '../login.php';
// Initialize new database class
$dataB = new DB;

// Set the return header from the database;
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

     if ($_SERVER['REQUEST_METHOD'] == 'GET'){

          if (isset($_GET['email'])){//Get developer based on email
               
               $res = $dataB->GetUser($tb_users, 'email', $tb_users_field, $_GET['email'], false);
               http_response_code(200);
               echo $res;


          }else if(isset($_GET['edit'])){//Edit a field in a developer name
               
               $email = $_GET['edit'];
               $field = $_GET['field'];
               if($field == 'password'){
                    $value = password_hash($_GET['value'], PASSWORD_DEFAULT);;
               }else{
                    $value = $_GET['value'];
               }

               $upd = $dataB->Update($tb_users, $field, $value, $email);
               http_response_code(200);
               echo $upd;
          
          }else if(isset($_GET['delete'])){//Delete a user from the database

               $email = $_GET['delete'];

               $del = $dataB->DelUser($tb_users, 'email', $email);
               http_response_code(200);
               echo $del;
               // echo json_encode(['msg' => 'Server Method is delete, '.$email]);

          }else if(isset($_GET['verify'])){//Verify user on the database
               $email = $_GET['verify'];
               $password = $_GET['pass'];
               
               if(!empty($email) && !empty($password)){

                    $gethash = $dataB->GetUser($tb_users, 'email', $tb_users_field, $email, 'password');

                    if($gethash !== NULL){//If there is no empty data sent
                         $equal = password_verify($password , $gethash);
                         http_response_code(200);
                         if($equal == true){
                              echo json_encode(['authenticated' => true]);
                         }else{
                              echo json_encode(['authenticated' => false]);
                         }

                    }else{
                         http_response_code(500);
                         echo json_encode(['error' => 'Error in databse']);
                    }
               }else{
                    http_response_code(400);
                    echo json_encode(['error' => 'No data in email or password']);
               }
               

          }else{
               $res = $dataB->GetUsers($tb_users, $tb_users_field);
               http_response_code(200);
               echo $res;
          }

          // echo $_SERVER['QUERY_STRING'];
     }else if($_SERVER['REQUEST_METHOD'] == 'POST' ){
          header('Content-Type: application/json');

               $json = file_get_contents('php://input');
               $js = json_decode($json, true);
          
               if(empty($js)){//Empty data from db
                    http_response_code(400);
                    echo json_encode(['msg' => 'No data sent to Server']);
               }else{
                    $name = $js['name'];
                    $email = $js['email'];
                    $password = password_hash($js['password'], PASSWORD_DEFAULT);
                    $company = $js['company'];
                    
                    $msg = $dataB->AddUser($tb_users, $name, $email, $password, $company);
                    http_response_code(201);
                    echo $msg;
               }            
     
}
// To parse into an array
// echo $_SERVER['REQUEST_URI'] ;
// $queries = array();
// parse_str($_SERVER['QUERY_STRING'], $queries); 
?>