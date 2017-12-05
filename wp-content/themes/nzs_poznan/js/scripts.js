//mapa
    var $headerMapCont = $('.map');
    if($headerMapCont.size() > 0){

		//omijam i wracam po 
        function initHeaderMap(){
			//
            function createHeaderMap($cnt, lat, lng){
                var opts = {
                    center: new google.maps.LatLng(lat, lng),
                    zoom: 8,
                    scrollwheel: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
				// tworzymy mape do zm hm
                var headerMap = new google.maps.Map($cnt.get(0), opts);
                
                if(restaurantsList){
                    for(var entry in restaurantsList){
                        
                        var geocoder = new google.maps.Geocoder();
						
                        geocoder.geocode({
                            address: restaurantsList[entry].city
                        }, function(results, status){
							// jeśli status jest ok
                            if(status == google.maps.GeocoderStatus.OK){
								//rysujemy na  mapce kółeczko
                                var options = {
                                    strokeColor: "#000",
                                    strokeOpacity: 1,
                                    strokeWeight: 2,
                                    fillColor: "#f55b0c",
                                    fillOpacity: 1,
                                    center: results[0].geometry.location,
                                    map: headerMap,
                                    radius: restaurantsList[entry].restaurantsCount * 3500
                                }

                                new google.maps.Circle(options);

                            }else{
                                alert("Geocode was not successful for the following reason: " + status);
                            }

                        });
                    }
                }
            }
        
			//prosimy o lokalizację
            if(navigator.geolocation) {
				//f zwrotna uruchomiona po wyrażeniu zgody na podanie położenia, position - aktualna pozyucja
                var success = function(position) {
					//tworzy mapkę headerMapCont leci jako arg do f header map
                    createHeaderMap($headerMapCont, position.coords.latitude, position.coords.longitude)
                };

                var error = function() { 
                    createHeaderMap($headerMapCont, 52.259, 21.020); //warsaw coords
                }

                navigator.geolocation.getCurrentPosition(success, error);

            }else {
				// jeśli przegladarka nie obsługuje geo
                createHeaderMap($headerMapCont, 52.259, 21.020)
            }
        }
        //po załadowaniu drzewa dok i mapki
        google.maps.event.addDomListener(window, 'load', initHeaderMap);
    }
	
	var $rightMapCont = $('.right .map');
    if($rightMapCont.size() > 0){
        
        var address = $.trim($('input#gmap-address').val());
        
        var geocoder = new google.maps.Geocoder();
        
        geocoder.geocode({
            address: address
        }, function(results, status){
            
            if(status == google.maps.GeocoderStatus.OK){
                
                var mapOptions = { 
                    center: results[0].geometry.location, 
                    zoom: 16,
                    mapTypeId: google.maps.MapTypeId.ROADMAP 
                };

                var rightMap = new google.maps.Map($rightMapCont.get(0), mapOptions);
                
                new google.maps.Marker({
                    position: results[0].geometry.location,
                    map: rightMap
                });
                
            }else{
                alert("Geocode was not successful for the following reason: " + status);
            }
            
        });
    }