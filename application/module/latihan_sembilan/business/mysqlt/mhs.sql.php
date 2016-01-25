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

$sql['get_mahasiswa_by_id']= "
SELECT
	mhsId AS ID_MHS,
	mhsNama AS NAMA_MHS,
	mhsAlamat AS ALAMAT_MHS
FROM pub_ref_mhs
WHERE mhsId = '%s'
";

$sql['do_update_mahasiswa']="
UPDATE pub_ref_mhs
SET
	mhsNama = '%s',
	mhsAlamat = '%s'
WHERE mhsId = '%s';
";

?>