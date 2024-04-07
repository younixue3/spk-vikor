<?php

require_once("../connection.php");

$id = isset($_GET["id"]) ? $_GET["id"] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_sub_kriteria = $_POST["nama_sub_kriteria"];
    $skala_nilai = $_POST["skala_nilai"];
    $id_kriteria = $_POST["id_kriteria"];

    $sql = "UPDATE sub_kriteria SET nama_sub_kriteria = :nama_sub_kriteria, skala_nilai = :skala_nilai, id_kriteria = :id_kriteria WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nama_sub_kriteria", $nama_sub_kriteria);
    $stmt->bindParam(":skala_nilai", $skala_nilai);
    $stmt->bindParam(":id_kriteria", $id_kriteria);
    $stmt->bindParam(":id", $id);

    if ($stmt->execute()) {
        header("Location: sub_kriteria.php");
        exit();
    } else {
        echo "Error: " . $conn->errorInfo()[0];
    }
}

$sql = "SELECT * FROM sub_kriteria WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$sub_kriteria = $stmt->fetch(PDO::FETCH_ASSOC);

$sql_kriteria = "SELECT * FROM kriteria";
$stmt_kriteria = $conn->prepare($sql_kriteria);
$stmt_kriteria->execute();
$kriteria = $stmt_kriteria->fetchAll(PDO::FETCH_ASSOC);

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
            <h1 class="">Edit Sub Kriteria</h1></div>
          <div class="card-body">
            <form name="formSubKriteria" id="formSubKriteria" method="post">
              <div class="grid-cols">
                <label class="input-label" for="nama_kriteria"><b>Kriteria</b></label>
                <select class="input-control" placeholder="Pilih Kriteria" id="id_kriteria"
                        name="id_kriteria">
                  <option>--- Pilih Kriteria ---</option>
                    <?php foreach ($kriteria as $item => $value) : ?>
                      <option selected="<?= $value['id'] == $sub_kriteria['id_kriteria'] ? 'selected' : '' ?>" value="<?= $value['id'] ?>"><?= $value['nama_kriteria'] ?></option>
                    <?php endforeach; ?>
                </select>
              </div>
              <div class="grid-cols">
                <label class="input-label" for="kriteria"><b>Nama Sub Kriteria</b></label>
                <input
                  class="input-control"
                  type="text"
                  placeholder="Masukkan Nama Sub Kriteria"
                  id="nama_sub_kriteria"
                  name="nama_sub_kriteria"
                  value="<?= $sub_kriteria['nama_sub_kriteria'] ?>"
                  required
                />
              </div>
              <div class="grid-cols">
                <label class="input-label" for="bobot"><b>Skala Nilai</b></label>
                <input
                  class="input-control"
                  type="number"
                  step="0.01"
                  placeholder="Masukkan Skala Nilai"
                  id="skala_nilai"
                  name="skala_nilai"
                  value="<?= $sub_kriteria['skala_nilai'] ?>"
                  required
                />
              </div>
              <input type="hidden" value="<?= $sub_kriteria['id'] ?>">
            </form>
          </div>
          <div class="card-footer">
            <a href="sub_kriteria.php" type="button" id="closeSubKriteriaModal" class="btn btn-secondary close"
               data-dismiss="modal">Tutup</a>
            <button type="submit" form="formSubKriteria" class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </section>
    </div>
  </main>
</div>
</body>
</html>