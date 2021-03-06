<?php

use yii\helpers\Url;
use yii\helpers\Html;

$redis = Yii::$app->redis;

$result = $redis->executeCommand('hmset', ['test_collection', 'key1', 'val1', 'key2', 'val2']);

?>

<script>
var list_services = <?php echo json_encode($list_services); ?>;
</script>
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.css" />
<script src="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.js"></script>

<div class="panel panel-default">

    <div class="panel-heading">
        <?php echo Yii::t('DirectoryModule.views_directory_members', '<strong>Services</strong>'); ?>
    </div>

    <div class="panel-body">

        <!-- search form -->

        <?php echo Html::beginForm(Url::to(['/services/services/find']), 'get', array('class' => 'form-search')); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group form-group-search">
                    <?php echo Html::textInput("keyword", $keyword, array("class" => "form-control form-search", "placeholder" => Yii::t('DirectoryModule.views_directory_members', 'search for services'))); ?>
                    <?php echo Html::submitButton(Yii::t('DirectoryModule.views_directory_members', 'Search'), array('class' => 'btn btn-default btn-sm form-button-search')); ?>
                </div>
            </div>
        </div>
        <?php echo Html::endForm(); ?>

        <?php if (count($services) == 0): ?>
            <p><?php echo Yii::t('DirectoryModule.views_directory_members', 'No services found into the net!'); ?></p>
        <?php endif; ?>

	<div class="row">
    		<!-- map -->
		<div class="col-md-8">
    			<div id="map" style="width: 600px; height: 400px"></div>
		</div>
		<!-- list services -->
		<div class="col-md-4">
			<ul class="media-list">
			<?php foreach ($list_services as $item): ?>
  				<li id="<?php echo $item->id ?>"class="media">
    					<div class="media-left">
      						<a href="#">
        					<img class="media-object" src="..." alt="...">
      						</a>
    					</div>
    					<div class="media-body">
      						<h4 class="media-heading"><?php echo $item->name ?></h4>
     ...
    					</div>
  				</li>
			<?php endforeach; ?>
			</ul>	
		</div>
    	</div>
    </div>
    <hr>


</div>

<!-- script click services menu(right) -->
<script>
    var my_position;
    $(document).ready(function (){
    			navigator.geolocation.getCurrentPosition(showPosition);
    			function showPosition(position) {
				my_position = [position.coords.latitude, position.coords.longitude];	
    		                showMap(my_position);
			}	
		     });

    function showMap(current_pos){

        var basicLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6IjZjNmRjNzk3ZmE2MTcwOTEwMGY0MzU3YjUzOWFmNWZhIn0.Y8bhBaUMqFiPrDRW9hieoQ', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
            '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="http://mapbox.com">Mapbox</a>',
            id: 'mapbox.streets'
        });
        //var services_layer;
        var url = 'index.php?r=services/find/get-services-json&lat=' + current_pos[0] +'&long=' + current_pos[1] + '&radius=' + '2'
        jQuery.getJSON(url, function (json) {
                var services_layer = json;
                var layer_list = [];

                var baseMaps = {
                    "Map": basicLayer
                };

                var overlayMaps = {
                };

                for(var i=0; i<services_layer.length; i++){
                    var service_name = services_layer[i]['name'];
                    var layer = services_layer[i];
                    var points =  layer['points'];
                    var poi = [];
                    for(var ii=0; ii<points.length; ii++){
                        var name_point = points[ii]['name'];
                        var lat = points[ii]['lat'];
                        var long = points[ii]['long'];
                        var tmp = L.marker([lat, long]).bindPopup(name_point);
                        poi.push(tmp);
                    }
                    var tmp_layer = L.layerGroup(poi);
                    overlayMaps[service_name] = tmp_layer;
                    layer_list.push(tmp_layer);
                }

                layer_list.push(basicLayer);

                var map = L.map('map', {
                    center: current_pos,
                    zoom: 14,
                    layers: layer_list
                });


                L.control.layers(baseMaps, overlayMaps).addTo(map);

        });
	
    /*
	var basicLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6IjZjNmRjNzk3ZmE2MTcwOTEwMGY0MzU3YjUzOWFmNWZhIn0.Y8bhBaUMqFiPrDRW9hieoQ', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    });


	var littleton = L.marker([38.1853897, 15.5486825]).bindPopup('This is Littleton, CO.'),
    	denver    = L.marker([38.183654461352646, 15.553014278411865]).bindPopup('This is Denver, CO.'),
    	aurora    = L.marker([38.182068998322094, 15.55065393447876]).bindPopup('This is Aurora, CO.');
    	var servizio_uno = L.layerGroup([littleton, denver, aurora]);

	var map = L.map('map', {
   		center: current_pos,
    		zoom: 14,
    		layers: [basicLayer, servizio_uno]
	});
	*/
	//var map = L.map('map').setView(current_pos, 13);
    //var map = L.map('map').setView([51.505, -0.09], 13);

/*
    	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6IjZjNmRjNzk3ZmE2MTcwOTEwMGY0MzU3YjUzOWFmNWZhIn0.Y8bhBaUMqFiPrDRW9hieoQ', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(map);
*/	
/*
	var baseMaps = {
    		"Map": basicLayer,
	};
	var overlayMaps = {
    		"Servizio Uno": servizio_uno
	};
	L.control.layers(baseMaps, overlayMaps).addTo(map);
  */
        /*
    L.marker([51.5, -0.09]).addTo(map)
        .bindPopup("<b>Hello world!</b><br />I am a popup.").openPopup();

    L.circle([51.508, -0.11], 500, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5
    }).addTo(map).bindPopup("I am a circle.");

    L.polygon([
        [51.509, -0.08],
        [51.503, -0.06],
        [51.51, -0.047]
    ]).addTo(map).bindPopup("I am a polygon.");


    var popup = L.popup();

    function onMapClick(e) {
        popup
            .setLatLng(e.latlng)
            .setContent("You clicked the map at " + e.latlng.toString())
            .openOn(map);
    }

    map.on('click', onMapClick);
    */


    }
	
</script>

<!-- script map -->
<script>
   </script>
