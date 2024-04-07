<?php

require_once("../connection.php");

$id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($id) {
    $sql = "DELETE FROM kriteria WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
}

header("Location: alternatif.php");
exit();

?>