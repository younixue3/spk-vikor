<?php

require_once('../connection.php');

$alternatif_id = isset($_GET["alternatif_id"]) ? $_GET["alternatif_id"] : null;

$sql_alternatif = "SELECT * FROM alternatif";
$stmt_alternatif = $conn->prepare($sql_alternatif);
$stmt_alternatif->execute();
$alternatif = $stmt_alternatif->fetchAll(PDO::FETCH_ASSOC);

//Detail Alternatif Karyawan
if ($alternatif_id) {
    $sql_alternatif_choice = "SELECT * FROM alternatif WHERE id = :alternatif_id ";
    $stmt_alternatif_choice = $conn->prepare($sql_alternatif_choice);
    $stmt_alternatif_choice->bindParam(":alternatif_id", $alternatif_id);
    $stmt_alternatif_choice->execute();
    $alternatif_choice = $stmt_alternatif_choice->fetch(PDO::FETCH_ASSOC);
}

//Penilaian
if ($alternatif_id) {
    $sql_spk = "SELECT spk.id as id_spk, spk.id_sub_kriteria as id_sub_kriteria, kriteria.id as id_kriteria, kriteria.nama_kriteria as nama_kriteria, id_sub_kriteria FROM spk JOIN kriteria ON spk.id_kriteria = kriteria.id WHERE id_alternatif = :alternatif_id";
    $stmt_spk = $conn->prepare($sql_spk);
    $stmt_spk->bindParam(":alternatif_id", $alternatif_id);
    $stmt_spk->execute();
    $spk = $stmt_spk->fetchAll(PDO::FETCH_ASSOC);
}

//
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $penilaian = $_POST["penilaian"];
    $spk_id = $_POST["spk_id"] || null;
    $sub_kriteria = $_POST["id_sub_kriteria"] || null;
    if ($penilaian) {
//      Delete SPK Kriteria before
        $sql_delete_spk = "DELETE FROM spk WHERE id_alternatif=:alternatif_id;";
        $stmt_delete_spk = $conn->prepare($sql_delete_spk);
        $stmt_delete_spk->bindParam(":alternatif_id", $alternatif_id);
        $stmt_delete_spk->execute();

//      SPK Kriteria
        $sql_kriteria = "SELECT * FROM kriteria";
        $stmt_kriteria = $conn->prepare($sql_kriteria);
        $stmt_kriteria->execute();
        $kriteria = $stmt_kriteria->fetchAll(PDO::FETCH_ASSOC);

        foreach ($kriteria as $item => $value) {
          $sql_create_spk = "INSERT INTO spk (id_kriteria, id_alternatif) VALUES(:id_kriteria, :id_alternatif)";
          $stmt_create_spk = $conn->prepare($sql_create_spk);
          $stmt_create_spk->bindParam(":id_kriteria", $value['id']);
          $stmt_create_spk->bindParam(":id_alternatif", $alternatif_id);
          $stmt_create_spk->execute();
        }
        header("Location: input_nilai.php?alternatif_id=".$alternatif_id);
        exit();
    }

//    $sql = "INSERT INTO kriteria (nama_kriteria, bobot) VALUES (:nama_kriteria, :bobot)";
//    $stmt = $conn->prepare($sql);
//    $stmt->bindParam(":nama_kriteria", $nama_kriteria);
//    $stmt->bindParam(":bobot", $bobot);
//
//    if ($stmt->execute()) {
//        header("Location: kriteria.php". $_SERVER['alternatif_id']);
//        exit();
//    } else {
//        echo "Error: " . $conn->errorInfo()[0];
//    }
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
    <?php include '../components/layout/banner.php' ?>
  <main>
      <?php include '../components/layout/header.php' ?>
    <div class="content">
      <section class="grid-cols-10 gap-1">
        <h1 class="col-10">Penilaian</h1>
        <form class="col-3 grid-cols-3">
          <div action="" method="get" class="grid-cols col-2">
            <label class="input-label" for="nama_kriteria"><b>Alternatif</b></label>
            <select class="input-control" placeholder="Pilih alternatif" id="alternatif"
                    name="alternatif_id">
              <option value="">--- Pilih Alternatif ---</option>
                <?php foreach ($alternatif as $item => $value) : ?>
                  <option value="<?= $value['id'] ?>"><?= $value['nama_alternatif'] ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary" style="height: 40px; margin: auto">Simpan</button>
        </form>
        <?php if ($alternatif_id) { ?>
          <div class="card col-10">
            <section class="card-body">
              <table class="">
                <thead>
                <tr class="">
                  <th scope="col" style="width: 20px;">No</th>
                  <th scope="col">Nama Karyawan</th>
                  <th scope="col" style="width: 300px;">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr class="">
                  <td class="">1</td>
                  <td class=""><?= $alternatif_choice['nama_alternatif'] ?></td>
                  <td class="">
                    <form action="" method="post">
                      <input type="hidden" value="true" name="penilaian">
                      <button class="btn btn-primary">
                        Penilaian
                      </button>
                    </form>
                  </td>
                </tr>
                </tbody>
              </table>
            </section>
          </div>
          <div class="card col-10">
            <section class="card-body">
              <table class="">
                <thead>
                <tr class="">
                  <th scope="col">Kriteria</th>
                  <th scope="col">Sub Kriteriia</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($spk as $item => $valuespk) : ?>
                  <tr class="">
                    <td class=""><?= $valuespk['nama_kriteria'] ?></td>
                    <td class="">
                      <form action="edit.php" method="post">
                        <input type="hidden" name="id" value="<?= $valuespk['id_spk']?>">
                        <select class="input-control" placeholder="Pilih Sub Kriteria" id="id_sub_kriteria"
                                name="id_sub_kriteria">
                          <option disabled selected value="">--- Pilih Sub Kriteria ---</option>
                            <?php
                            $sql_sub_kriteria_choice = "SELECT * FROM sub_kriteria WHERE id_kriteria = :id_kriteria";
                            $stmt_sub_kriteria_choice = $conn->prepare($sql_sub_kriteria_choice);
                            $stmt_sub_kriteria_choice->bindParam(":id_kriteria", $valuespk['id_kriteria']);
                            $stmt_sub_kriteria_choice->execute();
                            $sub_kriteria_choice = $stmt_sub_kriteria_choice->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($sub_kriteria_choice as $item => $valuekriteriachoice) {
                                $selected = $valuekriteriachoice['id'] == $valuespk['id_sub_kriteria'] ? 'selected="selected"' : '';
                                echo "<option value='".$valuekriteriachoice['id'] . "'" . $selected . ">".$valuekriteriachoice['nama_sub_kriteria']."</option>";
                            }

                            ?>
                        </select>
                        <button type="submit" class="btn btn-primary" style="height: 40px; margin: auto">Simpan</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </section>
          </div>
        <?php } ?>
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