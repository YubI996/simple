<?php
// INCLUDE KONEKSI KE DATABASE
include_once("config.php");

// AMBIL DATA DARI DATABASE BERDASARKAN DATA TERAKHIR DI INPUT
$result = mysqli_query($mysqli, "SELECT * FROM z_score ORDER BY id DESC");
?>

<html>

<head>
	<title>Homepage</title>
</head>

<body>
	<center>
		<a href="add.html">Cek Status Gizi</a><br /><br />

		<table width='80%' border=0>

			<tr bgcolor='#CCCAAC'>
				<td>-3SD</td>
				<td>-2SD</td>
				<td>2SD</td>
				<td>3SD</td>
				<td>Actions</td>
			</tr>
			<?php

			while ($res = mysqli_fetch_array($result)) {
				echo "<tr>";
				echo "<td>" . $res['m3sd'] . "</td>";
				echo "<td>" . $res['m2sd'] . "</td>";
				echo "<td>" . $res['2sd'] . "</td>";
				echo "<td>" . $res['3sd'] . "</td>";
				echo "<td><a href=\"edit.php?id=$res[id]\">Edit</a> | <a href=\"delete.php?id=$res[id]\" onClick=\"return confirm('Kamu yakin untuk delete ini?')\">Delete</a></td>";
			}
			?>
		</table>
	</center>
</body>

</html>
