var srp_map;
var custom_icons = [];
var myOptions = [];
function _icon_array(icon_title, icon_file){
	var _icon = {		
		position:		'',
		title:		 	icon_title,
		map:			srp_map,
		icon:			new google.maps.MarkerImage(
													_get_icon(icon_title),
													new google.maps.Size(32, 37),
													new google.maps.Point(0,0),
													new google.maps.Point(16, 37)
													)
		/*
		,
		shadow:			new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_shadow.png',
													new google.maps.Size(22, 20),
													new google.maps.Point(0,0),
													new google.maps.Point(6, 20)
													),
		*/
	};
	return _icon;
}

function _get_icon(icon_title){
    var icons = {
        'Schools': 'schools.png',
        'Grocery Stores': 'grocery.png',
        'Restaurants': 'restaurants.png',
        'Hospitals': 'hospitals.png',
        'Golf Cources': 'golf.png',
        'Banks': 'banks.png',
        'Gas Stations': 'gas_stations.png'
    };
    return srp_url + '/icons/' + icons[icon_title];
}

function srp_custom_icons(){
	var iconSchools = _icon_array('Schools', 'schools.png');
	var iconGrocery = _icon_array('Grocery Stores', 'grocery.png');
	var iconRestaurants = _icon_array('Restaurants', 'restaurants.png');
	var iconHospitals = _icon_array('Hospitals', 'hospitals.png');
	var iconGolf = _icon_array('Golf Cources', 'golf.png');	
	var iconBanks = _icon_array('Banks', 'banks.png');
	var iconGasStations = _icon_array('Gas Stations', 'gas_stations.png');	

	custom_icons['schools'] = iconSchools;
	custom_icons['grocery'] = iconGrocery;
	custom_icons['restaurants'] = iconRestaurants;
	custom_icons['hospitals'] = iconHospitals;
	custom_icons['golf'] = iconGolf;
	custom_icons['banks'] = iconBanks;
	custom_icons['gas_stations'] = iconGasStations;
}

function srp_setDefaultMarker(point,description) {       	
		var infowindow = new google.maps.InfoWindow({
			content: description
		});
		var marker = new google.maps.Marker({
			  position: point, 
			  map: srp_map
		});   
		marker.setMap(srp_map);
		google.maps.event.addListener(marker, "click", function() {
		  infowindow.open(srp_map,marker, {maxWidth:315});
		});
}

function srp_createMarker(point,html,icon) {
		srp_custom_icons();
		var icon_array = custom_icons[icon];
		//icon_array[map] = 'srp_map';
		icon_array.position = point;
		var marker = new google.maps.Marker(icon_array);       	
		var infowindow = new google.maps.InfoWindow({
			content: html
		});
		google.maps.event.addListener(marker, "click", function() {
       		//marker.openInfoWindowHtml(html,{maxWidth:315});
			//infowindow.open(srp_map,marker, {maxWidth:315});
                        infowindow.open(srp_map,marker);
       	});
       	return marker;
	}

function srp_createMarkerCustom(point, name, address, type, ref, img) {
      var marker = new GMarker(point, custom_icons[type]);
      var html = img + '<strong>' + name + "</a></strong> <br />" + address +
	'<br />' + '<a href="' + ref + '">listing info</a>' 
      var infowindow = new google.maps.InfoWindow({
			content: html
		});
		google.maps.event.addListener(marker, "click", function() {
       		//marker.openInfoWindowHtml(html,{maxWidth:315});
			//infowindow.open(srp_map,marker, {maxWidth:315});
                        infowindow.open(srp_map,marker);
       	});
      return marker;
    }
	
function srp_initialize() {		
		myOptions = {
			zoom: 13,
			mapTypeControl: true,
			mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
			navigationControl: true,
			navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
			mapTypeId: google.maps.MapTypeId.ROADMAP      
		}
		srp_map = new google.maps.Map(document.getElementById("gre_map_canvas"), myOptions);
		srp_setupmap();		
}

//Other AJAX mapping
var loc = srp_wp_admin + '/admin-ajax.php';
var markerArray;
markerArray = new Array();

