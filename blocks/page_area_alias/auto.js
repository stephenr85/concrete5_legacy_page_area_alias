

var ccm_PageAreaAliasForm = function(element, options){
	
	
	var I = this;
	
	this.$element = $(element);
	this.$element.data('ccm_PageAreaAliasForm', this);
	
	this.$toolsDir = this.$element.find("[name=pageAreaAliasToolsDir]");
	this.$mode = this.$element.find("[name=mode]");
	
	this.$cID = this.$element.find("[name=acID]");
	this.$cIDWrap = this.$element.find("div.choose-collection");	
	
	this.$ctHandle = this.$element.find("[name=actHandle]");
	this.$ctHandleWrap = this.$element.find("div.choose-page-type");
	
	this.$arHandle = this.$element.find("[name=aarHandle]");
		
	this.$mode.change(function(evt){
		I.setMode(I.getMode());
	});
	
	this.$cID.add(this.$ctHandle).change(function(evt){
		I.refreshCollectionAreas();
	});
	
	this.setMode(this.getMode(), 0);	
};

ccm_PageAreaAliasForm.prototype = {
	getToolsDir:function(){
		return this.$toolsDir.val();
	},
	getCollectionID:function(){
		var cID = this.$cID.val();
		if(this.getMode() == 'page_type'){
			cID = this.getCollectionTypeMasterID();
		}
		return cID;
	},
	getCollectionTypeHandle:function(){
		return this.$ctHandle.val();
	},
	getCollectionTypeMasterID:function(){
		var handle = this.getCollectionTypeHandle(),
			$ctmID = this.$element.find("input[name^='actmID_"+handle+"']");
		return $ctmID.val();
	},
	getMode:function(){
		return this.$mode.filter(":checked").val();	
	},
	setMode:function(mode, speed){
		//console.log(mode);
		var $m = this.$mode.filter("[value='"+mode+"']"),
			speed = speed == null ? "slow" : speed;
		if($m.length){
			if(!$m.is(":checked")) this.$m[0].checked = true;
			if($.isFunction(this["_setMode_"+mode])){
				this["_setMode_"+mode].apply(this, arguments);
			}
		}
		return this;
	},	
	_setMode_inherit:function(mode, speed){
		this.$cIDWrap.slideUp(speed);
		this.$ctHandleWrap.slideUp(speed);
		this.refreshCollectionAreas();
	},
	_setMode_page:function(mode, speed){
		this.$ctHandleWrap.slideUp(speed);
		this.$cIDWrap.slideDown(speed);
		this.refreshCollectionAreas();
	},
	_setMode_page_type:function(mode, speed){
		this.$ctHandleWrap.slideDown(speed);
		this.$cIDWrap.slideUp(speed);
		this.refreshCollectionAreas();
	},
	
	refreshCollectionAreas:function(){
		console.log("refreshCollectionAreas()");
		var I = this;
		
		var opts = {
			url: this.$toolsDir.val()+"area_handles.php",
			data:{cID:this.getCollectionID()},
			dataType:"json",
			error:$.proxy(this._onRefreshCollectionAreasError, this),
			success:$.proxy(this._onRefreshCollectionAreasSuccess, this),
			complete:$.proxy(this._onRefreshCollectionAreasComplete, this),
			cache:false			
		};
		$.ajax(opts);
	},
	_onRefreshCollectionAreasError:function(){
		console.log("error", arguments);	
	},
	_onRefreshCollectionAreasSuccess:function(data){
		//console.log("success", arguments);		
		var prevVal = this.$arHandle.val();
		this.$arHandle.find("option").slice(1).remove();
		if(data.arHandles){
			for(var a=0; a < data.arHandles.length; a++){
				this.$arHandle.append("<option>"+data.arHandles[a]+"</option>");
			}
			this.$arHandle.val(prevVal);
		}
	},
	_onRefreshCollectionAreasComplete:function(){
		//console.log("complete", arguments);
	}	
	
};


ccm_PageAreaAliasForm.selectSitemapNode = function(cID, name){
	//ccm_selectSitemapNode.apply(this, arguments);
	console.log(arguments);
	var $btn = $(ccmActivePageField),
		$wrap = $btn.closest(".ccm-summary-selected-item"),
		$label = $wrap.find(".ccm-summary-selected-item-label"),
		$field = $wrap.find("[name='"+$btn.attr("dialog-sender")+"']");
	if($field.val() != cID){
		$label.text(name);
		$field.val(cID);
		$field.change();
	}
	//console.log(this);
	console.log($btn.add($label).add($field));
};


$(document).ready(function(){
	
	var controller = new ccm_PageAreaAliasForm($("#ccm-block-form"));
	
	
	
});