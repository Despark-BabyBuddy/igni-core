<?php

    $options = $field->getOptions();
    $coors = Array(
        "lat" => $field->parent->latitude,
        'long' => $field->parent->longitude
    );
    $coors = json_encode($coors);

?>
<div class="form-group">
    {!! Form::label('map_locator', $options['label']) !!}
    <br>
    <button class="btn" id="find-on-map" type="button">Find on Map</button>
    <br>
    <div class="controls">
            <div id="event-location-inputs">
                <div id="geocoding-map" class="admin-map" data-center="{{ $coors }}">
                </div>
            </div>
        </div>
</div>

@push('additionalScripts')
<script>
        // Init map for places
        var $map = $('#geocoding-map');

        if ($map.length) {
            $map.setmap({
                map: {
                    center: $map.data('center') || {
                        lat: '51.5008',
                        lng: '-0.1247'
                    },
                    type: 'roadmap',
                },
                markers: [
                    {
                        position: $map.data('center') || {
                            lat: '51.5008',
                            lng: '-0.1247'
                        },
                        drop: function () {
                            $('#latitude').val(this.position.lat);
                            $('#longitude').val(this.position.lng);
                        }
                    }
                ]
            });

            $('#find-on-map').bind('click', function () {
                var location = $('#address').val() + ', ' + $('#city').val() + ', ' + $('#postcode').val() + ', United Kingdom';

                $('#geocoding-map').setmap('center', location, function (lat, lng, address) {
                    var markers = $('#geocoding-map').setmap('getMarkerObjects');
                    $('#latitude').val(lat);
                    $('#longitude').val(lng);
                    markers[0].obj.setPosition(new window.google.maps.LatLng(lat, lng));
                });
            });
        }
</script>
<script src="http://maps.googleapis.com/maps/api/js?v=3.15&language=en&sensor=false&region=UK"></script>
<script src="/js/vendor/setmap/lib/setmap.js"></script>      

@endpush


