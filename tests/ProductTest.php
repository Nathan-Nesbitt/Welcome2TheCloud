<?php
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase {

    public function testTemplate() {
        $connection = new mysqli($host="localhost", $username="username", $passwd="password", $dbname="testDatabase");
        $this->assertNotNull(Product::getProducts($connection));
    }
}

?>