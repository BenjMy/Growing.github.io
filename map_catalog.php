<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

<head>
  
     <!-- Global Site Tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=TRACKING_ID"></script>

  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-156846585-1');
  </script>


   <title>Map GROWING stations</title>
    <?php include("link.php"); ?>

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/form.css" rel="stylesheet">

  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/i18n/defaults-*.min.js"></script>

   <style>
      html, body {
         height: 100%;
         margin: 0;
      }
      #map-container {
          margin-left: auto;
          margin-right: auto;
          width: 60%;
      }

/*      input[type=button], input[type=submit], input[type=reset] {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 16px 32px;
        text-decoration: none;
        margin: 4px 2px;  
        cursor: pointer;
      }
*/
   </style>

<style>
/*the container must be positioned relative:*/
.custom-select {
  position: relative;
  font-family: Arial;
}

.custom-select select {
  display: none; /*hide original SELECT element:*/
}

.select-selected {
  background-color: DodgerBlue;
}

/*style the arrow inside the select element:*/
.select-selected:after {
  position: absolute;
  content: "";
  top: 14px;
  right: 10px;
  width: 0;
  height: 0;
  border: 6px solid transparent;
  border-color: #fff transparent transparent transparent;
}

/*point the arrow upwards when the select box is open (active):*/
.select-selected.select-arrow-active:after {
  border-color: transparent transparent #fff transparent;
  top: 7px;
}

/*style the items (options), including the selected item:*/
.select-items div,.select-selected {
  color: #ffffff;
  padding: 8px 16px;
  border: 1px solid transparent;
  border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
  cursor: pointer;
  user-select: none;
}

/*style items (options):*/
.select-items {
  position: absolute;
  background-color: DodgerBlue;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 99;
}

/*hide the items when the select box is closed:*/
.select-hide {
  display: none;
}

.select-items div:hover, .same-as-selected {
  background-color: rgba(0, 0, 0, 0.1);
}
</style>

</head>

<body>



<?php include("header_home.php"); ?>


    <section id="studyAreas" class="section-bg">

    <div class="container">
        <div class="row">            
            <div class="col-lg-2 col-md-6">
            
                <form action="map_catalog.php" method="post"> 
                        <div class="select" style="width:200px;">
                          <h4> Method </h4>
                          <select name="methodology">
                           <option value="all">all</option>
                           <option value="ERT">ERT</option>
                            <option value="IP">IP</option>
                          </select>
                        </div>
                        <div class="select" style="width:200px;">
                          <h4> Organisation </h4>
                          <select name="organisation">
                              <option value="all">all</option>
                              <option value="UNIPD">UNIPD</option>
                              <option value="CERTE">CERTE</option>
                              <option value="LBL">LBL</option>
                          </select>
                       </div>
                        <div class="select" style="width:200px;">
                            <h4> Scale </h4>
                          <select name="scale">
                              <option value="all">all</option>
                              <option value="Lab">Laboratory</option>
                              <option value="Field">Field</option>
                          </select>
                      </div>
                      <p>
                        <div class="select" style="width:200px;">
                       <button class="btn btn-primary" type="submit" value="submit_button" method="post">Submit</button>
                        </div>
                      </p>
                      <p>
                         Share your research inputs: 
                      </p>
                        <p>
                         <a href="mailto:sbenjamin.mary@unipd.it?subject=Data catalog MSCA GROWING" target="_blank" class="btn btn-primary">Email Us</a>
                      </p>

                </form>

            </div> 
          
            <div class="col-lg-7 col-md-6">
                Map of the geophysical roots observation field sites 

            <div id="map" style="width: 900px; height: 400px;"></div>
          </div>
       </div> 
    </div> 

</section><!-- #about -->

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

