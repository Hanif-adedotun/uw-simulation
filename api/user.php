<?php
include_once '../login.php';
include_once '../database.php';

// Initialize new database class
$dataB = new DB;

// Set the return header from the database;
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET'){

     if (isset($_GET['email'])){//Get developer based on email
          
          $res = $dataB->GetUser($tb_users, 'email', $tb_users_field, $_GET['email']);
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
          echo $upd;
     
     }else if(isset($_GET['delete'])){//Delete a user from the database

          $email = $_GET['delete'];

          $del = $dataB->DelUser($tb_users, 'email', $email);
          echo $del;
          // echo json_encode(['msg' => 'Server Method is delete, '.$email]);

     }else if(isset($_GET['verify'])){//Verify user on the database
          $email = $_GET['email'];
          $password = $_GET['password'];

          // Code to verify user on the database
     }else{
          $res = $dataB->GetUsers($tb_users, $tb_users_field);
          echo $res;
     }

     // echo $_SERVER['QUERY_STRING'];
}else if($_SERVER['REQUEST_METHOD'] == 'POST' ){
          $json = file_get_contents('php://input');
          $js = json_decode($json, true);
      
          if(empty($js)){//Empty data from db
               echo json_encode(['msg' => 'No data sent to Server']);
           }else{
               $name = $js['name'];
               $email = $js['email'];
               $password = password_hash($js['password'], PASSWORD_DEFAULT);
               $company = $js['company'];
                //  password_verify()
               
                // echo $password;
           
                
                $msg = $dataB->AddUser($tb_users, $name, $email, $password, $company);
                echo $msg;
           }            
}

// To parse into an array
// echo $_SERVER['REQUEST_URI'] ;
// $queries = array();
// parse_str($_SERVER['QUERY_STRING'], $queries); 
?>