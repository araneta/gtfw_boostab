<?php

$sql['count']     = "
SELECT FOUND_ROWS() AS `count`
";

$sql['get_data_kelompok']  = "
SELECT SQL_CALC_FOUND_ROWS
   kelompok.id,
   kelompok.nama,
   IFNULL(peserta.count, 0) AS child
FROM
   kelompok
   LEFT JOIN
      (SELECT
         kelompokId AS parentId,
         COUNT(pesertaId) AS `count`
      FROM
         peserta
      GROUP BY kelompokId) AS peserta
      ON  peserta.parentId = kelompok.id
 WHERE 1 = 1
 AND kelompok.nama LIKE '%s'
 ORDER BY kelompok.nama
 LIMIT %s, %s
";

$sql['do_check_data']   = "
SELECT
   COUNT(id) AS `count`
FROM kelompok
WHERE 1 = 1
AND (id != '%s' OR 1 = %s)
AND nama = '%s'
";

$sql['do_insert_data']  = "
INSERT INTO kelompok
SET nama = '%s'
";

$sql['get_data_detil_kelompok']  = "
SELECT
   kelompok.id,
   kelompok.nama,
   IFNULL(peserta.count, 0) AS child
FROM
   kelompok
   LEFT JOIN
      (SELECT
         kelompokId AS parentId,
         COUNT(pesertaId) AS `count`
      FROM
         peserta
      GROUP BY kelompokId) AS peserta
      ON  peserta.parentId = kelompok.id
 WHERE 1 = 1
 AND kelompok.id = %s
 LIMIT 1
";

$sql['do_update_data']  = "
UPDATE kelompok
SET nama = '%s'
WHERE id = %s
";


$sql['do_delete_data']  = "
DELETE
FROM kelompok
WHERE id = '%s'
";

?>