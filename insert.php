<?php
$servername = "localhost";
$username = "root"; // default XAMPP
$password = "";
$dbname = "iotdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Proteksi: Memastikan ketiga data jarak dikirim lengkap oleh ESP32
    if (isset($_POST['jarak1']) && isset($_POST['jarak2']) && isset($_POST['jarak3'])) {
        
        $jarak1 = $_POST['jarak1'];
        $jarak2 = $_POST['jarak2'];
        $jarak3 = $_POST['jarak3'];

        // Menyimpan ke tabel sensor_tiga_jarak
        $sql = "INSERT INTO sensor_tiga_jarak (jarak1, jarak2, jarak3) VALUES ('$jarak1', '$jarak2', '$jarak3')";

        if ($conn->query($sql) === TRUE) {
            echo "Data 3 sensor berhasil disimpan";
        } else {
            echo "Error Database: " . $conn->error;
        }
        
    } else {
        // Mengembalikan pesan error jika data POST yang diterima tidak lengkap
        echo "Error: Parameter data jarak tidak lengkap!";
    }
}

$conn->close();
?>