jQuery(document).ready( function() {       
srp_refresh_tabs("#srp-tab-wrap");
srp_refresh_tabs(".srp-tabs");
function srp_addOverlay(marker){
	if(typeof gre_map !== "undefined"){
				marker.setMap(gre_map);
	}else
	if(typeof srp_map !== "undefined"){
				marker.setMap(srp_map);
	}
}

function srp_removeOverlay(marker){
	if(typeof gre_map !== "undefined"){
				marker.setMap(null);
	}else
	if(typeof srp_map !== "undefined"){				
				marker.setMap(null);
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
		  
		  jQuery('input#schools_select').click(function() {
			this.blur();
			this.focus();
		  });
		}

	jQuery('input[id^="yelp_cat_"]').change( function() {						
		//no need to check for yelp api key on every click
		//srp_function_exists('srp_yelp_api_key', 'option', srp_requestYelp, this);
		srp_requestYelp(this);
	});
	
	jQuery('input#schools_select').change( function() {						
		//no need to check for yelp api key on every click
		//srp_function_exists('srp_yelp_api_key', 'option', srp_requestYelp, this);
		srp_requestSchools();
	})
	  
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
					var ledgend = '<span class="' + cat + '"><img src="' + _get_icon(custom_icons[cat].title) + '" /> - ' + custom_icons[cat].title + '</span>';
					jQuery('#map div.srp_gre_legend').append(ledgend);				
					return false;
				}
			}
			var ajax_id = srp_ajax_loaderStart('gre_map_canvas', null);
			jQuery.post(loc, {
				action: 'srp_getYelp_ajax',
				term:		cat,
				lat:		coord[0],
				lng:		coord[1]
			  }, function(data){				  	
					srp_mapYelp(data);
					srp_ajax_loaderStop(ajax_id);
				},"json"
			);			
			return false;			
		}else{
			for(var i=0; i<markerArray.length; i++){
				if(markerArray[i].cat == cat){					
						srp_removeOverlay(markerArray[i]);
						jQuery('.srp_gre_legend span.' + cat).remove();
				}
			}
		}
                return false;
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
					
					var ledgend = '<span class="' + i + '"><img src="' + _get_icon(custom_icons[i].title) + '" /> - ' + custom_icons[i].title + '</span>';
					jQuery('#map div.srp_gre_legend').append(ledgend);					
				}			
			}
	}
	//END Yelp AJAX		
	
	function srp_requestSchools(){
		var prop_coord = jQuery('#srp_gre_prop_coord').val();
		var coord = prop_coord.split(',');
		var address = null;
		var cat = 'schools';
		if(jQuery('input#schools_select').attr('checked')){
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
                                        var ledgend = '<span class="' + cat + '"><img src="' + _get_icon(custom_icons[cat].title) + '" /> - ' + custom_icons[cat].title + '</span>';
					jQuery('#map div.srp_gre_legend').append(ledgend);				
					return false;
				}
			}
			
			var ajax_id = srp_ajax_loaderStart('gre_map_canvas', null);
			jQuery.post(loc, {
						action: 'srp_getSchools_ajax',
						address:		address,
						lat:		coord[0],
						lng:		coord[1]
					  }, function(data){
						  	srp_mapSchools(data);                                                        
							srp_ajax_loaderStop(ajax_id);
						},"json"
					);			
					return false;
		}else{
			for(var i=0; i<markerArray.length; i++){
				if(markerArray[i].cat == cat){					
						srp_removeOverlay(markerArray[i]);
						jQuery('.srp_gre_legend span.' + cat).remove();
				}
			}
		}
                return false;
	}		
	
	function srp_mapSchools(data){			
			var srp_education_div = jQuery('#srp_education').attr('id');
			//jQuery('#srp_education').append(data.content);
			//jQuery(function() {jQuery(".srp-tabs").tabs();});
			var category = data.markers;
			
			for(var i in category){
					var lat = category[i].lat;
					var lng = category[i].lng;
					var html = category[i].html;
					var point = new google.maps.LatLng(lat,lng);
					var marker = srp_createMarker(point,html,'schools');
					marker.cat = 'schools';
					markerArray.push(marker);
					marker.setMap(srp_map);
			}
				
				var ledgend = '<span class="schools"><img src="' + _get_icon(custom_icons[marker.cat].title) + '" /> - ' + custom_icons[marker.cat].title + '</span>';
				jQuery('#map div.srp_gre_legend').append(ledgend);
				return false;
	}
	
	
	
	//END Schools Preload			
        
	// END for GRE Plugin
});

