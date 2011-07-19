var loc = srp_wp_admin + '/admin-ajax.php';
var markerArray;
markerArray = new Array();

jQuery(document).ready( function() {

//Finish this function and the same one in srp-gre.php
function srp_function_exists(name, type, callbackfunc, arg){
	jQuery.post(loc, {
				action: 'srp_function_exists',
				name:		name,
				type:		type
			  }, function(data){
					if(data === '1'){
						if(typeof(arg) !== 'undefined'){
							callbackfunc(arg);	
						}else{
							callbackfunc();	
						}
					}
				  }
			);
	return false;
}

	//BEGIN Yelp AJAX
	
		// This is the hack for IE
		if (jQuery.browser.msie) {
		  jQuery('input[id^="yelp_cat_"]').click(function() {
			this.blur();
			this.focus();
		  });
		}

	jQuery('input[id^="yelp_cat_"]').change( function() {						
		//no need to check for yelp api key on every click
		//srp_function_exists('srp_yelp_api_key', 'option', srp_requestYelp, this);
		srp_requestYelp(this);
	});
	  
	function srp_requestYelp(arg){				
		
		var prop_coord = jQuery('#srp_gre_prop_coord').val();
		var coord = prop_coord.split(',');		
		var cat = jQuery(arg).attr("name");
			
		if(jQuery(arg).attr('checked')){
			if(markerArray.length > 0){
				var found = false;
				for(var i=0; i<markerArray.length; i++){
					if(markerArray[i].cat == cat){
						found = true;
						gre_map.addOverlay(markerArray[i]);
						jQuery('.srp_gre_legend span.' + cat).remove();
					}
				}
				if(found == true){	
					var ledgend = '<span class="' + cat + '"><img src="' + custom_icons[cat].image + '" /> - ' + custom_icons[cat].title + '</span>';
					jQuery('#map div.srp_gre_legend').append(ledgend);				
					return false;
				}
			}
			
			srp_ajax_loaderStart(null, 'gre_map_canvas');
			jQuery.post(loc, {
				action: 'srp_getYelp_ajax',
				term:		cat,
				lat:		coord[0],
				lng:		coord[1]
			  }, function(data){				  	
					srp_mapYelp(data);
					srp_ajax_loaderStop();
				},"json"
			);			
			return false;			
		}else{
			for(var i=0; i<markerArray.length; i++){
				if(markerArray[i].cat == cat){					
						gre_map.removeOverlay(markerArray[i]);
						jQuery('.srp_gre_legend span.' + cat).remove();
				}
			}
		}

	}
	
	function srp_mapYelp(data){
			if(typeof(data) !== 'undefined' && data != 0 && data != -1){
				var category = data;								
				for(var i in category){											
					for(var x in category[i]){
						var lat = category[i][x].lat;
						var lng = category[i][x].lng;
						var html = category[i][x].html;
						var point = new google.maps.LatLng(lat,lng);
						var marker = srp_createMarker(point,html,i);
						marker.cat = i;
						markerArray.push(marker);
						gre_map.addOverlay(marker);						
					}
					
					var ledgend = '<span class="' + i + '"><img src="' + custom_icons[i].image + '" /> - ' + custom_icons[i].title + '</span>';
					jQuery('#map div.srp_gre_legend').append(ledgend);					
				}			
			}
	}
	//END Yelp AJAX
	
	//BEGIN Schools Preload with 1 second timeout to let map_gre to load.
	jQuery(document).ready
	(
	        function()
       	 {
	                setTimeout
	                (
	                        function()
	                        {
	                                srp_function_exists('srp_education_api_key', 'option', srp_schools_preload);
	                        },
	                        1000
	                );
	        }
	); 
	
	function srp_schools_preload(){
		var prop_coord = jQuery('#srp_gre_prop_coord').val();
		var coord = prop_coord.split(',');
		var address = null;
		
		jQuery.post(loc, {
					action: 'srp_getSchools_ajax',
					address:		address,
					lat:		coord[0],
					lng:		coord[1]
				  }, function(data){srp_mapSchools(data)},"json"
				);			
				return false;
	}		
	
	function srp_mapSchools(data){			
			var srp_education_div = jQuery('#srp_education').attr('id');
			//jQuery('#srp_education').append(data.content);
			jQuery(function() {jQuery(".srp-tabs").tabs();});
			var category = data.markers;
			
			for(var i in category){
					var lat = category[i].lat;
					var lng = category[i].lng;
					var html = category[i].html;
					var point = new google.maps.LatLng(lat,lng);
					var marker = srp_createMarker(point,html,'schools');
					gre_map.addOverlay(marker);
			}
				
				var ledgend = '<span class="schools"><img src="' + custom_icons['schools'].image + '" /> - ' + custom_icons['schools'].title + '</span>';
				jQuery('#map div.srp_gre_legend').append(ledgend);
	}
	
	
	
	//END Schools Preload			
	
	// END for GRE Plugin
});