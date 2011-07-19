jQuery(document).ready( function() {
	// BEGIN for GRE Plugin
	srp_geocode();
	jQuery('#listings_address').change( function() { srp_geocode();	});
	jQuery('#listings_city').change( function() { srp_geocode();	});
	jQuery('#listings_state').change( function() { srp_geocode();	});
	jQuery('#listings_postcode').change( function() { srp_geocode();	});
	
	if(jQuery.trim(jQuery('#listings_latitude').val()) != '' && jQuery.trim(jQuery('#listings_longitude').val()) != ''){
		var lat = jQuery('#listings_latitude').val();
		var lng = jQuery('#listings_longitude').val();
		srp_geocode_test(lat, lng);
	}
	jQuery('#srp_get_coord').click( function() { srp_geocode();	});

//this has to be done correctly via AJAX hooks
	function srp_geocode(){
		
		if(typeof(srp_geo) !== 'undefined' && jQuery.trim(jQuery('#listings_address').val()) != '' && jQuery.trim(jQuery('#listings_city').val()) != '' && jQuery.trim(jQuery('#listings_state').val()) != '' && jQuery.trim(jQuery('#listings_postcode').val()) != ''){			
			var geo_button = '<p><input id="srp_get_coord" type="button" name="get_coord" value="Get Lat/Long" /><span id="test_geo_link"></span></p>';
			var geo_url = 'http://maps.google.com/maps/geo?q=';
			var params = '&sensor=false&output=json&key=' + srp_geo + '&callback=?';
			var address = jQuery('#listings_address').val() + ',+' + jQuery('#listings_city').val() + ',+' + jQuery('#listings_state').val() + '+' + jQuery('#listings_postcode').val();
			var url = geo_url + address + params;			
			jQuery('#listings3-div div').append(geo_button);
			jQuery('#srp_get_coord').click(function(){												
				jQuery.getJSON(url, function(data){					
					jQuery.each(data.Placemark, function(i,place){														 
						var lat = place.Point.coordinates[1];
						var lng = place.Point.coordinates[0];
						jQuery('#listings_latitude').val(lat);
						jQuery('#listings_longitude').val(lng);
						srp_geocode_test(lat, lng)
						return false;
					 });
	
				});		
		
			});
		}
	}
	
	function srp_geocode_test(lat, lng){
		var test = '<a href="http://maps.google.com/maps?hl=en&q=' + lat + ' ' + lng + '" target="_blank">Check if location is correct</a>';
		jQuery('#test_geo_link').html(test);
	}
	
	// END for GRE Plugin
});