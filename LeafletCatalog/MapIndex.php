

      <div class="container">

<!-- <script type="text/javascript">
    function selectAll() 
    { 
        selectBox = document.getElementById("methodology");

        for (var i = 0; i < selectBox.options.length; i++) 
        { 
             selectBox.options[i].selected = true; 
        } 
    }
</script>

<form method="post" action="file2.php">
    <select id="someId" name="selectName[]" multiple>
        <option value="123.123.123.123">123.123.123.123</option>
        <option value="234.234.234.234">234.234.234.234</option>
    </select>
    <input type="submit" name="submit" value=Submit onclick="selectAll();">
</form> -->

<!-- Formulaires
 -->         
          <form action="MapIndex.php" method="post"> <!-- // 4 - envoyer du POST -->
            <select name="methodology">
                <option value="ERT">ERT</option>
                <option value="IP">IP</option>
            </select>
            <select name="organisation">
                <option value="UNIPD">UNIPD</option>
                <option value="CERTE">CERTE</option>
                <option value="LBL">LBL</option>
            </select>
            <select name="scale">
                <option value="Laboratory">Laboratory</option>
                <option value="Field">Field</option>
            </select>
            <input type="submit" value="Valider" />
        </form>

    </div> 


<div id='map'></div>


<!-- MySQL Points to GeoJSON ->->- Also in the file MySQLPts2GeoJson.php
 -->
<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js"></script>


<!-- <script src="results.json" type="text/javascript"></script>
 --><!-- <script src="leafletGeoJson.js" type="text/javascript"></script>
 -->

<?php
/*
 * Title:   MySQL Points to GeoJSON
 * Notes:   Query a MySQL table or view of points with x and y columns and return the results in GeoJSON format, suitable for use in OpenLayers, Leaflet, etc.
 * Author:  Bryan R. McBride, GISP
 * Contact: bryanmcbride.com
 * GitHub:  https://github.com/bmcbride/PHP-Database-GeoJSON
 */

# Connect to MySQL database
$conn = new PDO('mysql:host=localhost;dbname=test');

# Build SQL SELECT statement including x and y columns
// $sql = 'SELECT *, x AS x, y AS y FROM cataloggeophy WHERE Method= echo $_POST['methodology']';

// $requete = 'SELECT *, x AS x, y AS y FROM cataloggeophy WHERE Method=\'?\'');
// $sql = array($_POST['methodology']));

// $req = $conn->prepare('SELECT nom FROM jeux_video WHERE possesseur = ?');

// $var=$_POST['methodology'];//variable venant du formulaire
// $sql = 'SELECT *, x AS x, y AS y FROM cataloggeophy WHERE Method='.$var);




$req = $conn->prepare('SELECT *, x AS x, y AS y FROM cataloggeophy');
$req->execute();

/*
* If form variable is set, only return records that are complying with the form
*/

// if (isset($_POST['organisation'])) {
//     $req = $conn->prepare('SELECT *, x AS x, y AS y FROM cataloggeophy WHERE organisation = ?');
//     $req->execute(array($_POST['organisation']));
// }

  // $req = $conn->prepare('SELECT *, x AS x, y AS y FROM cataloggeophy WHERE Method = ?');
  // $req->execute(array($_POST['Method']));

// $req = $conn->prepare('SELECT *, x AS x, y AS y FROM cataloggeophy WHERE Method = ?  AND Organisation = ?');
// $req->execute(array($_POST['methodology'], $_POST['organisation']));

// $req->execute(array($_POST['methodology'], $_POST['organisation']));

// echo '<ul>';
// while ($donnees = $req->fetch())
// {
//   echo '<li>' . $donnees['Method'] . ' (' . $donnees['Organisation'] . '</li>';
// }
// echo '</ul>';

// $req->closeCursor();

// while ($donnees = $reponse->fetch())

/*
* If bbox variable is set, only return records that are within the bounding box
* bbox should be a string in the form of 'southwest_lng,southwest_lat,northeast_lng,northeast_lat'
* Leaflet: map.getBounds().pad(0.05).toBBoxString()
*/
// if (isset($_GET['bbox']) || isset($_POST['bbox'])) {
//     $bbox = explode(',', $_GET['bbox']);
//     $sql = $sql . ' WHERE x <= ' . $bbox[2] . ' AND x >= ' . $bbox[0] . ' AND y <= ' . $bbox[3] . ' AND y >= ' . $bbox[1];
// }

# Try query or error
// $rs = $conn->query($sql);
if (!$req) {
    echo 'An SQL error occured.\n';
    exit;
}

# Build GeoJSON feature collection array
$geojson = array(
   'type'      => 'FeatureCollection',
   'features'  => array()
);

