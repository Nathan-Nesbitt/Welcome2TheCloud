<?php
use PHPUnit\Framework\TestCase;

final class AccountTest extends TestCase
{
    public function test() {
        $this -> assertNotNull(Account::createHashedPassword("foo"));
    }
}

?>