jQuery( "#billing_address_1" ).keypress(function() {
	jQuery( "#billing_city" ).hide(); 
	jQuery( "#billing_postcode" ).hide();
});

var placeSearch, autocomplete,placeSearch2, autocomplete2;

//add listeners to billing and shipping address fields
function initAutocomplete() {
	autocomplete = new google.maps.places.Autocomplete((document.getElementById('billing_address_1')),{types: ['geocode']});
	autocomplete.addListener('place_changed', fillInBillingAddress);
	autocomplete2 = new google.maps.places.Autocomplete((document.getElementById('shipping_address_1')),{types: ['geocode']});
	autocomplete2.addListener('place_changed', fillInShippingAddress);
}
//fill in billing address
function fillInBillingAddress() {   
	var place = autocomplete.getPlace();
	var finalthml=document.getElementById("billing_address_1").value;
	var numbersArray = finalthml.split(',');
	document.getElementById("billing_address_1").value=numbersArray[0];
	var address = {};
    place.address_components.forEach(function(c) {
		switch(c.types[0]){
			case 'street_number':
				address.StreetNumber = c.short_name;
				break;
			case 'route':
				address.StreetName = c.short_name;
				break;
			case 'neighborhood': case 'locality':    // North Hollywood or Los Angeles?
				document.getElementById("billing_city").value = c.short_name;
				break;
			case 'administrative_area_level_1':     //  Note some countries don't have states
				document.getElementById("billing_billing_state").value = c.long_name;
				break;
			case 'postal_code':
				document.getElementById("billing_billing_postcode").value = c.short_name;
				break;
			case 'country':
				address.Country = c;
				break;               
		}
    });
 jQuery( "#billing_city" ).show(); 
 jQuery( "#billing_postcode" ).show();       
}
//fill in shipping address
function fillInShippingAddress() {       
	var place = autocomplete2.getPlace();      
	var finalthml=document.getElementById("shipping_address_1").value;
	var numbersArray = finalthml.split(',');
	document.getElementById("shipping_address_1").value=numbersArray[0];
    var address = {};
    place.address_components.forEach(function(c) {
		switch(c.types[0]){
			case 'street_number':
				address.StreetNumber = c.short_name;
				break;
			case 'route':
				address.StreetName = c.short_name;
				break;
			case 'neighborhood': case 'locality':    // North Hollywood or Los Angeles?
				document.getElementById("shipping_city").value = c.short_name;
				break;
			case 'administrative_area_level_1':     //  Note some countries don't have states
				document.getElementById("shipping_billing_state").value = c.long_name;
				break;
			case 'postal_code':
				document.getElementById("shipping_billing_postcode").value = c.short_name;
				break;
			case 'country':
				address.Country = c;
				break;               
		}
    });
 	jQuery( "#shipping_city" ).show(); 
	jQuery( "#shipping_postcode" ).show();       
}
//locate coordinates
function geolocate() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			var geolocation = {
				lat: position.coords.latitude,
				lng: position.coords.longitude
			};
			var circle = new google.maps.Circle({
				center: geolocation,
				radius: position.coords.accuracy
			});
			autocomplete.setBounds(circle.getBounds());
		});
	}
}
