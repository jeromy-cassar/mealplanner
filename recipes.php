<?php
    session_start();
    require __DIR__ . '/vendor/autoload.php';
    $projectId = 'mealplanner-cc-2020'; //change when merging to master
    putenv('GOOGLE_APPLICATION_CREDENTIALS=MealPlanner cc 2020-472d0cbbc34e.json');
    //setup bigquery
    use\Google\Cloud\BigQuery\BigQueryClient;
    use\Google\Cloud\Core\ExponentialBackoff;
    //create bigquery object
    $bigQuery = new BigQueryClient([
        'projectID' => $projectId
    ]);
    //$_SESSION['username'] = "Jeromy";
    //print data and shape/structure of data:
    function preShow( $arr, $returnAsString=false ) {
        $ret  = '<pre>' . print_r($arr, true) . '</pre>';
        if ($returnAsString)
            return $ret;
        else
            echo $ret;
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
        <p class="greeting">Welcome, <span><?=$_SESSION['username']?></span></p>
    </header>
    <nav>
        <ul>
            <li id="nav-item"><a href="/main.php">Home</a></li>
            <li id="nav-item" class="active"><a href="/recipes.php">Recipes</a></li>
            <li id="nav-item"><a href="/meal-plan.php">Meal Plan</a></li>
            <li id="nav-item">
              <form action="/login.php" method="POST" id="logout-form">
                <input type="submit" id="logout-btn" name="logout-btn" value="Log Out">
              </form>
            </li>
        </ul>
    </nav>
    <body class="body">
        <div class="recipe-container">
        <h2>Recipes</h2>
            <div class="recipe-search">
                <form action="" method="GET">
                    <table>
                        <tr colspan=3>
                            <td>
                                <input type="radio" checked id="keyword" name="search" value="keyword">
                                <label for="keyword">Search By Keyword</label>
                            </td>
                            <td>
                                <input type="radio" id="ingredient" name="search" value="ingredient">
                                <label for="ingredient">Search By Ingredient</label>
                            </td>
                            <td>
                                <label for="difficulty">Difficulty: </label>
                                <select name="difficulty" id="difficulty">
                                    <option value="any" id="any" name="any">ANY</option>
                                    <option value="easy" id="easy">EASY</option>
                                    <option value="amateur" id="amateur">AMATEUR</option>
                                    <option value="hard" id="hard">HARD</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="search-query" id="search-query" placeholder="Search" required>
                                <input type="submit" id="search-btn" value="Search">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <h3>Results:</h3>
            <div class="recipe-result">
            <table>
                <?php
                //check if form has been submitted
                if(isset($_GET['search-query'])){
                    $search = $_GET['search-query'];
                    $difficulty = $_GET['difficulty'];
                    //check which search type has been selected
                    //--keyword
                    if($_GET['search'] == "keyword"){
                        //check if user has filtered difficulty
                        if($_GET['difficulty'] == "any"){
                            $query =
                            "SELECT *
                            FROM recipes.recipe_data
                            WHERE recipe_name LIKE '%$search%'
                            ORDER BY recipe_name";
                            //echo $query;
                             $jobConfig = $bigQuery->query($query);
                             $job = $bigQuery->startQuery($jobConfig);

                             $queryResults = $job->queryResults();

                             foreach($queryResults as $row){
                                 printf('
                                 <tr>
                                     <td class="recipe-item">
                                         <div class="food-img">
                                             ID: '.$row['recipe_id'].'
                                         </div>
                                         <div class="food-name">
                                            <form action="/recipe-item.php" method="GET">
                                                <input type="submit" name="recipe_request" id="recipe_request" value="'.$row['recipe_name'].'">
                                                <input type="hidden" name="recipe_name" id="recipe_name" value="'.$row['recipe_name'].'"> 
                                            </form>
                                         </div>
                                         <div class="food-desc">
                                             '.$row['ingredients'].'
                                         </div>
                                         <div class="food-difficulty">
                                             Difficulty: '.$row['difficulty'].'
                                         </div>
                                         <div class="food-preptime">
                                             Prep-time: '.$row['prep_time'].'min
                                         </div>
                                     </td>
                                 </tr>');
                             }
                        }
                        else{
                            $query =
                            "SELECT *
                            FROM recipes.recipe_data
                            WHERE recipe_name LIKE '%$search%' AND difficulty LIKE '%$difficulty%'
                            ORDER BY recipe_name";
                            //echo $query;
                            $jobConfig = $bigQuery->query($query);
                            $job = $bigQuery->startQuery($jobConfig);

                            $queryResults = $job->queryResults();

                            foreach($queryResults as $row){
                                printf('
                                <tr>
                                    <td class="recipe-item">
                                        <div class="food-img">
                                            ID: '.$row['recipe_id'].'
                                        </div>
                                        <div class="food-name">
                                            '.$row['recipe_name'].'
                                        </div>
                                        <div class="food-desc">
                                            '.$row['ingredients'].'
                                        </div>
                                        <div class="food-difficulty">
                                            Difficulty: '.$row['difficulty'].'
                                        </div>
                                        <div class="food-preptime">
                                            Prep-time: '.$row['prep_time'].'min
                                        </div>
                                    </td>
                                </tr>');
                            }
                        }
                    }
                    //--ingredient
                    elseif($_GET['search'] == "ingredient"){
                        //check if user has filtered difficulty
                        if($_GET['difficulty'] == "any"){
                            $query =
                            "SELECT *
                            FROM recipes.recipe_data
                            WHERE ingredients LIKE '%$search%'
                            ORDER BY recipe_name";
                            //echo $query;
                            $jobConfig = $bigQuery->query($query);
                            $job = $bigQuery->startQuery($jobConfig);

                            $queryResults = $job->queryResults();

                            foreach($queryResults as $row){
                                printf('
                                <tr>
                                    <td class="recipe-item">
                                        <div class="food-img">
                                            ID: '.$row['recipe_id'].'
                                        </div>
                                        <div class="food-name">
                                            '.$row['recipe_name'].'
                                        </div>
                                        <div class="food-desc">
                                            '.$row['ingredients'].'
                                        </div>
                                        <div class="food-difficulty">
                                            Difficulty: '.$row['difficulty'].'
                                        </div>
                                        <div class="food-preptime">
                                            Prep-time: '.$row['prep_time'].'min
                                        </div>
                                    </td>
                                </tr>');
                            }
                        }
                        else{
                            $query =
                            "SELECT *
                            FROM recipes.recipe_data
                            WHERE ingredients LIKE '%$search%' AND difficulty LIKE '%$difficulty%'
                            ORDER BY recipe_name";
                            //echo $query;
                            $jobConfig = $bigQuery->query($query);
                            $job = $bigQuery->startQuery($jobConfig);

                            $queryResults = $job->queryResults();

                            foreach($queryResults as $row){
                                printf('
                                <tr>
                                    <td class="recipe-item">
                                        <div class="food-img">
                                            ID: '.$row['recipe_id'].'
                                        </div>
                                        <div class="food-name">
                                            '.$row['recipe_name'].'
                                        </div>
                                        <div class="food-desc">
                                            '.$row['ingredients'].'
                                        </div>
                                        <div class="food-difficulty">
                                            Difficulty: '.$row['difficulty'].'
                                        </div>
                                        <div class="food-preptime">
                                            Prep-time: '.$row['prep_time'].'min
                                        </div>
                                    </td>
                                </tr>');
                            }
                        }
                    }
                }
                else{
                    echo '<script>alert("Please enter a keyword to search for recipes");</script>';
                }
                ?>
                </table>
            </div>
        </div>
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
