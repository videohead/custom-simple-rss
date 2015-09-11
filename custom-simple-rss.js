    jQuery(document).ready(function (){  

                jQuery(".tab").click(function(){
                    var thisobj =  jQuery(this);
					var thisobjId =  jQuery(this).attr('id');
					jQuery(".tab").removeClass("on");
					jQuery(".postbox").removeClass("on");
					
                    if(thisobj.hasClass("on")){
                        jQuery(this).removeClass("on");
						jQuery("#postbox_"+thisobjId).removeClass("on");
                    }else{
                        jQuery(this).addClass("on");
						jQuery("#postbox_"+thisobjId).addClass("on");
                    }
                });
	
	
	});   
