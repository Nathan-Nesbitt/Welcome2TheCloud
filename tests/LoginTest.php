<?php
use PHPUnit\Framework\TestCase;

final class LoginTest extends TestCase
{
    public function testTemplate() {
        $connection = new mysqli($host="localhost", $username="username", $passwd="password", $dbname="testDatabase");
        $this->assertNotFalse(Login::loginUser($connection, "arnold", "test"));
    }
}

?>