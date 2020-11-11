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
        <title>Cloud Comp A2</title>
        <link rel="stylesheet" type="text/css" href="/stylesheets/style.css">
    </head>
    <body>
    <h1>Meal Planner</h1>
        <form action="" method="POST" name="change" id="change">
        <div class="heading"><h2>Change Password</h2></div>
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username"><br>

            <label for="password">New Password:</label><br>
            <input type="password" name="password" id="password"><br>

            <label for="confirm_password">Confirm new Password:</label><br>
            <input type="password" name="confirm_password" id="confirm_password"><br>

            <input type="reset" value="Clear">
            <input type="submit" value="Change Password">
        </form>
    </body>
</html>
<?php
  $valid = true;
  //check if form is submitted
  if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    //check if fields are empty
    if(empty($username) || empty($password) || empty($confirm_password)){
      echo '<script>alert("Fields cannot be empty. Try Again.");</script>';
      $valid = false;
    }

    //check if username matches
    $checkAccount = $datastore->query()
        ->kind('user')
        ->filter('username', '=', $username);
    $result = $datastore->runQuery($checkAccount);
    $userID = array();
    //retreive accounts that match
    foreach($result as $entity){
      $userID = $entity->key()->pathEndIdentifier();
    }
    if(empty($userID)){
      echo '<script>alert("Username does not exist. Try Again.");</script>';
      $valid = false;
    }

    //check if passwords match
    if($password != $confirm_password){
      echo '<script>alert("Passwords do not match. Try Again.");</script>';
      $valid = false;
    }

    //update password to the account
    if($valid == true){
      $transaction = $datastore->transaction();
      $key = $datastore->key('user', $userID);
      $retrieveID = $datastore->lookup($key);
      $retrieveID['password'] = $password;
      $transaction->update($retrieveID);
      $transaction->commit();
      //redirect to login page and display message
      echo '<script>
        alert("Password changed successfully.");
        window.location = "/";
        </script>';
    }
  }
?>
