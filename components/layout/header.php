<?php
if (!isset($_COOKIE['username'])) {
    // Jika cookie tidak ada, redirect ke halaman login
    header("Location: ../../login.php");
    exit;
}
?>
<div class="sidebar border" style="background-color:white; margin: 10px; border-radius: 10px;">
  <div class="grid-cols-2 profile-bar">
    <img class="profile"/>
    <div>
      <label>Admin</label>
      <span class="badge">Online</span>
    </div>
  </div>
  <ul class="h-100 header">
    <li><a href="/">Beranda</a></li>
    <li><a href="../../alternatif/alternatif.php">Alternatif</a></li>
    <li><a href="../../kriteria/kriteria.php">Kriteria</a></li>
    <li><a href="../../sub_kriteria/sub_kriteria.php">Sub Kriteria</a></li>
    <li><a href="../../input_nilai/input_nilai.php">Input Nilai</a></li>
    <li><a href="../../perangkingan/perangkingan.php">Perangkingan</a></li>
    <li class="bg-danger"><a href="../../login.php" style="color: white">Logout</a></li>
  </ul>
</div>