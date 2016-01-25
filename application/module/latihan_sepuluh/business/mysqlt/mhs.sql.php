<?php
$sql['get_data'] = "
SELECT SQL_CALC_FOUND_ROWS
    m.mhsId AS `ID_MHS`,
    #m.mhsParentId AS PARENT_ID_MHS,
    #p.mhsNama AS PARENT,
    m.mhsNama AS `NAMA_MHS`,
	mhsAlamat AS ALAMAT_MHS
FROM
    pub_ref_mhs m
#LEFT JOIN pub_ref_mhs p ON p.mhsId = m.mhsParentId
	--filter--
--limit--
";

$sql['count_data'] = "
SELECT FOUND_ROWS() AS total
";

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

$sql['do_delete_mahasiswa']="
DELETE from pub_ref_mhs
WHERE mhsId='%s';
";

?>