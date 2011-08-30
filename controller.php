<?php  defined('C5_EXECUTE') or die(_("Access Denied."));

/**
 * Allows you to show the contents of a parent or specific page in an area of your current page.
 * @package Page Area Alias
 * @author Stephen Rushing
 * @category Packages
 * @copyright  Copyright (c) 2011 Stephen Rushing. (http://www.esiteful.com)
 */
class PageAreaAliasPackage extends Package {

	protected $pkgHandle = 'page_area_alias';
	protected $appVersionRequired = '5.4.0';
	protected $pkgVersion = '1.0';
	
	public function getPackageDescription() {
		return t("Allows you to show the blocks of a parent or specific page in an area of your current page.");
	}
	
	public function getPackageName() {
		return t("Page Area Alias");
	}
	
	public function install() {
		$pkg = parent::install();
		BlockType::installBlockTypeFromPackage('page_area_alias', $pkg);	
	}
	
	public function upgrade(){
		parent::upgrade();
		
			
			
	}
	
}