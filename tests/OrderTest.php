<?php
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function testTemplate() {
        $connection = new mysqli($host="localhost", $username="username", $passwd="password", $dbname="testDatabase");
        $this->assertNotFalse((new Order)->getProductsInOrder($connection, 1));
    }
}

?>