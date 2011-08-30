<?php
	defined('C5_EXECUTE') or die("Access Denied.");
	/*
	$dh = Loader::helper('concrete/dashboard/sitemap');
	if ($dh->canRead()) {
		die(t('Access Denied'));	
	}*/
	
	
	$hlpJson = Loader::helper('json');
	
	$cID = isset($_REQUEST['cID']) ? $_REQUEST['cID'] : NULL;
	
	
	$json['error'] = false;
	$json['messages'] = array();
	
	if(empty($cID)){
		$json['error'] = true;
		$json['messages'][] = t('No collection ID was provided.');			
	}	
	//If there are errors, send them now
	if($json['error']){
		echo $hlpJson->encode($json);
		exit();
	}
	
	//Otherwise, provide the options
	$hlpPageAreas = Loader::helper('page_area_alias','page_area_alias');
	$arHandles = $hlpPageAreas->getAreaHandlesByCollectionID($cID);	
	
	$json['arHandles'] = $arHandles;
	//page->acquireAreaPermissions($permsCollectionID)

	echo $hlpJson->encode($json);

?>