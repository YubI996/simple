<html>

<head>
	<title>Tambah Data</title>
</head>

<body>
	<?php
	// INCLUDE KONEKSI KE DATABASE
	include_once("config.php");

	if (isset($_POST['Submit'])) {
		$lahir = mysqli_real_escape_string($mysqli, $_POST['lahir']);
		$berat = mysqli_real_escape_string($mysqli, $_POST['berat']);
		$posisi = mysqli_real_escape_string($mysqli, $_POST['posisi']);
		$tinggi = mysqli_real_escape_string($mysqli, $_POST['tinggi']);
		$jk = mysqli_real_escape_string($mysqli, $_POST['jk']);
		
		// CEK DATA TIDAK BOLEH KOSONG
		if (empty($lahir) || empty($berat) || empty($posisi) || empty($tinggi) || empty($jk)) {

			if (empty($lahir)) {
				echo "<font color='red'>Kolom lahir tidak boleh kosong.</font><br/>";
			}

			if (empty($berat)) {
				echo "<font color='red'>Kolom berat tidak boleh kosong.</font><br/>";
			}

			if (empty($posisi)) {
				echo "<font color='red'>Kolom po$posisi tidak boleh kosong.</font><br/>";
			}

			if (empty($tinggi)) {
				echo "<font color='red'>Kolom Gambar tidak boleh kosong.</font><br/>";
			}

			// KEMBALI KE HALAMAN SEBELUMNYA
			echo "<br/><a href='javascript:self.history.back();'>Kembali</a>";
		} else {
			$lahir= strtotime($lahir);
			$now = strtotime(date('Y-m-d H:i:s'));
			$y1 = date('Y', $lahir);
			$y2 = date('Y', $now);
			$m1 = date('m', $lahir);
			$m2 = date('m', $now);
			$umur = (($y2-$y1)*12)+($m2-$m1);
			if ($umur < 24 && $posisi == "H") {
				$tinggi += 0.7;
			}
			elseif ($umur >= 24 && $posisi == "L") {
				$tinggi -= 0.7;
			}
			$tinggi = round($tinggi);
			$bmi = round(10000*$berat/pow($tinggi,2), 2);
			$var = $umur<=24?1:2;
			
			// $z_score = mysqli_query($mysqli, "SELECT id,m3sd AS A1,m2sd AS B1,2sd AS C1 FROM z_score WHERE var = $var AND acuan = $umur AND jk = $jk AND jenis_tbl = 1");
			// $z_score = mysqli_fetch_fields($z_score);
			// $z_score = mysqli_fetch_all($z_score,MYSQLI_ASSOC);
			$hasil = cek_status($umur,$tinggi, $berat, $bmi, $jk, $var,$mysqli);
			echo'<pre>';
			print_r($hasil);
			echo '</pre>';
			

			// if ($bmi < $bmi_u['m3sd']) {
			// 	$status = "Sangat Kurus";
			// }
			// elseif ($bmi >= $bmi_u['m3sd'] && $bmi < $bmi_u['m2sd']) {
			// 	$status = "Kurus";
			// }
			// elseif ($bmi >= $bmi_u['m2sd'] && $bmi <= $bmi_u['2sd']) {
			// 	$status = "Normal";
			// }
			// else {
			// 	$status = "Gemuk";
			// }
			print("Umur :".$umur." Bulan<br>");
			print("Berat Badan :".$berat." Kg\n<br>");
			print("Tinggi :".$tinggi." Cm\n<br>");
			$jenkel = $jk==1?"Laki-laki":"Perempuan";
			print("Jenis Kelamin :".$jenkel."<br>");
			print("BMI :".$bmi."<br>");
			print("var :".$var."<br>");
			// print("Status Gizi :".$status."<br><br>");
			// MENAMPILKAN PESAN BERHASIL
			echo "<br/><a href='index.php'>Index</a>";
		}
	}
	function cek_status($umur,$tinggi,$berat,$bmi,$jk,$var,$link)
	{

		$query1 = mysqli_query($link, "SELECT id,m3sd AS a1,m2sd AS b1,2sd AS c1 FROM z_score WHERE var = $var AND acuan = $umur AND jk = $jk AND jenis_tbl = 1");//ambil IMT/U
		$query2 = mysqli_query($link, "SELECT id,m3sd AS a2,m2sd AS b2,2sd AS c2 FROM z_score WHERE acuan = $umur AND jk = $jk AND jenis_tbl = 2");//ambil BB/U
		$query3 = mysqli_query($link, "SELECT id,m3sd AS a3,m2sd AS b3,2sd AS c3 FROM z_score WHERE var = $var AND acuan = $umur AND jk = $jk AND jenis_tbl = 3");//ambil TB/U
		$query4 = mysqli_query($link, "SELECT id,m3sd AS a4,m2sd AS b4,2sd AS c4 FROM z_score WHERE var = $var AND acuan = $tinggi AND jk = $jk AND jenis_tbl = 4");//ambil BB/TB

		$imt = mysqli_fetch_all($query1, MYSQLI_ASSOC);
		$imt = $imt[0];
		$bb = mysqli_fetch_all($query2, MYSQLI_ASSOC);
		$bb = $bb[0];
		$tb = mysqli_fetch_all($query3, MYSQLI_ASSOC);
		$tb = $tb[0];
		$bt = mysqli_fetch_all($query4, MYSQLI_ASSOC);
		$bt = $bt[0];
		// echo"<pre>";
		// print_r($imt);
		// echo"</pre>";
		if ($bmi < $imt['a1']) {
			$s1= "Sangat Kurus";
			}
			elseif ($bmi>=$imt['a1'] && $bmi < $imt['b1']) {
				$s1 = "Kurus";
			}
			elseif ($bmi >= $imt['b1'] && $bmi <= $imt['c1']) {
				$s1 = "Normal";
			}
			else {
			$s1 = "Gemuk";
		}
		
		if ($berat < $bb['a2']) {
			$s2= "Gizi Buruk";
			}
			elseif ($berat >= $bb['a2'] && $berat < $bb['b2']) {
				$s2 = "Gizi Kurang";
			}
			elseif ($berat >= $bb['b2'] && $berat <= $bb['c2']) {
				$s2 = "Gizi Baik";
			}
			else {
				$s2 = "Gizi Lebih";
		}

		if ($tinggi < $tb['a3']) {
				$s3= "Sangat Pendek";
			}
			elseif ($tinggi >= $tb['a3'] && $tinggi < $tb['b3']) {
				$s3 = "Pendek";
			}
			elseif ($tinggi >= $tb['b3'] && $tinggi <= $tb['c3']) {
				$s3 = "Normal";
			}
			else {
				$s3 = "Tinggi";
		}

		if ($berat < $bt['a4']) {
				$s4= "Sangat Kurus";
			}
			elseif ($berat >=$bt['a4'] && $berat < $bt['b4']) {
				$s4 = "Kurus";
			}
			elseif ($berat >= $bt['b4'] && $berat <= $bt['c4']) {
				$s4 = "Normal";
			}
			else {
				$s4 = "Gemuk";
		}
		$hasil = array("imt"=>$s1,"bb"=>$s2,"tb"=>$s3,"bt"=>$s4);
		// $hasil = $imt;
		return $hasil;
	}
	?>
</body>

</html>