if(!isset($_POST['submit_button']))
{

# Connect to MySQL database
$conn = new PDO('mysql:host=localhost;dbname=test');

$req = $conn->prepare('SELECT *, x AS x, y AS y FROM cataloggeophy');
$req->execute();

/*
* If form variable is set, only return records that are complying with the form
*/

if(isset($_POST['methodology']))
{

if ($_POST['methodology'] != 'all') { 
    $req = $conn->prepare('SELECT *, x AS x, y AS y FROM cataloggeophy WHERE method = ?');
    $req->execute(array($_POST['methodology']));} 

if ($_POST['scale'] != 'all') { 
    $req = $conn->prepare('SELECT *, x AS x, y AS y FROM cataloggeophy WHERE scale = ?');
    $req->execute(array($_POST['scale']));} 


if ($_POST['organisation'] != 'all') { 
    $req = $conn->prepare('SELECT *, x AS x, y AS y FROM cataloggeophy WHERE organisation = ?');
    $req->execute(array($_POST['organisation']));} 

}


// Pour rassembler les informations au moment de la requête, on effectue des jointures.


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
  <div class="container">
    <div class="list-group">
      <a href="#" class="list-group-item">
        <h4 class="list-group-item-heading">Title: <?php echo $row['organisation']; ?></h4>
        <p class="list-group-item-text"> <strong>Method</strong> : <?php echo $row['method']; ?></p>
        <p class="list-group-item-text"> <strong>Date</strong> : <?php echo $row['date']; ?></p>
        <p class="list-group-item-text"> <strong>Scale</strong> : <?php echo $row['scale']; ?></p>
      </a>
    </div>
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

} // if submit has been pressed
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

    // var mapquestOAM = new L.TileLayer("http://{s}.mqcdn.com/tiles/1.0.0/sat/{z}/{x}/{y}.jpg", {
    //  maxZoom: 3,
    //  subdomains: ["oatile1", "oatile2", "oatile3", "oatile4"],
    //  attribution: 'Tiles courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a>. Portions Courtesy NASA/JPL-Caltech and U.S. Depart. of Agriculture, Farm Service Agency'
    // });

    // Bike Racks
    var bikeRacksIcon = L.icon({
      iconUrl: './img/leaficon.png',
      iconSize: [24, 28],
      iconAnchor: [12, 28],
      popupAnchor: [0, -25]
    });


    bikeRacks = new L.geoJson(null, {
      pointToLayer: function (feature, latlng) {
        return L.marker(latlng, {
          icon: bikeRacksIcon,
          title: feature.properties.organisation
        });
      },
      onEachFeature: function (feature, layer) {
        if (feature.properties) {
          var content = '<table border="1" style="border-collapse:collapse;" cellpadding="2">' +
            '<tr>' + '<th>ID</th>' + '<td>' + feature.properties.id + '</td>' + '</tr>' +
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

      // // load GeoJSON from an external file
      // $.getJSON("results.geojson",function(data){
      //   // add GeoJSON layer to the map once the file is loaded
      //   L.geoJson(data).addTo(map);
      // });
     

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

    // var baseLayers = {
    //   "MapQuest Streets": mapquestOSM,
    //   // "MapQuest Aerial": mapquestOAM
    // };

    var overlays = {
     "Bike Racks": bikeRacks,
     "Bike Racks (clustered)": bikeRackClusters,
    };

    layersControl = new L.Control.Layers(baseLayers, overlays, {
     collapsed: false
    });

    // map.addControl(layersControl);


</script>

  
  <!--==========================
  -- JavaScript Libraries 
  ============================-->

<!-- form
 -->

  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/mobile-nav/mobile-nav.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/counterup/counterup.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="lib/isotope/isotope.pkgd.min.js"></script>
  <script src="lib/lightbox/js/lightbox.min.js"></script>
  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>

  <!-- Template map Javascript File -->
  <script src="map/sample-geojson.js" type="text/javascript"></script>
  <script src="map/leaflet.js" type="text/javascript"></script>

<script type="text/javascript">

</body>
</html>