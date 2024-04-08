<?php

require_once('../connection.php');

$search = isset($_GET["search"]) ? $_GET["search"] : "";

$sql = "SELECT id, nama_alternatif, kode, (SELECT SUM(penilaian) FROM spk where spk.id_alternatif = alternatif.id) as total FROM alternatif order by total DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$Alternatif = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
<div class="container">
    <?php include '../components/layout/banner.php' ?>
  <main>
      <?php include '../components/layout/header.php' ?>
    <div class="content">
      <section class="grid-cols-10 gap-1">
        <h1 class="col-10">Perangkingan</h1>
        <div class="card col-10">
          <section class="card-body">
            <table class="">
              <thead>
              <tr class="">
                <th scope="col" style="width: 20px;">No</th>
                <th scope="col">Kode</th>
                <th scope="col">Alternatif</th>
                  <?php foreach ($kriteria as $item => $valuekriteria) : ?>
                    <th scope="col"><?= $valuekriteria['nama_kriteria'] ?></th>
                  <?php endforeach; ?>
                <th scope="col">Nilai</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($Alternatif as $item => $value) : ?>
                <tr class="">
                  <td class=""><?= $value['id'] ?></td>
                  <td class=""><?= $value['kode'] ?></td>
                  <td class=""><?= $value['nama_alternatif'] ?></td>
                    <?php foreach ($kriteria as $item => $valuekriteria) : ?>
                      <td scope="col">
                          <?php
                          $sql_spk = "SELECT * FROM spk JOIN spk_vikor.sub_kriteria sk on spk.id_sub_kriteria = sk.id JOIN spk_vikor.kriteria k on spk.id_kriteria = k.id WHERE id_alternatif = :id_alternatif AND spk.id_kriteria = :id_kriteria";
                          $stmt_spk = $conn->prepare($sql_spk);
                          $stmt_spk->bindParam(':id_alternatif', $value['id']);
                          $stmt_spk->bindParam(':id_kriteria', $valuekriteria['id']);
                          $stmt_spk->execute();
                          $spk = $stmt_spk->fetch(PDO::FETCH_ASSOC);
                          echo isset($spk['id_sub_kriteria']) ? $spk['skala_nilai'] : '';
                          ?>
                      </td>
                    <?php endforeach; ?>
                  <td class=""><?= $value['total'] ?></td>
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
