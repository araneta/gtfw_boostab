<?php
	$sql['get_all_notify'] = "
		SELECT
			`notifyId`,
			`notifyNama`,
			`notifyKeterangan`,
			`notifyKategoryKode`,
			`notifyStatusKode` AS `status`
		FROM `plo_notify`
		LEFT JOIN plo_ref_notify_kategory ON notifyKategoryId = notifyNotifyKategoriId
		LEFT JOIN plo_ref_notify_status ON notifyNotifyStatusId = notifyStatusId
		WHERE notifyUserTujuanId = '%s'
	";
	
	$sql['set_load_all'] ="
		UPDATE 
			`plo_notify`
		SET 
		   `notifyLoadStatus` = 'load'
	";
	
	$sql['get_unload_notify'] = "
		SELECT
			`notifyId`,
			`notifyNama`,
			`notifyKeterangan`,
			`notifyKategoryKode`,
			`notifyStatusKode` AS `status`
		FROM `plo_notify`
		LEFT JOIN plo_ref_notify_kategory ON notifyKategoryId = notifyNotifyKategoriId
		LEFT JOIN plo_ref_notify_status ON notifyNotifyStatusId = notifyStatusId
		WHERE notifyUserTujuanId = '%s'
		AND notifyLoadStatus = 'unload'
	";
	
	$sql['get_module_from_notify'] = "
		SELECT 
		   module,
		   submodule,
		   `action`,
		   `type` ,
		   notifyUrl
		FROM
		   `plo_notify` 
		   LEFT JOIN gtfw_module 
		      ON moduleId = notifyModuleId 
		WHERE notifyId = '%s' 
		   AND notifyModuleId IS NOT NULL 
	";
	
	$sql['get_module_id'] ="
		SELECT
			ModuleId
		FROM gtfw_module
		WHERE Module = '%s' AND SubModule = '%s'
	";
	
	$sql['set_read_notify']="
		UPDATE `plo_notify`
		SET 
		   `notifyNotifyStatusId` = '2'
		WHERE `notifyId` = '%s'
	";
	
	$sql['add_notify']="
	INSERT INTO plo_notify (`notifyNama`,
             `notifyKeterangan`,
             `notifyModuleId`,
             `notifyUrl`,
             `notifyNotifyStatusId`,
             `notifyNotifyKategoriId`,
             `notifyUserTujuanId`,
             `notifyUserId`)
	VALUES %s
	";
	
	$sql['delete_notify_by_nomor_surat']="
		DELETE FROM plo_notify
		WHERE notifyNama LIKE '%s'
	";
?>
