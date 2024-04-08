<?php

require_once('../connection.php');

$search = isset($_GET["search"]) ? $_GET["search"] : "";


$sql = "SELECT sub_kriteria.id as id, nama_sub_kriteria, nama_kriteria, skala_nilai FROM sub_kriteria JOIN kriteria ON sub_kriteria.id_kriteria = kriteria.id WHERE nama_sub_kriteria LIKE '%$search%'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$sub_kriteria = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_kriteria = "SELECT * FROM kriteria";
$stmt_kriteria = $conn->prepare($sql_kriteria);
$stmt_kriteria->execute();
$kriteria = $stmt_kriteria->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_sub_kriteria = $_POST["nama_sub_kriteria"];
    $skala_nilai = $_POST["skala_nilai"];
    $id_kriteria = $_POST["id_kriteria"];

    $sql = "INSERT INTO sub_kriteria (nama_sub_kriteria, skala_nilai, id_kriteria) VALUES (:nama_sub_kriteria, :skala_nilai, :id_kriteria)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nama_sub_kriteria", $nama_sub_kriteria);
    $stmt->bindParam(":skala_nilai", $skala_nilai);
    $stmt->bindParam(":id_kriteria", $id_kriteria);

    if ($stmt->execute()) {
        header("Location: sub_kriteria.php");
        exit();
    } else {
        echo "Error: " . $conn->errorInfo()[0];
    }
}

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
  <div class="modal fade" id="modalSubKriteria" tabindex="-1" aria-labelledby="modalSubKriteriaLabel"
       aria-hidden="true">
    <div class="modal-content modal-md animate">
      <div class="modal-header flex justify-beetwen">
        <h5 class="modal-title" id="modalSubKriteriaLabel">Kelola Sub Kriteria</h5>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="formSubKriteria" name="formSubKriteria">
          <div class="grid-cols">
            <label class="input-label" for="nama_kriteria"><b>Kriteria</b></label>
            <select class="input-control" placeholder="Pilih Kriteria" id="id_kriteria"
                    name="id_kriteria">
                <option value="">--- Pilih Kriteria ---</option>
                <?php foreach ($kriteria as $item => $value) : ?>
                  <option value="<?= $value['id'] ?>"><?= $value['nama_kriteria'] ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="grid-cols">
            <label class="input-label" for="nama_kriteria"><b>Nama Sub Kriteria</b></label>
            <input
              class="input-control"
              type="text"
              placeholder="Masukkan Nama sub_kriteria"
              id="nama_sub_kriteria"
              name="nama_sub_kriteria"
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
              required
            />
          </div>
          <div class="modal-footer">
            <button type="button" id="closeSubKriteriaModal" class="btn btn-secondary close" data-dismiss="modal">Tutup</button>
            <button type="submit" form="formSubKriteria" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
    <?php include '../components/layout/banner.php' ?>
  <main>
      <?php include '../components/layout/header.php' ?>
    <div class="content">
      <section class="grid-cols-10 gap-1">
        <h1 class="col-10">Daftar Sub Kriteria</h1>
        <form action="" method="get" class="col-3">
          <label class="input-label">Search</label>
          <input class="input-control" name="search" placeholder="search"/>
        </form>
        <div class="col-7"></div>
        <div class="col-8"></div>
        <button type="button" class="btn btn-primary" id="openKriteriaModal" data-toggle="modal"
                data-target="#modalSubKriteria">
          Kelola Sub Kriteria
        </button>
        <div class="card col-10">
          <section class="card-body">
            <table class="">
              <thead>
              <tr class="">
                <th scope="col" style="width: 20px;">No</th>
                <th scope="col">Kriteria</th>
                <th scope="col">Sub Kriteria</th>
                <th scope="col">Skala Nilai</th>
                <th scope="col" style="width: 300px;">Action</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($sub_kriteria as $item => $value) : ?>
                <tr class="">
                  <td class=""><?= $value['id'] ?></td>
                  <td class=""><?= $value['nama_kriteria'] ?></td>
                  <td class=""><?= $value['nama_sub_kriteria'] ?></td>
                  <td class=""><?= $value['skala_nilai'] ?></td>
                  <td class="">
                    <a href="edit.php?id=<?= $value['id'] ?>" class="btn btn-warning">
                      Edit
                    </a>
                    <a href="delete.php?id=<?= $value['id'] ?>" onclick="return confirm('Yakin ingin menghapus sub_kriteria ini?')" class="btn btn-danger">
                      Delete
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </section>
        </div>
      </section>
    </div>
  </main>
</div>
</body>
<script>
    const openKriteriaModalBtn = document.getElementById('openKriteriaModal');
    const modal = document.getElementById('modalSubKriteria');
    const closeModalBtn = document.getElementById('closeSubKriteriaModal');

    openKriteriaModalBtn.addEventListener('click', () => {
        modal.classList.add('show');
        modal.classList.remove('fade');
    });

    closeModalBtn.addEventListener('click', () => {
        modal.classList.remove('show');
        modal.classList.add('fade');
    });

    // Add similar logic for closing modal on clicking outside the modal content
</script>
</html>