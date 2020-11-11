<?php
//setup
session_start();
require __DIR__ . '/vendor/autoload.php';
$projectId = 'mealplanner-cc-2020'; //change when merging to master
putenv('GOOGLE_APPLICATION_CREDENTIALS=MealPlanner cc 2020-472d0cbbc34e.json');
//setup datastore
use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\Datastore\Query\Query;
$datastore = new DatastoreClient([
  'projectId' => $projectId
]);

function showMap($location){
  $map = <<<"MAP"
  <iframe
      width="450"
      height="250"
      frameborder="0" style="border:0"
      src="https://www.google.com/maps/embed/v1/search?key=AIzaSyAW8SF3ZkODnax_QoAshoj0WSPIdosBT5U&q=supermarkets+near+$location">
    </iframe>
MAP;
  echo $map;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Google Maps Test</title>
    <link rel="stylesheet" href="/stylesheets/style.css">
  </head>
  <body>
  <div class="center">
    <form action="" method="POST" name="google-maps-request" id="google-maps-request">
      <label for="location">Enter your suburb/address:</label>
      <input type="text" name="location" id="location">
      <input type="submit" name="submit" value="Find nearest supermarkets">
    </form>
  </div>
    <?php
      //check if user has submitted the form
      if(isset($_POST['location'])){
      //check if location is empty
        if(!empty($_POST['location'])){
          //submit a HTTP request to the Google Maps API
          showMap($_POST['location']);
        }
      }
    ?>
  </body>
</html>
