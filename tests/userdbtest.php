<?php
declare (strict_types=1);
use PHPUnit\Framework\TestCase;



// final class DBTest extends TestCase{
//      public function testgetUsers(): void{
//           $this->assertInstanceOf(
//                DB::class,
//                DB::getUsers('users', array('id', 'name', 'email', 'password', 'company'))
//           );
//      }
// }

class userdbTest extends TestCase{
     public function testEcho(){
          require 'database.php';
          $datab = new DB;
          $this->assertEquals('Hell', $datab->echoClass('Hello'));
     }
}

// Open command prompt in C:\xamppp\htdocs\uw simulation\uw-simulation
// Then run tests using the command below
// phpunit tests\userdbTest.php
?>