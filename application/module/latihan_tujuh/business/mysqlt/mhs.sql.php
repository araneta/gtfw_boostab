<?php

$sql['get_list_mahasiswa'] = "
SELECT
	mhsNama AS NAMA_MHS
FROM pub_ref_mhs
WHERE mhsNama like '%s'
ORDER BY NAMA_MHS ASC
";

?>