// addLoadEvent by Simon Willison
// http://www.webreference.com/programming/javascript/onloads/
function addLoadEvent(func) { 
	  var oldonload = window.onload; 
	  if (typeof window.onload != 'function') { 
	    window.onload = func; 
	  } else { 
	    window.onload = function() { 
	      if (oldonload) { 
	        oldonload(); 
	      } 
	      func(); 
		} 
	  } 
}

function srp_ajax_loaderStart(id, title){
        var randomnumber=Math.floor(Math.random()*100001)
        var ajax_id = "ajax_loading_" + id + "_" + randomnumber;
        
        var append_to;
        var _width;
        var _height;
	if(id != null && id != 'undefined'){
		_width = jQuery('#'+id).width();
		_height = jQuery('#'+id).height();
		append_to = '#'+id;
                //alert(status + ' - ' + append_to);
	}else{
		append_to = 'body';
		_width = jQuery(window).width();
		_height = jQuery(window).height();
	}
	var img = '<img src="' + srp_url + '/images/ajax-loader.gif" alt="Loading. Please wait.">';
	if(title == null || title == 'undefined'){
		title = "Loading...";
	}
	var loading_message = '<div id="' + ajax_id + '" class="ajax_loader">' + img + title + '</div>';	
	jQuery(append_to).prepend(loading_message);
	var ajx_w = jQuery("#" + ajax_id).width();
	var ajx_h = jQuery("#" + ajax_id).height();
	var x = _width/2 - ajx_w/2;
	var y = _height/2 - ajx_h/2;
	jQuery('#' + ajax_id).css({'top' : y, 'left' : x});

        return ajax_id;    
}
function srp_ajax_loaderStop(id){        
	jQuery("#" + id).remove();
}

function srp_profile(x){
    
        if(x == 10){
            return false;
        }
        if(!x){
            var x = 0;
        }        
        var n = load_srp_functions.length;        
        
        var ajax_id = srp_ajax_loaderStart('srp_extension', null);
        
        for(var i=0; i<n; i++){
            var _listing_values = JSON.stringify(srp_listing_values);
            var _init_function = load_srp_functions[i];
           //alert(_init_function);
            jQuery.ajax({
                    type: "POST",
                    url: loc,
                    data: {
                        action: 'srp_ajax_call',
                        callback: _init_function,
                        srp_listing_values: _listing_values
                    },
                    success: function(data){
                            srp_output_gre(data);
                            srp_ajax_loaderStop(ajax_id);
                            x++;
                            load_srp_functions.splice('',1);                            
                            if(load_srp_functions.length >0){
                                srp_profile(x);
                            }
                    },
                    async: true,
                    dataType: "text"
            });        
            return false;
        }        
        return false;
}

function srp_profile_tabs(x){

        if(x == 10){
            return false;
        }
        if(!x){
            var x = 0;
        }
        var n = load_srp_functions.length;

        var ajax_id = srp_ajax_loaderStart('srp_extension', null);

        for(var i=0; i<n; i++){
            var _listing_values = JSON.stringify(srp_listing_values);
            var _init_function = load_srp_functions[i];

            jQuery.ajax({

                type: "POST",
                url: loc,
                data: {
                    action: 'srp_ajax_call',
                    callback: _init_function,
                    srp_listing_values: _listing_values
                },
                success: function(data){
                            srp_output_gre(data);
                            srp_ajax_loaderStop(ajax_id);
                    },
                    async: true,
                    dataType: "text"
            });
        }
        return false;
}

        function srp_output_gre(data){            
            jQuery('#srp_extension').append(data);
            srp_check_prefilled();
            jQuery("#srp-tab-wrap").tabs("destroy");
            jQuery(".srp-tabs").tabs("destroy");
            srp_refresh_tabs("#srp-tab-wrap");
            srp_refresh_tabs(".srp-tabs");            
        }