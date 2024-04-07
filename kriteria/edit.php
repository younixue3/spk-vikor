<?php

require_once("../connection.php");

$id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_kriteria = $_POST["nama_kriteria"];
    $bobot = $_POST["bobot"];

    $sql = "UPDATE kriteria SET nama_kriteria = :nama_kriteria, bobot = :bobot WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nama_kriteria", $nama_kriteria);
    $stmt->bindParam(":bobot", $bobot);
    $stmt->bindParam(":id", $id);

    if ($stmt->execute()) {
        header("Location: kriteria.php");
        exit();
    } else {
        echo "Error: " . $conn->errorInfo()[0];
    }
}

$sql = "SELECT * FROM kriteria WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$kriteria = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../asset/css/main.css"/>
</head>
<body>
<div class="container h-100">
    <?php include '../components/layout/banner.php' ?>
    <main>
        <?php include '../components/layout/header.php' ?>
        <div class="content">
            <section class="grid-cols gap-1">
                <div class="card bg-secondary">
                    <div class="card-header">
                        <h1 class="">Edit Kriteria</h1></div>
                    <div class="card-body">
                        <form name="formKriteria" id="formKriteria" method="post">
                            <div class="grid-cols">
                                <label class="input-label" for="kriteria"><b>Nama Kriteria</b></label>
                                <input
                                    class="input-control"
                                    type="text"
                                    placeholder="Masukkan Nama Kriteria"
                                    id="nama_kriteria"
                                    name="nama_kriteria"
                                    value="<?= $kriteria['nama_kriteria'] ?>"
                                    required
                                />
                            </div>
                            <div class="grid-cols">
                                <label class="input-label" for="bobot"><b>Bobot Kriteria</b></label>
                                <input
                                    class="input-control"
                                    type="number"
                                    step="0.01"
                                    placeholder="Masukkan Bobot Kriteria"
                                    id="bobot"
                                    name="bobot"
                                    value="<?= $kriteria['bobot'] ?>"
                                    required
                                />
                            </div>
                            <input type="hidden" value="<?= $kriteria['id'] ?>">
                        </form>
                    </div>
                    <div class="card-footer">
                            <a href="kriteria.php" type="button" id="closeKriteriaModal" class="btn btn-secondary close" data-dismiss="modal">Tutup</a>
                            <button type="submit" form="formKriteria" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>
</body>
</html>