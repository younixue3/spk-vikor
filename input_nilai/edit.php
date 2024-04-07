<?php

require_once("../connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sub_kriteria = $_POST["id_sub_kriteria"];
    $id = $_POST["id"];
    echo $id_sub_kriteria;
    echo $id;
    $sql = "UPDATE spk SET id_sub_kriteria = :id_sub_kriteria WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id_sub_kriteria", $id_sub_kriteria);
    $stmt->bindParam(":id", $id);

    if ($stmt->execute()) {
        header("Location: ". $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error: " . $conn->errorInfo()[0];
    }
}
?>