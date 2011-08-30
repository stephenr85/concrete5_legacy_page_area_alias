<?php
	class PageAreaAliasBlockController extends BlockController {
		
		protected $btDescription = "Show the blocks of a parent or specific page in an area of your current page.";
		protected $btName = "Page Area Alias";
		protected $btTable = 'btPageAreaAlias';
		protected $btInterfaceWidth = "400";
		protected $btInterfaceHeight = "300";
		
		public function getMode(){
			if(empty($this->mode)){
				return 'inherit';
			}
			return $this->mode;
		}
		
		public function getBlockTypeRefMode(){
			if(empty($this->btRefMode)){
				return 'exclude';
			}
			return $this->btRefMode;
		}
		
		public function getBlockTypeRefHandles(){
			if(!empty($this->btRefHandles)){
				$ids = explode(',', $this->btRefHandles);
				return $ids;
			}
			return NULL;
		}
		
		public function getAliasCollectionID(){
			if($this->getMode()=='inherit'){
				$p = Page::getCurrentPage();
				$pcID = $p->getCollectionParentID();
				if(!empty($pcID)){
					return $pcID;
				}
			}else if($this->getMode() == 'page_type'){
				$ct = CollectionType::getByHandle($this->actHandle);
				return $ct->getMasterCollectionID();
			}else{
				return $this->acID;
			}
			return NULL;
		}
		
		public function getAliasCollection(){
			$acID = $this->getAliasCollectionID();
			if(!empty($acID)){
				return Page::getByID($acID);
			}
			return NULL;
		}
		
		public function getAliasAreaHandle(){
			if(empty($this->aarHandle)){
				//return handle of current area if none is set
				return $this->getBlockObject()->getAreaHandle();
			}
			return $this->aarHandle;
		}
		
		public function getAliasCollectionBlocks($areaHandle=NULL){
			
			$c = $this->getAliasCollection();
			if(is_null($areaHandle)){
				$areaHandle = $this->getAliasAreaHandle();	
			}
			
			if(is_object($c)){
				return $c->getBlocks($areaHandle);
			}
			return NULL;
		}
		
		public function filterBlocksByBlockType($types=NULL, $mode=NULL, $blocks=NULL){
			if(is_string($types)){
				$types = array($types);
			}else if(is_null($types)){
				$types = $this->getBlockTypeRefHandles();	
			}
			if(is_null($mode)){
				$mode = $this->getBlockTypeRefMode();	
			}
			if(is_null($blocks)){
				$blocks = $this->getAliasCollectionBlocks();	
			}
			$results = array();
			
			foreach($blocks as $block){
				$isFiltered = in_array($block->getBlockTypeHandle(), $types);
				if(($mode == 'exclude' && !$isFiltered) || ($mode == 'include' && $isFiltered)){					
					$results[] = $block;
				}
			}
			return $results;
		}
		
		
		function resolveInheritedAliasBlocks(&$blocks=NULL){
			if(is_null($blocks)){
				$blocks = $this->getAliasCollectionBlocks();
			}
			//Look for alias blocks that we're inheriting and get the blocks from it
			foreach($blocks as $key=>$block){
				if($this->getBlockObject()->getBlockTypeHandle() == $block->getBlockTypeHandle()){
					$inheritAreaHandle = $block->getInstance()->aarHandle;
					if(empty($inheritAreaHandle)){
						//The auto area handles are not getting loaded into the block object properly from within the controller. view->blockObj->arHandle has it, so not sure what's up with that.	
						$block->getInstance()->aarHandle = $this->getAliasAreaHandle();
					}
					$inheritBlocks = $block->getInstance()->getAliasBlocks();
					//$this->pre($block->getBlockTypeHandle());
					//$this->pre($key);
					array_splice($blocks, $key, 1, $inheritBlocks);
					reset($blocks);
					
				}		
			}
			foreach($blocks as $block){
				//echo $block->getBlockTypeHandle().' :';	
			}
			return $blocks;
		}
		
		
		function getAliasBlocks(){
			
			$blocks = $this->resolveInheritedAliasBlocks();
			$blocks = $this->filterBlocksByBlockType(NULL, NULL, $blocks);
			//$blocks = $this->filterBlocksByBlockType($this->$blocks); //filter it again

			return $blocks;
		}
		
		
		function validate($data){
			$e = Loader::helper('validation/error');
			
			//$e->add($this->pre($args, TRUE));
			return $e;
		}
		
		function save($data){
			
			if($data['mode'] == 'inherit' || empty($data['mode'])){
				$data['acID'] = NULL;
				$data['actHandle'] = NULL;
				
			}else if($data['mode'] == 'page'){
				$data['actHandle'] = NULL;
			}else if($data['mode'] == 'page_type'){
				$data['acID'] = NULL;
			}
			
			if(empty($data['aarHandle'])){
				$data['aarHandle'] = NULL;	
			}
			
			if(empty($data['btRefHandles'])){
				$data['btRefHandles']=NULL;	
			}else if(is_array($data['btRefHandles'])){
				$data['btRefHandles'] = implode(',', $data['btRefHandles']);	
			}
			
			//$this->pre($data);
			//return;
			parent::save($data);
		}
		
		
		
		
		function pre($thing, $save=FALSE){
			$str = '<pre style="white-space:pre; border:1px solid #ccc; padding:8px; margin:0 0 8px 0;">'.print_r($thing, TRUE).'</pre>';
			if(!$save){
				echo $str;	
			}
			return $str;
		}
		
	}
	
?>