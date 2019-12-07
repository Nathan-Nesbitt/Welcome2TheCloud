<?php
use PHPUnit\Framework\TestCase;

final class AdminTest extends TestCase
{
    public function testTemplate() {

        $connection = new mysqli($host="localhost", $username="username", $passwd="password", $dbname="testDatabase");

        $this->assertNotNull(Admin::getOrderAmountAndTotalPrice($connection));
    }
}

?>