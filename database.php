<?php

include 'login.php';
// Create a JSON error parser that converts error to JSON
// Create an errror logger to file also

class DB{
     public function GetUsers($table, $fields){
          include 'login.php';
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
          }
          return json_encode($output);
     }
     // Gets the
     public function GetUser($table, $field, $fields, $data){
          include 'login.php';
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
               return ParseError($con);
          }else{
               return json_encode(['msg'=> NULL]);
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
 
               if ($conn->query($sql) === TRUE) {
                    return json_encode(['msg'=>'Updated Successfully']);
                  } else {
                       return ParseError($conn->error);
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

               if($con->query($sql)){
                    return json_encode(['msg' => 'Deleted User Successfully']);
               }else{
                    return ParseError($con->error);
               }

          }catch(\Throwable $e){
               return ParseError($e);
          }
     }

     public function testClass($data){
          return 'Hello, let me echo your data: '.$data;
     } 
}


function ParseError($error){
     return json_encode(['error'=> $error]);
}

?>