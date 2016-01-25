<?php

$sql['get_list_mahasiswa'] = "
SELECT
	mhsId AS ID_MHS,
	mhsNama AS NAMA_MHS,
	mhsAlamat AS ALAMAT_MHS
FROM pub_ref_mhs
ORDER BY NAMA_MHS ASC
LIMIT %s, %s
";

$sql['get_count_mahasiswa'] = "
SELECT
	COUNT(mhsId) AS total
FROM pub_ref_mhs
";

?>