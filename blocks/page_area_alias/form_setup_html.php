<?php  defined('C5_EXECUTE') or die("Access Denied."); ?> 
<?php

	$bt = $this->blockObj;

	$pagesel = Loader::helper('form/page_selector');
	$hlpPageArea = Loader::helper('page_area_alias', 'page_area_alias');
	
	$cMode = $this->controller->getMode();
	
	
	//$btl = $a->getAddBlockTypes($c, $ap);
	//$blockTypes = $btl->getBlockTypeList();
	$hlpUrl = Loader::helper('concrete/urls');
	$ch = Loader::helper('concrete/interface');
	$form = Loader::helper('form');
	
	Loader::model('collection_types');
	$cTypes = CollectionType::getList();
	$ctOpts = array();
	foreach($cTypes as $cType){
		$ctOpts[$cType->getCollectionTypeHandle()] = $cType->getCollectionTypeName();
	}
	
	$db = Loader::db();
	$query = $db->query('select * from BlockTypes order by btName');
	$blockTypes = BlockTypeList::getInstalledList();
	
	//->getBlockAreaObject();
?>

<input type="hidden" name="pageAreaAliasToolsDir" value="<?php echo $hlpUrl->getBlockTypeToolsURL($bt)?>/" />

<div class="choose-mode">
	<h2><?php echo t('Mode') ?></h2>
    <div>
        <label><?php echo $form->radio('mode', 'inherit', $cMode=='inherit') ?> <?php echo t('Parent Page') ?></label>
        <label><?php echo $form->radio('mode', 'page', $cMode=='page') ?> <?php echo t('Specific Page') ?></label>
        <label><?php echo $form->radio('mode', 'page_type', $cMode=='page_type') ?> <?php echo t('Page Type') ?></label>
    </div>
</div>

<div class="choose-collection">
	<h2><?php echo t('Choose a page') ?></h2>
    <div>
		<?php echo $pagesel->selectPage('acID', $this->controller->getAliasCollectionID(), 'ccm_PageAreaAliasForm.selectSitemapNode') ?>
    </div>
</div>

<div class="choose-page-type">
	<h2><?php echo t('Choose a page type') ?></h2>
    <div>
		<?php echo $form->select('actHandle', $ctOpts, $actHandle) ?>
        <?php foreach($cTypes as $cType){
			$ctHandle = $cType->getCollectionTypeHandle();
			$ctMasterID = $cType->getMasterCollectionID();
			echo "<input type='hidden' name='actmID_$ctHandle' value='$ctMasterID' />";
		}?>
      
    </div>
</div>

<div class="choose-area">
    <h2><?php echo t('Area to alias') ?></h2>
    <div>
	    <small><?php echo t('When set to "Auto", the block will use the same area name where it is placed.') ?></small><br/>
        <select name="aarHandle" id="aarHandle">
            <option value="">Auto</option>
        </select>        
    </div>
    
</div>

<div class="choose-block-types">
	<h2><?php echo t('Block types') ?></h2>
    <div>
        <p><?php echo t('%s the following block types:', $form->select('btRefMode', array('exclude'=>'Exclude', 'include'=>'Include'), $btRefMode)) ?></p>
        <ul class="btlist">
        <?php 
		$btRefHandlesArray = $this->controller->getBlockTypeRefHandles();
		
		foreach($blockTypes as $blockType){
			$btHandle = $blockType->getBlockTypeHandle();
			$btName = $blockType->getBlockTypeName();
			$btDesc = $blockType->getBlockTypeDescription();
			$checked = is_array($btRefHandlesArray) && in_array($btHandle, $btRefHandlesArray) ? 'checked' : '';
        	echo "<li><label title=\"$btDesc\"><input type=\"checkbox\" name=\"btRefHandles[]\" value=\"$btHandle\" $checked /> $btName</label></li>";
		} 
		?>
        </ul>
    </div>
</div>

<style type="text/css">
div.choose-mode,
div.choose-collection,
div.choose-page-type,
div.choose-area {margin-bottom:15px;}
</style>