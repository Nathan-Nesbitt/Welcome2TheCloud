<?php
use PHPUnit\Framework\TestCase;

final class addcartTest extends TestCase
{
    public function testGetCurrentItems() {
        $this -> assertNotNull(getCurrentItems());
    }
}

?>