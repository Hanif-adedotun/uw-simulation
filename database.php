<?php

include_once 'login.php';
// Create a JSON error parser that converts error to JSON
// Create an errror logger to file also
include_once 'tests/recordError.php';
// recordError()
class DB{
     public function GetUsers($table, $fields){
          include 'login.php';
          
          try{
               $sql = "SELECT * FROM $table";
               $res = $con->query($sql);
               if($res){
                    $output = array();
                    $i = 0;
                    while($row = mysqli_fetch_assoc($res)){
                         foreach ($fields as $f){
                              $output[$i][$f] =  $row[$f];
                         }
                         $i++;
                    }
                    if(!empty($output)){
                         return json_encode($output);
                    }else{
                         return ParseError('Data requested is empty');
                    }
                    
               }else{
                    return ParseError($con->error);
               }
               
          }catch (\Throwable $e){
               return ParseError($e);
          }
          
     }
     // Gets the
     public function GetUser($table, $field, $fields, $data, $param){
          include 'login.php';

          try{
               if($param == false){
                    $sql = "SELECT * FROM $table WHERE $field = '$data'";

                    $res = $con->query($sql);
                    if(mysqli_num_rows($res) > 0){
                         $out = array();
                         $i = 0;
                         while($row = mysqli_fetch_assoc($res)){
                              foreach ($fields as $f){
                                   $out[$i][$f] =  $row[$f];
                              }
                              $i++;
                         }
                         return json_encode($out);
                    }else if(!$res){
                         return ParseError($con->error);
                    }else{
                         recordError($con->error); //Enter Error into log file
                         return json_encode(['user'=> NULL]);
                    }

               }else{
                    $sql = "SELECT $param FROM $table WHERE $field = '$data'";
                    $res = $con->query($sql);

                    if(mysqli_num_rows($res) > 0){
                         // output data of each row
                         while($row = mysqli_fetch_assoc($res)) {
                         $dat = $row[$param];
                         }
                         return $dat;
                    } else {
                         // return json_encode(['msg'=> NULL]);
                         http_response_code(500);
                         recordError($con->error); //Enter Error into log file
                         return NULL;
                    }
               }
          }catch (\Throwable $e){
               return ParseError($e);
          }
          
          
          
     }
     public function AddUser($table, $name, $email, $pass, $company){
          include 'login.php';
          try{
               if(empty($email)){
                    return ParseError('Email field is empty');
               }

          $sql = $con->prepare("INSERT INTO $table (name, email, password, company) VALUES (?,?,?,?)");
          $sql->bind_param('ssss', $name, $email, $pass, $company);
          $sql->execute();

          return json_encode(['msg'=>'Inserted Successfully']);
          }catch(\Throwable $e){
               return ParseError($e);
          }
     }
     public function Update($table, $field, $value, $email){
          include 'login.php';
          // Function to udate user information
          try{
               $sql = "UPDATE $table SET $field='$value' WHERE email='$email'";
 
               if ($con->query($sql) === TRUE) {
                    return json_encode(['msg'=>'Updated Successfully']);
                  } else {
                       return ParseError($con->error);
                  }
               
          }catch(\Throwable $e){
               return ParseError($e);
          }
     }

     public function DelUser($table, $id, $email){
          include 'login.php';
          // Function to delete user

          try{
               $sql = "DELETE FROM $table WHERE $id='$email'";
               $req = $con->query($sql);

               if( $req === TRUE){
                    return json_encode(['msg' => 'Deleted Successfully']);
               }else{
                    return ParseError($con->error);
               }

          }catch(\Throwable $e){
               return ParseError($e);
          }
     }

     public function echoClass($greet){
          return $greet;
     } 

     // Functions perculiar to the table of applications
     // ****** MAJOR DIVISION ******


     public function addApp($dev_table, $email, $app_table, $name, $logo, $size, $version, $compatibility, $downloads, $status, $age, $dor){
          include 'login.php';

          try{
          // Get id of the developer
          $sql = "SELECT id FROM $dev_table WHERE email = '$email'";
          $res = $con->query($sql);

          if($res && mysqli_num_rows($res) > 0){
               // output data of each row
               while($row = mysqli_fetch_assoc($res)){
                    $devid = $row['id'];
               }
               // return $devid;
       
          } else {
               // return json_encode(['msg'=> NULL]);
               http_response_code(500);
               recordError($con->error); //Enter Error into log file
               return NULL;
          }
          // return;

          // After getting the id of the developer, insert the id and other details into the database
          
           $sql = $con->prepare("INSERT INTO `$app_table` (`name`, `size`, `version`, `compatibility`, `downloads`, `status`, `age group`, `date of release`, `logo`, `developer id`) VALUES (?,?,?,?,?,?,?,?,?,?)");
           $sql->bind_param('sssssssssi', $name, $size, $version, $compatibility, $downloads, $status, $age, $dor, $logo, $devid);
          
           if ($sql->execute() === TRUE) {
               return json_encode(['msg'=>'Inserted Successfully']);
          } else {
               return ParseError($con->error);
             }
           


     }catch(\Throwable $e){
          return ParseError($e);
     }
          
                    
     }

     // Get Logo and display in browser
     public function getLogo($table, $field, $id){
          include 'login.php';

          try{
                    $sql = "SELECT $field FROM $table WHERE id = '$id'";
                    $res = $con->query($sql);

                    if(mysqli_num_rows($res) > 0){
                         // output data of each row
                         while($row = mysqli_fetch_assoc($res)) {
                         $logo = $row[$field];
                         }
                         // return json_encode(['logo'=> 'uploads/'.$dat]);
                         echo '<br>';
                         echo "<p><img src='uploads/$logo' height='150px' width='300px'></p>";


                    } else {
                         // return json_encode(['msg'=> NULL]);
                         http_response_code(500);
                         recordError($con->error); //Enter Error into log file
                         return NULL;
                    }
          }catch(\Throwable $e){
               recordError($e); //Enter Error into log file
          }
     }

     public function getapp($dev_table, $email, $table, $fields){
          include 'login.php';

          try{

          $sql = "SELECT id FROM $dev_table WHERE email = '$email'";
          $res = $con->query($sql);

          if($res && mysqli_num_rows($res) > 0){
               // output data of each row
               while($row = mysqli_fetch_assoc($res)){
                    $devid = $row['id'];
               }
               // return $devid;

          // Get all applications of the developer
          $sql = "SELECT * FROM `$table` WHERE `developer id` = '$devid'";
          $res = $con->query($sql);

          if($res && mysqli_num_rows($res) > 0){
               $output = array();
               $i = 0;
               while($row = mysqli_fetch_assoc($res)){
                    foreach ($fields as $f){
                         $output[$i][$f] =  $row[$f];
                    }
                    $i++;
               }
               if(!empty($output)){
                    return json_encode($output);
               }else{
                    return ParseError('Data requested is empty');
               }
          }
                   
          } else {
               // return json_encode(['msg'=> NULL]);
               http_response_code(500);
               recordError($con->error); //Enter Error into log file
               return NULL;
  
          }
          }catch(\Throwable $e){
               ParseError($con->error);
          }
     }
}


function ParseError($error){
     recordError($error); //Enter Error into log file
     http_response_code(500);
     return json_encode(['error'=> $error]);
}

?>