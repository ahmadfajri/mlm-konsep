<?php

// Koneksi ke Database
$koneksi = mysqli_connect("localhost","root","","db_mlm");
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}


/**
 * display_children($parent, $level, $per_komisi=10000)
 * parameter :
 * $parent = id member yang mau dicari berapa komisinya
 * $level = pengurutan
 * $per_komisi = besar komisinya
 * hasil return : integer (komisi);
 * cara pakai : display_children(2, 0, 10000) mencari komisi member yg id nya 2, 2 disini bisa disesuaikan secara dinamis
 */
function display_children($parent, $level, $per_komisi=10000) { 
	global $koneksi;
	$komisi = 0;
	$result = mysqli_query($koneksi, "SELECT * FROM tbl_referal WHERE id_referal='$parent'");
	foreach ($result as $key => $row) {
		$komisi += $per_komisi;
		$hasil[$level]['child'] = [];
		$hasil[$level]['child'][] = mysqli_query($koneksi, "SELECT * FROM tbl_referal WHERE id_referal='$row[id_member]'");
		$komisi += display_children($row['id_member'], $level+1);
	}
	return $komisi;
}

?>

<h3>Tabel Member</h3>
<table border="1" cellspacing="0" cellpadding="8">
	<tr>
		<td>ID</td>
		<td>Nama</td>
		<td>Bonus</td>
	</tr>
	<?php
		$member = mysqli_query($koneksi, "SELECT * FROM tbl_member");
		foreach($member as $datamember):

	?>
	<tr>
		<td><?= $datamember['id'] ?></td>
		<td><?= $datamember['nama'] ?></td>
		<td><?= display_children($datamember['id'],1); ?></td>
	</tr>
	<?php 
		endforeach;
	?>
</table>

<br><br>

<h3>Tabel MLM</h3>
<table border="1" cellspacing="0" cellpadding="8">
	<tr>
		<td>ID Referal</td>
		<td>ID Member</td>
	</tr>
	<?php
	    $referal = mysqli_query($koneksi, "SELECT * FROM tbl_referal");
		foreach($referal as $datareferal):
	?>
	<tr>
		<td><?= $datareferal['id_referal'] ?></td>
		<td><?= $datareferal['id_member'] ?></td>
	</tr>
	<?php 
		endforeach;
	?>
</table>