# Loop through rows to build feature arrays
while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
    $properties = $row;
    // echo '<li>' . $properties['Method'] . ' (' . $properties['Organisation'] . '</li>';
    # Remove x and y fields from properties (optional)
    unset($properties['x']);
    unset($properties['y']);
    $feature = array(
        'type' => 'Feature',
        'geometry' => array(
            'type' => 'Point',
            'coordinates' => array(
                $row['x'],
                $row['y']
            )
        ),
        'properties' => $properties
    );
    # Add feature arrays to feature collection array
    array_push($geojson['features'], $feature);

?>

    <div>
    <h1>Title: <?php echo $row['Method']; ?></h1>
    <strong>Method</strong> : <?php echo $row['Method']; ?><br />
    </div>

<?php
}
$req->closeCursor(); // Termine le traitement de la requête

// header('Content-type: application/json');
// echo json_encode($geojson, JSON_NUMERIC_CHECK);
$fp = fopen('results.json', 'w');
fwrite($fp, json_encode($geojson, JSON_NUMERIC_CHECK));
fclose($fp);

$conn = NULL;
?>


 <script type="text/javascript">
    var map;

    // var mapquestOSM = new L.TileLayer("http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png", {
    //  maxZoom: 17,
    //  subdomains: ["otile1", "otile2", "otile3", "otile4"],
    //  attribution: 'Tiles courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a>. Map data (c) <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, CC-BY-SA.'
    // });

    var mapquestOSM = new L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                  maxZoom: 16,
                  attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                  id: 'mapbox/light-v9'
                });

    var mapquestOAM = new L.TileLayer("http://{s}.mqcdn.com/tiles/1.0.0/sat/{z}/{x}/{y}.jpg", {
     maxZoom: 3,
     subdomains: ["oatile1", "oatile2", "oatile3", "oatile4"],
     attribution: 'Tiles courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a>. Portions Courtesy NASA/JPL-Caltech and U.S. Depart. of Agriculture, Farm Service Agency'
    });

    // Bike Racks
    // var bikeRacksIcon = L.icon({
    //   iconUrl: 'planticon.jpg',
    //   iconSize: [24, 28],
    //   iconAnchor: [12, 28],
    //   popupAnchor: [0, -25]
    // });
    bikeRacks = new L.geoJson(null, {
      pointToLayer: function (feature, latlng) {
        return L.marker(latlng, {
          // icon: bikeRacksIcon,
          // title: feature.properties.address
        });
      },
      onEachFeature: function (feature, layer) {
        if (feature.properties) {
          var content = '<table border="1" style="border-collapse:collapse;" cellpadding="2">' +
            '<tr>' + '<th>ID</th>' + '<td>' + feature.properties.id + '</td>' + '</tr>' +
            '<tr>' + '<th>Method</th>' + '<td>' + feature.properties.Method + '</td>' + '</tr>' +
            '<tr>' + '<th>Organisation</th>' + '<td>' + feature.properties.Organisation + '</td>' + '</tr>' +
            '<table>';
          layer.bindPopup(content);
        }
      }
    });
    bikeRackClusters = new L.MarkerClusterGroup({
      spiderfyOnMaxZoom: true,
      showCoverageOnHover: false,
      zoomToBoundsOnClick: true,
      disableClusteringAtZoom: 16
    });
    $.getJSON("results.json", function (data) {
      bikeRacks.addData(data);
      bikeRackClusters.addLayer(bikeRacks);
    }).complete(function () {
      map.fitBounds(bikeRacks.getBounds());
    });

    // Bus Routes
    // var busRoutes = new L.geoJson(null, {
    //  style: function (feature) {
    //    return {
    //      color: '#9a9afc',
    //      weight: 2,
    //      opacity: 1
    //    };
    //  },
    //  onEachFeature: function (feature, layer) {
    //    if (feature.properties) {
    //      var content = '<table border="1" style="border-collapse:collapse;" cellpadding="2">' +
   //          '<tr>' + '<th>CDTA Route</th>' + '<td>' + feature.properties.cdta_route + '</td>' + '</tr>' +
   //          '<tr>' + '<th>Route</th>' + '<td>' + feature.properties.var_route + '</td>' + '</tr>' +
   //          '<tr>' + '<th>Identification</th>' + '<td>' + feature.properties.var_identi + '</td>' + '</tr>' +
   //          '<tr>' + '<th>Description</th>' + '<td>' + feature.properties.var_descri + '</td>' + '</tr>' +
   //          '<table>';
    //      layer.bindPopup(content);
    //    }
    //  }
    // });
    // $.getJSON("busRoutes_geojson.php", function (data) {
    //   busRoutes.addData(data);
    // });

    map = new L.Map("map",{
      layers: [mapquestOSM, bikeRacks]
    });

    var baseLayers = {
      "MapQuest Streets": mapquestOSM,
      "MapQuest Aerial": mapquestOAM
    };

    var overlays = {
     "Bike Racks": bikeRacks,
     "Bike Racks (clustered)": bikeRackClusters,
    };

    layersControl = new L.Control.Layers(baseLayers, overlays, {
     collapsed: false
    });

    map.addControl(layersControl);


</script>

