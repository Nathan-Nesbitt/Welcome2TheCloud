<?php
use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase
{
    public function testTemplate() {
        $connection = new mysqli($host="localhost", $username="username", $passwd="password", $dbname="testDatabase");
        $this->assertNotNull((new Category)->getCategories($connection));
    }
}

?>