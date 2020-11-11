<?php

/**
 * This is an example of a front controller for a flat file PHP site. Using a
 * Static list provides security against URL injection by default. See README.md
 * for more examples.
 */
# [START gae_simple_front_controller]
switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/':
        require 'login.php';
        break;
    case '/login.php':
        require 'login.php';
        break;
    case '/recipes.php':
        require 'recipes.php';
        break;
    case '/main.php':
        require 'main.php';
        break;
    case '/account.php':
        require 'account.php';
        break;
    case '/password.php':
        require 'password.php';
        break;
    case '/recipe-item.php':
        require 'recipe-item.php';
        break;
    case '/query.php':
        require 'query.php';
        break;
    default:
        http_response_code(404);
        exit('Not Found');
}
# [END gae_simple_front_controller]
