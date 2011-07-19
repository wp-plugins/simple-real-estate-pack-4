/*Old function that uses GMap API 2 with API key requried
function srp_geocode(){
	if(typeof(srp_geo) !== 'undefined' && jQuery.trim(jQuery('#listings_address').val()) != '' && jQuery.trim(jQuery('#listings_city').val()) != '' && jQuery.trim(jQuery('#listings_state').val()) != '' && jQuery.trim(jQuery('#listings_postcode').val()) != ''){
			var geo_url = 'http://maps.google.com/maps/geo?q=';
			var params = '&oe=utf8&sensor=false&output=json&key=' + srp_geo + '&jsoncallback=?';
			var address = jQuery('#listings_address').val() + ', ' + jQuery('#listings_city').val() + ', ' + jQuery('#listings_state').val() + ' ' + jQuery('#listings_postcode').val();
			var url = geo_url + address.replace(/ /g, "+") + params;
			var loc = srp_wp_admin + '/admin-ajax.php';
			jQuery.post(loc, {
				action: 'srp_geocode_ajax',
				address: address
			  }, function(data){
					lat = data[1];
					lng = data[0];
					jQuery('#listings_latitude').val(lat);
					jQuery('#listings_longitude').val(lng);
					srp_geocode_test(lat, lng);
				}, "json"
			);
		return false;
		}

}
*/

//Utilizing GMap API v3 (no API key needed)
function srp_geocode(){
	if(typeof(srp_geo) !== 'undefined' && jQuery.trim(jQuery('#listings_address').val()) != '' && jQuery.trim(jQuery('#listings_city').val()) != '' && jQuery.trim(jQuery('#listings_state').val()) != '' && jQuery.trim(jQuery('#listings_postcode').val()) != ''){

			var address = jQuery('#listings_address').val() + ', ' + jQuery('#listings_city').val() + ', ' + jQuery('#listings_state').val() + ' ' + jQuery('#listings_postcode').val();
                        var geocoder;
                        geocoder = new google.maps.Geocoder();
                        if (geocoder) {
                              geocoder.geocode( { 'address': address}, function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    var latlng = results[0].geometry.location;
                                    
                                    jQuery('#listings_latitude').val(latlng.lat());
                                    jQuery('#listings_longitude').val(latlng.lng());
                                    srp_geocode_test(latlng.lat(), latlng.lng());
                                }else{
                                  alert("Geocode was not successful for the following reason: " + status);
                                }
                              });
                            }

		return false;
		}

}

function srp_geocode_test(lat, lng){
		var test = '<a href="http://maps.google.com/maps?hl=en&q=' + lat + ' ' + lng + '" target="_blank">Check if location is correct</a>';
		jQuery('#test_geo_link').html(test);
		jQuery("#listings_latitude").triggerHandler("focus");
	}

jQuery(document).ready( function() {
	// BEGIN for GRE Plugin

	var geo_button = '<p><input id="srp_get_coord" type="button" name="get_coord" value="Get Lat/Long" /><span id="test_geo_link"></span></p>';

	if(typeof(jQuery('#listings3-div div')) !== 'undefined'){
		jQuery('#listings3-div div').append(geo_button);
	}

	if(jQuery('#listings_latitude').val() != '' && jQuery('#listings_longitude').val() != ''){
		var lat = jQuery('#listings_latitude').val();
		var lng = jQuery('#listings_longitude').val();
		srp_geocode_test(lat, lng);
	}
	jQuery('#srp_get_coord').click(function() {
		srp_geocode();
	});

	// END for GRE Plugin
});