<?php

require_once('../connection.php');

$search = isset($_GET["search"]) ? $_GET["search"] : "";


$sql = "SELECT * FROM alternatif WHERE nama_alternatif LIKE '%$search%'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$Alternatif = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_alternatif = $_POST["nama_alternatif"];

    $sql = "INSERT INTO alternatif (nama_alternatif) VALUES (:nama_alternatif)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nama_alternatif", $nama_alternatif);

    if ($stmt->execute()) {
        header("Location: alternatif.php");
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
<div class="container h-100">
  <div class="modal fade" id="modalAlternatif" tabindex="-1" aria-labelledby="modalAlternatifLabel"
       aria-hidden="true">
    <div class="modal-content modal-md animate">
      <div class="modal-header flex justify-beetwen">
        <h5 class="modal-title" id="modalAlternatifLabel">Kelola Alternatif</h5>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="formAlternatif" name="formAlternatif">
          <div class="grid-cols">
            <label class="input-label" for="nama_alternatif"><b>Nama Alternatif</b></label>
            <input
              class="input-control"
              type="text"
              placeholder="Masukkan Nama Alternatif"
              id="nama_alternatif"
              name="nama_alternatif"
              required
            />
          </div>
          <div class="modal-footer">
            <button type="button" id="closeAlternatifModal" class="btn btn-secondary close" data-dismiss="modal">Tutup</button>
            <button type="submit" form="formAlternatif" class="btn btn-primary">Simpan</button>
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
        <h1 class="col-10">Daftar Alternatif</h1>
        <form action="" method="get" class="col-3">
          <label class="input-label">Search</label>
          <input class="input-control" name="search" placeholder="search"/>
        </form>
        <div class="col-7"></div>
        <div class="col-8"></div>
        <button type="button" class="btn btn-primary" id="openAlternatifModal" data-toggle="modal"
                data-target="#modalAlternatif">
          Kelola Alternatif
        </button>
        <div class="card col-10">
          <section class="card-body">
            <table class="">
              <thead>
              <tr class="">
                <th scope="col" style="width: 20px;">No</th>
                <th scope="col">Alternatif</th>
                <th scope="col" style="width: 300px;">Action</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($Alternatif as $item => $value) : ?>
                <tr class="">
                  <td class=""><?= $value['id'] ?></td>
                  <td class=""><?= $value['nama_alternatif'] ?></td>
                  <td class="">
                    <a href="edit.php?id=<?= $value['id'] ?>" class="btn btn-warning">
                      Edit
                    </a>
                    <a href="delete.php?id=<?= $value['id'] ?>" onclick="return confirm('Yakin ingin menghapus Alternatif ini?')" class="btn btn-danger">
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
    const openAlternatifModalBtn = document.getElementById('openAlternatifModal');
    const modal = document.getElementById('modalAlternatif');
    const closeModalBtn = document.getElementById('closeAlternatifModal');

    openAlternatifModalBtn.addEventListener('click', () => {
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