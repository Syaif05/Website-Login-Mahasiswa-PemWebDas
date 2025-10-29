<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $nim = $_POST["nim"];
    $tanggal = $_POST["tanggal"];
    $jurusan = $_POST["jurusan"];

    echo "Data berhasil di simpan <br>";
    echo"Atas Nama $nama <br>";
    echo"NIM $nim <br>";
    echo"Pada Tanggal $tanggal <br>";
    echo"Asal Jurusan $jurusan <br> <hr>";


    echo "<a href='form.html'> Kembali ke Form </a>";
} else {
    echo "Akses Ilegal";}
?>