<?php
declare (strict_types=1);
use PHPUnit\Framework\TestCase;



final class DBTest extends TestCase{
     public function testgetUsers(): void{
          $this->assertInstanceOf(
               DB::class,
               DB::getUsers('users', array('id', 'name', 'email', 'password', 'company'))
          )
     }
}


?>