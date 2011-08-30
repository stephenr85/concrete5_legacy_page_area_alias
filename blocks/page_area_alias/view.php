<?php
	$isEditMode = Page::getCurrentPage()->isEditMode();
	//print_r($this->blockObj);	
	
	if(empty($aarHandle)){
		$aarHandle = $this->blockObj->arHandle;
		$this->controller->getBlockObject()->arHandle = $aarHandle;
	}
	
	$aliasCollection = $this->controller->getAliasCollection();
	
	if(!is_object($aliasCollection)){
		if($isEditMode)
			echo '<p>'.t('The referenced page could not be found (Page Area Alias).').'</p>';
		
		return;	
	}else{
		$aliasArea = Area::get($aliasCollection, $aarHandle);	
	}
	
	
	
	if(!is_object($aliasArea)){
		if($isEditMode)
			echo "<p>The \"$aarHandle\" area does not exist on the referenced page (Page Area Alias).</p>";
		return;
	}
	
	
	$aliasBlocks = $this->controller->getAliasBlocks();
	
	foreach($aliasBlocks as $aliasBlock){
		$p = new Permissions($aliasBlock);
		if($p->canRead()){
			if(strlen($view = $aliasBlock->getBlockfilename()) > 0){
				$aliasBlock->display($view);
			}else{
				$aliasBlock->display();	
			}		
		}
	}
	
	
?>
