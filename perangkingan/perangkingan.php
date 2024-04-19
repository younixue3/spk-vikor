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
        <div class="card bg-warning col-10">
          <section class="card-header">
            <h3>Matriks Keputusan</h3>
          </section>
          <section class="card-body">
            <table class="">
              <thead>
              <tr class="">
                <th scope="col" style="width: 20px;">No</th>
                <th scope="col">Nama Alternatif</th>
                  <?php foreach ($kriteria as $item => $valuekriteria) : ?>
                    <th scope="col"><?= $valuekriteria['nama_kriteria'] ?></th>
                  <?php endforeach; ?>
              </tr>
              </thead>
              <tbody>
              <?php
              $matrixKeputusan = [];
              ?>
              <?php foreach ($Alternatif as $item => $value) : ?>
                <tr class="">
                  <td class=""><?= $value['id'] ?></td>
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
                          if (isset($spk['id_sub_kriteria'])) {
                              $nilaiKriteria[$spk['nama_kriteria']][] = $spk['skala_nilai'];
                              $matrixKeputusan[$value['id']][] = $spk['skala_nilai'];
                          }
                          ?>
                      </td>
                    <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </section>
        </div>
        <div class="card bg-warning col-10">
          <section class="card-header">
            <h3>Menentukan Bobot Kriteria</h3>
          </section>
          <section class="card-body">
            <table class="">
              <thead>
              <tr class="">
                <th scope="col" style="width: 20px;">No</th>
                <th scope="col" style="width: 20px;">Kode</th>
                <th scope="col">Nama</th>
                <th scope="col">Bobot</th>
                <th scope="col">Normal</th>
              </tr>
              </thead>
              <tbody>
              <?php
              $totalBobot = 0;
              $totalBobotNorm = 0;
              ?>
              <?php foreach ($kriteria as $item => $value) : ?>
                <tr class="">
                  <td class=""><?= $value['id'] ?></td>
                  <td class=""><?= $value['kode_kriteria'] ?></td>
                  <td class=""><?= $value['nama_kriteria'] ?></td>
                  <td class=""><?= $value['bobot'] ?></td>
                  <td class=""><?= $value['bobot'] / 100 ?></td>
                </tr>
                  <?php
                  $totalBobot += $value['bobot'];
                  $totalBobotNorm += $value['bobot'] / 100;
                  ?>
              <?php endforeach; ?>
              <tr>
                <td>Total</td>
                <td></td>
                <td></td>
                <td class=""><?= $totalBobot ?></td>
                <td class=""><?= $totalBobotNorm ?></td>
              </tr>
              </tbody>
            </table>
          </section>
        </div>
        <div class="card bg-warning col-10">
          <section class="card-header">
            <h3>Normalisasi Matriks</h3>
          </section>
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
                          echo isset($spk['id_sub_kriteria']) ?  round(($spk['skala_nilai'] - min($nilaiKriteria[$valuekriteria['nama_kriteria']])) / (max($nilaiKriteria[$valuekriteria['nama_kriteria']]) - min($nilaiKriteria[$valuekriteria['nama_kriteria']])),3) : '';
                          ?>
                      </td>
                    <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </section>
        </div>
        <div class="card bg-warning col-10">
          <section class="card-header">
            <h3>Normalisasi Matriks terbobot</h3>
          </section>
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
                          echo isset($spk['id_sub_kriteria']) ?  round(($spk['skala_nilai'] - min($nilaiKriteria[$valuekriteria['nama_kriteria']])) / (max($nilaiKriteria[$valuekriteria['nama_kriteria']]) - min($nilaiKriteria[$valuekriteria['nama_kriteria']])) * ($valuekriteria['bobot'] / 100),3) : '';
                          ?>
                      </td>
                    <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </section>
        </div>
        <div class="card bg-warning col-10">
          <section class="card-header">
            <h3>Normalisasi Matriks terbobot</h3>
          </section>
          <section class="card-body">
            <table class="">
              <thead>
              <tr class="">
                <th scope="col" style="width: 20px;">No</th>
                <th scope="col">Kode</th>
                <th scope="col">Alternatif</th>
                <th scope="col">S</th>
                <th scope="col">R</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($Alternatif as $item => $value) : ?>
                <tr class="">
                  <td class=""><?= $value['id'] ?></td>
                  <td class=""><?= $value['kode'] ?></td>
                  <td class=""><?= $value['nama_alternatif'] ?></td>
                    <?php
                    $sValue = [];
                    ?>
                    <?php foreach ($kriteria as $item => $valuekriteria) : ?>
                          <?php
                          $sql_spk = "SELECT * FROM spk JOIN spk_vikor.sub_kriteria sk on spk.id_sub_kriteria = sk.id JOIN spk_vikor.kriteria k on spk.id_kriteria = k.id WHERE id_alternatif = :id_alternatif AND spk.id_kriteria = :id_kriteria";
                          $stmt_spk = $conn->prepare($sql_spk);
                          $stmt_spk->bindParam(':id_alternatif', $value['id']);
                          $stmt_spk->bindParam(':id_kriteria', $valuekriteria['id']);
                          $stmt_spk->execute();
                          $spk = $stmt_spk->fetch(PDO::FETCH_ASSOC);
                          if (isset($spk['id_sub_kriteria'])) {
                            $sValue[] = round(($spk['skala_nilai'] - min($nilaiKriteria[$valuekriteria['nama_kriteria']])) / (max($nilaiKriteria[$valuekriteria['nama_kriteria']]) - min($nilaiKriteria[$valuekriteria['nama_kriteria']])) * ($valuekriteria['bobot'] / 100), 3);
                          }
                          ?>
                    <?php endforeach; ?>
                  <td class=""><?= isset($spk['id_sub_kriteria']) ? array_sum($sValue) : 0 ?></td>
                  <td class=""><?= isset($spk['id_sub_kriteria']) ? max($sValue) : 0 ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </section>
        </div>
        <div class="card bg-warning col-10">
          <section class="card-header">
            <h3>Menghitung Indeks Vikor (Q)</h3>
          </section>
          <section class="card-body">
            <table class="">
              <thead>
              <tr class="">
                <th scope="col" style="width: 20px;">No</th>
                <th scope="col">Kode</th>
                <th scope="col">Alternatif</th>
                <th scope="col">Q</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($Alternatif as $item => $value) : ?>
                <tr class="">
                  <td class=""><?= $value['id'] ?></td>
                  <td class=""><?= $value['kode'] ?></td>
                  <td class=""><?= $value['nama_alternatif'] ?></td>
                    <?php
                    $nMatriksTerbobot = [];
                    ?>
                    <?php foreach ($kriteria as $item => $valuekriteria) : ?>
                        <?php
                        $sql_spk = "SELECT * FROM spk JOIN spk_vikor.sub_kriteria sk on spk.id_sub_kriteria = sk.id JOIN spk_vikor.kriteria k on spk.id_kriteria = k.id WHERE id_alternatif = :id_alternatif AND spk.id_kriteria = :id_kriteria";
                        $stmt_spk = $conn->prepare($sql_spk);
                        $stmt_spk->bindParam(':id_alternatif', $value['id']);
                        $stmt_spk->bindParam(':id_kriteria', $valuekriteria['id']);
                        $stmt_spk->execute();
                        $spk = $stmt_spk->fetch(PDO::FETCH_ASSOC);
                        if (isset($spk['id_sub_kriteria'])) {
                          $nMatriksTerbobot[] = round(($spk['skala_nilai'] - min($nilaiKriteria[$valuekriteria['nama_kriteria']])) / (max($nilaiKriteria[$valuekriteria['nama_kriteria']]) - min($nilaiKriteria[$valuekriteria['nama_kriteria']])) * ($valuekriteria['bobot'] / 100),3);

                            $V_score = 0.5 * (max($nilaiKriteria[$valuekriteria['nama_kriteria']]) - min($nilaiKriteria[$valuekriteria['nama_kriteria']]));
                        }
                        ?>
                    <?php endforeach; ?>
                  <?php
                  ?>
                  <td class=""><?= isset($spk['id_sub_kriteria']) ? (0.5 * (max($nMatriksTerbobot) - min($nMatriksTerbobot))) + ( 0.5 * (array_sum($nMatriksTerbobot) / count($nMatriksTerbobot))) : 0 ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </section>
        </div>
        <div class="card bg-warning col-10">
          <section class="card-header">
            <h3>Perangkingan</h3>
          </section>
          <section class="card-body">
            <table class="">
              <thead>
              <tr class="">
                <th scope="col" style="width: 20px;">No</th>
                <th scope="col">Kode</th>
                <th scope="col">Alternatif</th>
                <th scope="col">Q</th>
                <th scope="col">Ranking</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($Alternatif as $itemalternatif => $value) : ?>
                <tr class="">
                  <td class=""><?= $value['id'] ?></td>
                  <td class=""><?= $value['kode'] ?></td>
                  <td class=""><?= $value['nama_alternatif'] ?></td>
                    <?php
                    $nMatriksTerbobot = [];
                    ?>
                    <?php foreach ($kriteria as $item => $valuekriteria) : ?>
                        <?php
                        $sql_spk = "SELECT * FROM spk JOIN spk_vikor.sub_kriteria sk on spk.id_sub_kriteria = sk.id JOIN spk_vikor.kriteria k on spk.id_kriteria = k.id WHERE id_alternatif = :id_alternatif AND spk.id_kriteria = :id_kriteria";
                        $stmt_spk = $conn->prepare($sql_spk);
                        $stmt_spk->bindParam(':id_alternatif', $value['id']);
                        $stmt_spk->bindParam(':id_kriteria', $valuekriteria['id']);
                        $stmt_spk->execute();
                        $spk = $stmt_spk->fetch(PDO::FETCH_ASSOC);
                        if (isset($spk['id_sub_kriteria'])) {
                            $nMatriksTerbobot[] = round(($spk['skala_nilai'] - min($nilaiKriteria[$valuekriteria['nama_kriteria']])) / (max($nilaiKriteria[$valuekriteria['nama_kriteria']]) - min($nilaiKriteria[$valuekriteria['nama_kriteria']])) * ($valuekriteria['bobot'] / 100),3);

                            $V_score = 0.5 * (max($nilaiKriteria[$valuekriteria['nama_kriteria']]) - min($nilaiKriteria[$valuekriteria['nama_kriteria']]));
                        }
                        ?>
                    <?php endforeach; ?>
                  <td class=""><?= isset($spk['id_sub_kriteria']) ? (0.5 * (max($nMatriksTerbobot) - min($nMatriksTerbobot))) + ( 0.5 * (array_sum($nMatriksTerbobot) / count($nMatriksTerbobot))) : 0 ?></td>
                  <td class=""><?= ++$itemalternatif ?></td>

                    <?php
                    ?>
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
