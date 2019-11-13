<?php
use PHPUnit\Framework\TestCase;

final class ListProductTest extends TestCase
{
    public function testTemplate() {
        $this -> assertNull(printTableProd());
    }
}

?>