<?php

$sql['get_list_mahasiswa'] = "
SELECT
	mhsId AS ID_MHS,
	mhsNama AS NAMA_MHS,
	mhsAlamat AS ALAMAT_MHS
FROM pub_ref_mhs
ORDER BY NAMA_MHS ASC
";

$sql['do_add_mahasiswa']="
INSERT INTO pub_ref_mhs (mhsNama,mhsAlamat)
VALUES ('%s','%s');
";

?>