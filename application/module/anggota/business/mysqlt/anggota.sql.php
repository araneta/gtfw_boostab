<?php 

$sql['get_kelompok']    = "
SELECT
   id,
   nama AS `name`
FROM kelompok
ORDER BY nama
";

$sql['count']           = "
SELECT FOUND_ROWS() AS `count`
";

$sql['get_data_anggota']   = "
SELECT SQL_CALC_FOUND_ROWS
   peserta.pesertaId AS id,
   peserta.pesertaNama AS nama,
   peserta.pesertaEmail AS email,
   peserta.pesertaPhone AS phone,
   peserta.pesertaAddress AS address,
   kelompok.id AS kelompok_id,
   kelompok.nama AS kelompok_nama
FROM
   peserta
   LEFT JOIN kelompok
      ON kelompok.id = peserta.kelompokId
WHERE 1 = 1
AND peserta.pesertaNama LIKE '%s'
AND (kelompok.id = '%s' OR 1 = %s)
ORDER BY kelompok.nama, peserta.pesertaNama
LIMIT %s, %s
";

$sql['do_insert_data_anggota']      = "
INSERT INTO peserta
SET pesertaNama = '%s',
   pesertaEmail = '%s',
   pesertaPhone = '%s',
   pesertaAddress = '%s',
   kelompokId = '%s'
";

$sql['get_detail_anggota']    = "
SELECT SQL_CALC_FOUND_ROWS
   peserta.pesertaId AS id,
   peserta.pesertaNama AS nama,
   peserta.pesertaEmail AS email,
   peserta.pesertaPhone AS phone,
   peserta.pesertaAddress AS address,
   kelompok.id AS kelompok_id,
   kelompok.nama AS kelompok_nama
FROM
   peserta
   LEFT JOIN kelompok
      ON kelompok.id = peserta.kelompokId
WHERE 1 = 1
AND peserta.pesertaId = %s
ORDER BY kelompok.nama, peserta.pesertaNama
LIMIT 1
";

$sql['do_update_data_anggota']      = "
UPDATE peserta
SET pesertaNama = '%s',
   pesertaEmail = '%s',
   pesertaPhone = '%s',
   pesertaAddress = '%s',
   kelompokId = '%s'
WHERE pesertaId = %s
";

$sql['do_delete_data_anggota']      = "
DELETE
FROM peserta
WHERE pesertaId = '%s'
";


 ?>