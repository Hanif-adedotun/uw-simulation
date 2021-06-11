<?php
declare (strict_types=1);
use PHPUnit\Framework\TestCase;



class userdbTest extends TestCase{
     public function testEcho(){
          require 'database.php';
          $datab = new DB;
          $this->assertEquals('Hello', $datab->echoClass('Hello'));
     }

     // Test for the GET Option of Users api
     // @tests get user from (email), update user information from (email, field , value), verify user information from (email,pass)
     public function testUserGet(){
          $uri = 'http://localhost/uw%20simulation/uw-simulation/api/user.php';
          $client = new GuzzleHttp\Client();

          $queries = array('email=hanif.dev@gmail.com', 'edit=hanif.dev@gmail.com&field=name&value=Hanif Coder', 'verify=hanif.dev@gmail.com&pass=hanif_12', NULL);
          $i = 0;
          foreach ($queries as $q) {
               $res = $client->request('GET', $uri, ['query' => $q]);
               $this->assertEquals(200, $res->getStatusCode());
     
               $contentType = $res->getHeaders()["Content-Type"][0];
               $this->assertEquals("application/json", $contentType);
               
               $body = $res->getBody();
               switch($i){//Dependng on the query, the test assertation changes
                    case 0:
                         $bodArr = json_decode((string) $body, true)[0];
                         $this->assertArrayHasKey('name', $bodArr);
                    break;

                    case 1:
                         $bodArr = json_decode((string) $body, true);
                         $this->assertEquals('Updated Successfully', $bodArr['msg']);
                    break;

                    case 2:
                         $bodArr = json_decode((string) $body, true);
                         $this->assertEquals(true, $bodArr['authenticated']);
                    break;

                    case 3:
                         $bodArr = json_decode((string) $body, true)[0];
                         $this->assertArrayHasKey('name', $bodArr);
                    break;
               }
               $i++; //Loop through the queries 
          }
   

     }

     // POST test for User API 
     public function testUserPost(){
          $uri = 'http://localhost/uw%20simulation/uw-simulation/api/user.php';
          $client = new GuzzleHttp\Client();

          $reqBody = array("name"=>"Elon Musk", "email" => "elon@neuralink.com", "password" => "elon1&@2", "company" => "Neuralink");
        
          $res = $client->request('POST', $uri , ['headers' =>['content-type' => 'application/json'], 'body' => json_encode($reqBody)]);
          $body = $res->getBody();

          $bodArr = json_decode((string) $body, true);
          $this->assertEquals('Inserted Successfully', $bodArr['msg']);
     }

     public function testappGet(){
          //All test functions for application api
          $uri = 'http://localhost/uw%20simulation/uw-simulation/api/app.php';
          $client = new GuzzleHttp\Client();

          // The queries as an array
          $queries = array(NULL, 'logo=4', 'email=sovam.dev@gmail.com');
          $i = 0;

          foreach($queries as $q){
               $res = $client->request('GET', $uri , ['query' => $q]);
               $this->assertEquals(200, $res->getStatusCode());
          
               // Test the result being given
               $body = $res->getBody();

               switch($i){//Dependng on the query, the test assertation changes
                    case 0:
                         $contentType = $res->getHeaders()["Content-Type"][0];
                         $this->assertEquals("application/json", $contentType);

                         $bodArr = json_decode((string) $body, true)[0];
                         $this->assertArrayHasKey('date of release', $bodArr);
                    break;

                    case 1:
                         $contentType = $res->getHeaders()["Content-Type"][0];
                         $this->assertEquals("text/html;charset=UTF-8", $contentType);

                         $bodArr = (string) $body;
                         $this->assertStringContainsStringIgnoringCase("<p><img src='uploads/banner.png' height='150px' width='300px'></p>", $bodArr);
                    break;

                    case 2:
                         $contentType = $res->getHeaders()["Content-Type"][0];
                         $this->assertEquals("application/json", $contentType);

                         $bodArr = json_decode((string) $body, true)[0];
                         $this->assertArrayHasKey('compatibility', $bodArr);
                    break;

               }
               $i++; //Loop through the queries 
               
          }
         
     }

      // POST test for User API 
      public function testAppPost(){
          $uri = 'http://localhost/uw%20simulation/uw-simulation/api/app.php';
          $client = new GuzzleHttp\Client();

          $reqBody = array( "email" => "sovam.dev@gmail.com", "name" => "Second test application", "size" => "50mb", "version" => "2.1.2", "compatibility" => "Android 10+", "downloads" => "4000000", "status" => "Published", "age group" => "12+", "date of release" => "2021-06-07", "logo" => "hanif_ui.png");
         
          $res = $client->request('POST', $uri , ['headers' =>['content-type' => 'application/json'],'body' => json_encode($reqBody)]);
          $body = $res->getBody();

          $bodArr = json_decode((string) $body, true);
          $this->assertEquals('Inserted Successfully', $bodArr['msg']);
     }


     
}

// Open command prompt in C:\xamppp\htdocs\uw simulation\uw-simulation
// Then run tests using the command below
// phpunit tests\userdbTest.php

// or 
// Open command prompt in C:\xamppp\htdocs\uw simulation\uw-simulation
// composer test
?>