<?php
use PHPUnit\Framework\TestCase;

final class AddCartTest extends TestCase
{
    function GetCurrentItemsTest() {
        $this -> assertNotNull(getCurrentItems());
    }
}

?>