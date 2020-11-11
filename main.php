<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
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
            <li id="nav-item" class="active"><a href="/main.php">Home</a></li>
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
      <h2>Meal Plans:</h2>
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
