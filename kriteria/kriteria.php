<?php

require_once('../connection.php');

$search = isset($_GET["search"]) ? $_GET["search"] : "";


$sql = "SELECT * FROM kriteria WHERE nama_kriteria LIKE '%$search%'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$kriteria = $stmt->fetchAll(PDO::FETCH_ASSOC);


function add_leading_zero($value, $threshold = 1) {
    return sprintf('%0' . $threshold . 's', $value);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql_last = "SELECT * FROM kriteria ORDER BY id DESC limit 1";
    $stmt_last = $conn->query($sql_last);
    $stmt_last->execute();
    $lastkriteria = $stmt_last->fetch(PDO::FETCH_ASSOC);
    $kode = "C".(add_leading_zero($lastkriteria['id'] ? $lastkriteria['id'] + 1 : 1));

    $nama_kriteria = $_POST["nama_kriteria"];
    $bobot = $_POST["bobot"];

    $sql = "INSERT INTO kriteria (nama_kriteria, bobot, kode_kriteria) VALUES (:nama_kriteria, :bobot, :kode_kriteria)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nama_kriteria", $nama_kriteria);
    $stmt->bindParam(":kode_kriteria", $kode);
    $stmt->bindParam(":bobot", $bobot);

    if ($stmt->execute()) {
        header("Location: kriteria.php");
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
  <div class="modal fade" id="modalKriteria" tabindex="-1" aria-labelledby="modalKriteriaLabel"
       aria-hidden="true">
    <div class="modal-content modal-md animate">
      <div class="modal-header flex justify-beetwen">
        <h5 class="modal-title" id="modalKriteriaLabel">Kelola Kriteria</h5>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="formKriteria" name="formKriteria">
          <div class="grid-cols">
            <label class="input-label" for="nama_kriteria"><b>Nama Kriteria</b></label>
            <input
              class="input-control"
              type="text"
              placeholder="Masukkan Nama Kriteria"
              id="nama_kriteria"
              name="nama_kriteria"
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
              required
            />
          </div>
          <div class="modal-footer">
            <button type="button" id="closeKriteriaModal" class="btn btn-secondary close" data-dismiss="modal">Tutup</button>
            <button type="submit" form="formKriteria" class="btn btn-primary">Simpan</button>
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
        <h1 class="col-10">Daftar Kriteria</h1>
        <form action="" method="get" class="col-3">
          <label class="input-label">Search</label>
          <input class="input-control" name="search" placeholder="search"/>
        </form>
        <div class="col-7"></div>
        <div class="col-8"></div>
        <button type="button" class="btn btn-primary" id="openKriteriaModal" data-toggle="modal"
                data-target="#modalKriteria">
          Kelola Kriteria
        </button>
        <div class="card col-10">
          <section class="card-body">
            <table class="">
              <thead>
              <tr class="">
                <th scope="col" style="width: 20px;">No</th>
                <th scope="col" style="width: 20px;">Kode</th>
                <th scope="col">Kriteria</th>
                <th scope="col">Bobot</th>
                <th scope="col" style="width: 300px;">Action</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($kriteria as $item => $value) : ?>
                <tr class="">
                  <td class=""><?= $value['id'] ?></td>
                  <td class=""><?= $value['kode_kriteria'] ?></td>
                  <td class=""><?= $value['nama_kriteria'] ?></td>
                  <td class=""><?= $value['bobot'] ?></td>
                  <td class="">
                    <a href="edit.php?id=<?= $value['id'] ?>" class="btn btn-warning">
                      Edit
                    </a>
                    <a href="delete.php?id=<?= $value['id'] ?>" onclick="return confirm('Yakin ingin menghapus kriteria ini?')" class="btn btn-danger">
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
    const modal = document.getElementById('modalKriteria');
    const closeModalBtn = document.getElementById('closeKriteriaModal');

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