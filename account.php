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
        <form action="" method="POST" name="create" id="create">
        <div class="heading"><h2>Create Account:</h2></div>
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username"><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password"><br>

            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" name="confirm_password" id="confirm_password"><br>

            <input type="reset" value="Clear">
            <input type="submit" value="Create Account">
        </form>
    </body>
</html>
<?php
   //check if form has been submitted
   $valid = true;
    if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
        //collect POST data
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPass = $_POST['confirm_password'];

        //check if values are empty
        if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirm_password'])){
            echo '<script>alert("All fields are required. Try Again.");</script>';
            $valid = false;
        }
        //check if passwords match
        if($_POST['password'] != $_POST['confirm_password']){
            echo '<script>alert("Passwords do not match. Try Again.");</script>';
            $valid = false;
        }
        //check if username exists
        //get datastore accounts and encode it
        $checkAccount = $datastore->query()
            ->kind('user')
            ->filter('username', '=', $username);
        $result = $datastore->runQuery($checkAccount);

        $userID = array();
        //retreive accounts that match
        foreach($result as $entity){
          $userID = $entity->key()->pathEndIdentifier();
        }
        //if the results have a matching username, display message and prevent user from creating an account
        if(!empty($userID)){
          echo '<script>alert("Username already exists. Try Again.");</script>';
          $valid = false;
        }
        //if credentials are correct, create the new account and redirect user to the login page
        if($valid == true) {
            //add new user to datastore
            $newUser = $datastore->entity('user', [
                'username' => $username,
                'password' => $password
            ]);
            $datastore->insert($newUser);
            //display message & redirect to login page
            echo '<script> alert("Your account has been created successfully"); window.location="/login.php"; </script>';
        }
    }
?>
