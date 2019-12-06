<?php
function getCategories($connection) {
        $query = $connection->prepare("SELECT categoryName FROM category");
		$query->execute();
        $result = $query->get_result();
        return $result;
}
?>