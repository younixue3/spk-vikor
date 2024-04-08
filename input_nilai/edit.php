<?php

require_once("../connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sub_kriteria = $_POST["id_sub_kriteria"];
    $id = $_POST["id"];

    $sql_sub_kriteria = "SELECT * FROM sub_kriteria JOIN spk_vikor.kriteria k on sub_kriteria.id_kriteria = k.id WHERE sub_kriteria.id = :id";
    $stmt_sub_kriteria = $conn->prepare($sql_sub_kriteria);
    $stmt_sub_kriteria->bindParam(":id", $id_sub_kriteria);
    $stmt_sub_kriteria->execute();
    $sub_kriteria = $stmt_sub_kriteria->fetch(PDO::FETCH_ASSOC);
    $total_nilai = ($sub_kriteria["skala_nilai"] * $sub_kriteria['bobot']) / 10;
    echo $id_sub_kriteria;
    echo $id;
    $sql = "UPDATE spk SET id_sub_kriteria = :id_sub_kriteria, penilaian = :penilaian WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id_sub_kriteria", $id_sub_kriteria);
    $stmt->bindParam(":penilaian", $total_nilai);
    $stmt->bindParam(":id", $id);

    if ($stmt->execute()) {
        header("Location: ". $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error: " . $conn->errorInfo()[0];
    }
}
?>