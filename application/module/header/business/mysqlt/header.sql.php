<?php

   $sql['GetPeriodeAktif'] = "
      SELECT
         periodedtId,
         CONCAT(periodeTahun,'/',periodeTahun+1) AS TAHUN_AJARAN,
         TRIM(CONCAT(periodetipeNama,' ',periodejnsNama)) AS SEMESTER
      FROM pub_periode_det
      INNER JOIN pub_periode ON periodedtPeriodeId = periodeId
      INNER JOIN pub_ref_periode_tipe ON periodetipeId = periodedtPeriodetipeId
      INNER JOIN pub_ref_periode_jenis ON periodejnsId = periodePeriodejnsId
      INNER JOIN akd_periode ON prdPeriodedtId = periodedtId
      WHERE
         prdIsAktif = 1
   ";

?>
