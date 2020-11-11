<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
putenv('GOOGLE_APPLICATION_CREDENTIALS=MealPlanner cc 2020-472d0cbbc34e.json');
//import GCS client library
use Google\Cloud\Storage\StorageClient;

//instantiate GCS client and register stream wrapper
$storage = new StorageClient();
$storage->registerStreamWrapper();

//get name of recipe and image file name
$recipeName = strtoupper($_GET['recipe_name']);
$name = str_replace(" ","-",$_GET['recipe_name']); //find spaces and replace with hyphen

/* 
 * showMap() function
 * @param $location - user's entered location from the form
 * @return HTML - Map (Google Maps API)
 */
function showMap($location){
  $map = <<<"MAP"
  <iframe
      width="450"
      height="300"
      frameborder="0" style="border:0"
      src="https://www.google.com/maps/embed/v1/search?key=AIzaSyAW8SF3ZkODnax_QoAshoj0WSPIdosBT5U&q=supermarkets+near+$location">
    </iframe>
MAP;
  echo $map;
}
?>
<html class="html">
  <head>
    <title>RMIT Cloud Comp A2</title>
    <link rel="stylesheet" href="/stylesheets/style.css">
  </head>
  <div class="wrapper">
    <header>
        <h1>Welcome to Meal Planner!</h1>
    </header>
    <nav>
        <ul>
            <li id="nav-item"><a href="/main.php">Home</a></li>
            <li id="nav-item"><a href="/recipes.php">Recipes</a></li>
            <li id="nav-item"><a href="/meal-plan.php">Meal Plan</a></li>
            <li id="nav-item">
              <form action="/login.php" method="POST" id="logout-form">
                <input type="submit" id="logout-btn" name="logout-btn" value="Log Out">
              </form>
            </li>
        </ul>
    </nav>
    <body class="body">
      <!--Retrieve GET data and print the recipe's name-->
      <div class="recipe-content">
        <div class="recipe-img">
          <?php echo '<img src="https://storage.cloud.google.com/recipe_content/'.$name.'.jpg" width="200px" height="200px">'; ?>
        </div>
        <div class="recipe-name">
          <h2><?= $recipeName ?></h2>
        </div>
        <div class="recipe-file">
          <?php
            //read the text file and print the data inside
            $contents = explode(";",file_get_contents('gs://recipe_content/'.$name.'.txt'));
            $length = count($contents);
            for($x=0;$x<$length;$x++){
              echo $contents[$x]."<br />";
            }
          ?>
        </div>
        <div class="g-maps">
          <form action="" method="POST" name="google-maps-request" id="google-maps-request">
            <label for="location">Enter your suburb/address:</label>
            <input type="text" name="location" id="location">
            <input type="submit" name="submit" value="Find nearest supermarkets">
          </form>
          <?php
            //check if user has submitted the form
            if(isset($_POST['location'])){
              //check if location is not empty
              if(!empty($_POST['location'])){
                //call showMap() function which sends a HTTP request to the Google Maps API
                showMap($_POST['location']);
              }
            }
          ?>
        </div>
      </div>
      <br>
    </body>
    <div class="filler"></div>
  <footer>
     <p class="footer-content">
      Disclaimer: This project has been created for the Cloud Computing subject for assignment 2. <br>
      Made by: Ryan Harris and Jeromy Cassar from RMIT University 2020.
     </p>
  </footer>
  </div>
</html>
<?php
  //check if user wants to sign out
  if(isset($_POST['logout-btn'])){
    //unset session
    unset($_SESSION);
    //redirect user to login page
    echo '<script>window.location="/login.php"</script>';
    unset($_SESSION); //remove login credentials from SESSION
    exit;
  }
?>
