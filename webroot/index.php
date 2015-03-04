<?php
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 

require __DIR__.'/config_with_app.php'; 
session_start();
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');
$app = new \Anax\MVC\CApplicationBasic($di);
// Create services and inject into the app. 

//Create specific services for this app
$di->set('CommentsController', function() use ($di) {
    $controller = new Anax\Comments\CommentsController();
    $controller->setDI($di);
    return $controller;	
});

$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

$di->set('TagsController', function() use ($di) {
    $controller = new \Anax\Tags\TagsController();
    $controller->setDI($di);
    return $controller;
});

$di->set('QuestionsController', function() use ($di) {
    $controller = new \Anax\Questions\QuestionsController();
    $controller->setDI($di);
    return $controller;
});

$di->set('Tags2questionController', function() use ($di) {
    $controller = new \Anax\Tags2question\Tags2questionController();
    $controller->setDI($di);
    return $controller;
});

$di->set('AnswersController', function() use ($di) {
    $controller = new \Anax\Answers\AnswersController();
    $controller->setDI($di);
    return $controller;
});

$di->set('Answers2questionController', function() use ($di) {
    $controller = new \Anax\Answers2question\Answers2questionController();
    $controller->setDI($di);
    return $controller;
});

$di->set('DbtablesController', function() use ($di) {
    $controll = new \Roka\Dbtables\DbtablesController();
    $controll->setDI($di);
    return $controll;
});


$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
  // $db->setOptions(require ANAX_APP_PATH . 'config/config_mysql.php');
	 $db->setOptions(require ANAX_APP_PATH . 'config/config_sqlite.php');
    $db->connect();
    return $db;
});

	
	$di->set('HomeController', function() use ($di) {
    $controll = new \ANAX\Home\HomeController();
    $controll->setDI($di);
    return $controll;
});


$di->setShared('form', '\Mos\HTMLForm\CForm');


//Route for Homepage
$app->router->add('', function() use ($app) {
    $app->HomeController->indexAction();
});  


$app->router->add('about', function() use ($app) {
    $app->theme->setTitle("About");
    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    $byline='';
    $app->views->add('me/page', [
        'content' => $content,
        'byline' => $byline,
    ]);
});



$app->router->handle();
$app->theme->render();