<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iotdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data dari tabel 'sensor_tiga_jarak'
$sql = "SELECT * FROM sensor_tiga_jarak ORDER BY id DESC LIMIT 20";
$result = $conn->query($sql);

// Fungsi pembantu untuk menentukan warna dan status berdasarkan aturan jarak
function cekStatus($jarak) {
    if ($jarak <= 0) {
        return ["status" => "Error / N/A", "warna" => "#757575"]; // Abu-abu jika sensor bermasalah
    } elseif ($jarak > 0 && $jarak <= 15) {
        return ["status" => "Sangat Bahaya", "warna" => "#d32f2f"]; // Merah
    } elseif ($jarak > 15 && $jarak <= 30) {
        return ["status" => "Peringatan", "warna" => "#f57c00"]; // Orange
    } else {
        return ["status" => "Aman", "warna" => "#388e3c"]; // Hijau
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data 3 Sensor Jarak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #0288D1;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        /* Style untuk badge status */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            color: white;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <h2>Monitoring Data 3 Sensor HC-SR04</h2>
    <p>Halaman ini menampilkan data jarak real-time beserta indikator tingkat bahaya.</p>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Sensor 1</th>
            <th>Sensor 2</th>
            <th>Sensor 3</th>
            <th>Waktu</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Ambil status untuk masing-masing sensor
                $s1 = cekStatus($row["jarak1"]);
                $s2 = cekStatus($row["jarak2"]);
                $s3 = cekStatus($row["jarak3"]);

                echo "<tr>
                        <td>".$row["id"]."</td>
                        
                        <td>"
                            .($row["jarak1"] <= 0 ? "N/A" : $row["jarak1"]." cm").
                            "<br><span class='badge' style='background-color:".$s1['warna']."'>".$s1['status']."</span>
                        </td>
                        
                        <td>"
                            .($row["jarak2"] <= 0 ? "N/A" : $row["jarak2"]." cm").
                            "<br><span class='badge' style='background-color:".$s2['warna']."'>".$s2['status']."</span>
                        </td>
                        
                        <td>"
                            .($row["jarak3"] <= 0 ? "N/A" : $row["jarak3"]." cm").
                            "<br><span class='badge' style='background-color:".$s3['warna']."'>".$s3['status']."</span>
                        </td>
                        
                        <td>".$row["waktu"]."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
        }
        ?>
    </table>

</body>
</html>

<?php $conn->close(); ?>