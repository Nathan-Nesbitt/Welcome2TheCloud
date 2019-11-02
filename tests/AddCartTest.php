<?php
use PHPUnit\Framework\TestCase;

final class AddCartTest extends TestCase
{
    function testGetCurrentItems() {
        $this -> assertNotNull(getCurrentItems());
    }
}

?>