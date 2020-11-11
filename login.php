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
?>
<html>
    <head>
        <title>RMIT Cloud Comp A2</title>
        <link rel="stylesheet" type="text/css" href="/stylesheets/style.css">
    </head>
    <body>
      <h1>Meal Planner</h1>
      <form action="" method="POST" name="login" id="login">
      <div class="heading"><h2>Login:</h2></div>
        <label for="username">Username:</label><br>
        <input type="text" name="username" id="username"><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password"><br>

        <input type="submit" name="submit" id="submit" value="Login">
        <p>
      Don't have an account? Create a free account <a href="/account.php">here!</a><br>
      Forgot password? <a href="/password.php">I forgot my password</a>
      </p>
      </form>
    </body>
</html>
<?php
    $valid = true;
    //check if form is submitted
    if(isset($_POST['username']) && isset($_POST['password'])){
      $username = $_POST['username'];
      $password = $_POST['password'];
      //check if fields are empty
      if(empty($username) || empty($password)){
        echo '<script>alert("Fields cannot be empty. Try Again.");</script>';
        $valid = false;
      }

      //check if account exists
      //check username
      $checkAccount = $datastore->query()
          ->kind('user')
          ->filter('username', '=', $username);
      $result = $datastore->runQuery($checkAccount);
      $userID = array(); //retrieves 1 or 0 accounts
      //retreive accounts that match
      foreach($result as $entity){
        $userID = $entity->key()->pathEndIdentifier();
      }
      if(empty($userID)){
        //if the account doesn't exist, display error message and prevent login
        echo '<script>alert("Username does not exist. Try Again.");</script>';
        $valid = false;
      } else {
        //if an account exists, retrieve the account selected
        $key = $datastore->key('user', $userID);
        $retrieveID = $datastore->lookup($key);
        //check if password matches
        if($password != $retrieveID['password']){
          echo '<script>alert("Password entered was incorrect. Try Again.");</script>';
          $valid = false;
        }
      }
      
      //check if login credentials are correct
      if($valid == true){
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        echo '<script>window.location="/main.php"</script>';
      }
    }
?>
