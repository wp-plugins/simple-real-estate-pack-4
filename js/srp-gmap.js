var srp_map;
var custom_icons = [];
google.load("maps", "2");
function srp_custom_icons(){
	var iconSchools;
	iconSchools = new GIcon(); 
	iconSchools.title = 'Schools';
	iconSchools.image = 'http://labs.google.com/ridefinder/images/mm_20_brown.png';
	iconSchools.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
	iconSchools.iconSize = new GSize(12, 20);
	iconSchools.shadowSize = new GSize(22, 20);
	iconSchools.iconAnchor = new GPoint(6, 20);
	iconSchools.infoWindowAnchor = new GPoint(5, 1);
	
	var iconGrocery;
	iconGrocery = new GIcon(); 
	iconGrocery.title = 'Grocery Stores';
	iconGrocery.image = 'http://labs.google.com/ridefinder/images/mm_20_yellow.png';
	iconGrocery.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
	iconGrocery.iconSize = new GSize(12, 20);
	iconGrocery.shadowSize = new GSize(22, 20);
	iconGrocery.iconAnchor = new GPoint(6, 20);
	iconGrocery.infoWindowAnchor = new GPoint(5, 1);
	
	var iconRestaurants;
	iconRestaurants = new GIcon(); 
	iconRestaurants.title = 'Restaurants';
	iconRestaurants.image = 'http://labs.google.com/ridefinder/images/mm_20_purple.png';
	iconRestaurants.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
	iconRestaurants.iconSize = new GSize(12, 20);
	iconRestaurants.shadowSize = new GSize(22, 20);
	iconRestaurants.iconAnchor = new GPoint(6, 20);
	iconRestaurants.infoWindowAnchor = new GPoint(5, 1);
	
	var iconHospitals;
	iconHospitals = new GIcon(); 
	iconHospitals.title = 'Hospitals';
	iconHospitals.image = 'http://labs.google.com/ridefinder/images/mm_20_blue.png';
	iconHospitals.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
	iconHospitals.iconSize = new GSize(12, 20);
	iconHospitals.shadowSize = new GSize(22, 20);
	iconHospitals.iconAnchor = new GPoint(6, 20);
	iconHospitals.infoWindowAnchor = new GPoint(5, 1);
	
	var iconGolf;
	iconGolf = new GIcon(); 
	iconGolf.title = 'Golf Cources';
	iconGolf.image = 'http://labs.google.com/ridefinder/images/mm_20_green.png';
	iconGolf.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
	iconGolf.iconSize = new GSize(12, 20);
	iconGolf.shadowSize = new GSize(22, 20);
	iconGolf.iconAnchor = new GPoint(6, 20);
	iconGolf.infoWindowAnchor = new GPoint(5, 1);
	
	var iconBanks;
	iconBanks = new GIcon(); 
	iconBanks.title = 'Banks';
	iconBanks.image = 'http://labs.google.com/ridefinder/images/mm_20_white.png';
	iconBanks.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
	iconBanks.iconSize = new GSize(12, 20);
	iconBanks.shadowSize = new GSize(22, 20);
	iconBanks.iconAnchor = new GPoint(6, 20);
	iconBanks.infoWindowAnchor = new GPoint(5, 1);
	
	var iconGasStations;
	iconGasStations = new GIcon(); 
	iconGasStations.title = 'Gas Stations';
	iconGasStations.image = 'http://labs.google.com/ridefinder/images/mm_20_gray.png';
	iconGasStations.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
	iconGasStations.iconSize = new GSize(12, 20);
	iconGasStations.shadowSize = new GSize(22, 20);
	iconGasStations.iconAnchor = new GPoint(6, 20);
	iconGasStations.infoWindowAnchor = new GPoint(5, 1);
	
	
	custom_icons['schools'] = iconSchools;
	custom_icons['grocery'] = iconGrocery;
	custom_icons['restaurants'] = iconRestaurants;
	custom_icons['hospitals'] = iconHospitals;
	custom_icons['golf'] = iconGolf;
	custom_icons['banks'] = iconBanks;
	custom_icons['gas_stations'] = iconGasStations;

}

function srp_default_createMarker(point,description) {
       	var marker = new google.maps.Marker(point);
       	google.maps.Event.addListener(marker, "click", function() {
       		marker.openInfoWindowHtml(description);
       	});			
       	return marker;
}

function srp_createMarker(point,html,icon) {
		srp_custom_icons();
		var marker = new GMarker(point, custom_icons[icon]);
       	GEvent.addListener(marker, "click", function() {
       		marker.openInfoWindowHtml(html,{maxWidth:315});
       	});
       	return marker;
	}

function srp_createMarkerCustom(point, name, address, type, ref, img) {
      var marker = new GMarker(point, custom_icons[type]);
      var html = img + '<strong>' + name + "</a></strong> <br />" + address +
	'<br />' + '<a href="' + ref + '">listing info</a>' 
      GEvent.addListener(marker, 'click', function() {
        marker.openInfoWindowHtml(html);
      });
      return marker;
    }
	
function srp_initialize() {
		srp_map = new google.maps.Map2(document.getElementById("gre_map_canvas"));
		srp_map.addControl(new google.maps.SmallMapControl());
		srp_map.addControl(new google.maps.MapTypeControl());		
		srp_setupmap();		
}

//Other AJAX mapping
var loc = srp_wp_admin + '/admin-ajax.php';
var markerArray;
markerArray = new Array();

jQuery(document).ready( function() {
function srp_addOverlay(marker){
	if(typeof gre_map == "undefined" && typeof srp_map !== "undefined"){
		srp_map.addOverlay(marker);
	}else{
		gre_map.addOverlay(marker);
	}
}

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
						srp_addOverlay(markerArray[i]);
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
						srp_addOverlay(marker);						
					}
					
					var ledgend = '<span class="' + i + '"><img src="' + custom_icons[i].image + '" /> - ' + custom_icons[i].title + '</span>';
					jQuery('#map div.srp_gre_legend').append(ledgend);					
				}			
			}
	}
	//END Yelp AJAX
	
	//BEGIN Schools Preload with 1.5 second timeout to let map_gre to load.
	if(typeof jQuery('#srp_gre_prop_coord').val() !== 'undefined'){
	jQuery(document).ready
	(	 	
	        function()
       	 {
	                setTimeout
	                (
	                        function()
	                        {
	                                srp_schools_preload();
	                        },
	                        2000
	                );
	        }
	); 
	}
	
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
					srp_addOverlay(marker);
			}
				
				var ledgend = '<span class="schools"><img src="' + custom_icons['schools'].image + '" /> - ' + custom_icons['schools'].title + '</span>';
				jQuery('#map div.srp_gre_legend').append(ledgend);
	}
	
	
	
	//END Schools Preload			
	
	// END for GRE Plugin
});