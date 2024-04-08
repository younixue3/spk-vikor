<?php

require_once("../connection.php");

$id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_alternatif = $_POST["nama_alternatif"];

    $sql = "UPDATE alternatif SET nama_alternatif = :nama_alternatif WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nama_alternatif", $nama_alternatif);
    $stmt->bindParam(":id", $id);

    if ($stmt->execute()) {
        header("Location: alternatif.php");
        exit();
    } else {
        echo "Error: " . $conn->errorInfo()[0];
    }
}

$sql = "SELECT * FROM alternatif WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$alternatif = $stmt->fetch(PDO::FETCH_ASSOC);

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
<div class="container">
    <?php include '../components/layout/banner.php' ?>
    <main>
        <?php include '../components/layout/header.php' ?>
        <div class="content">
            <section class="grid-cols gap-1">
                <div class="card bg-secondary">
                    <div class="card-header">
                        <h1 class="">Edit Alternatif</h1></div>
                    <div class="card-body">
                        <form name="formAlternatif" id="formAlternatif" method="post">
                            <div class="grid-cols">
                                <label class="input-label" for="Alternatif"><b>Nama Alternatif</b></label>
                                <input
                                    class="input-control"
                                    type="text"
                                    placeholder="Masukkan Nama Alternatif"
                                    id="nama_alternatif"
                                    name="nama_alternatif"
                                    value="<?= $alternatif['nama_alternatif'] ?>"
                                    required
                                />
                            </div>
                            <input type="hidden" value="<?= $alternatif['id'] ?>">
                        </form>
                    </div>
                    <div class="card-footer">
                            <a href="alternatif.php" type="button" id="closeAlternatifModal" class="btn btn-secondary close" data-dismiss="modal">Tutup</a>
                            <button type="submit" form="formAlternatif" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>
</body>
</html>