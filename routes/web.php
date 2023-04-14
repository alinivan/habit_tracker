<?php

use Core\Routing\Route;

$route = new Route();

/* ------------ WEB ------------ */

/* --- PAGES --- */
$route->get('/', 'PageController:homePage');
// Login
$route->get('/login', 'UserController:loginPage');
$route->post('/login', 'UserController:login');
// Register
$route->get('/register', 'UserController:registerPage');
$route->post('/register', 'UserController:register');
// Logout
$route->get('/logout', 'UserController:logout');


/* ------------ APP ------------ */

$route->get('/dashboard', 'DashboardController:index');

/* --- HABITS --- */
// display list of all habits
$route->get('/habits', 'HabitController:index');
// show form to make new habits
$route->get('/habit/new', 'HabitController:new');
// add new habit to database, then redirect
$route->post('/habits', 'HabitController:create');
// show info about one habit
$route->get('/habit/{id}', 'HabitController:show');
// show edit form for one habit
$route->get('/habit/{id}/edit', 'HabitController:edit');
// update a particular habit
$route->post('/habits/{id}', 'HabitController:update');
// delete a particular habit
$route->get('/habits/{id}/delete', 'HabitController:destroy');


/* --- TRACKER --- */
// index
$route->get('/tracker', 'TrackerController:index');
// form for insert
$route->get('/tracker/new', 'TrackerController:new');
// insert
$route->post('/tracker/create', 'TrackerController:create');

$route->post('/tracker/fast-create', 'TrackerController:fastCreate');

$route->post('/tracker/upload', 'TrackerController:upload');


/* --- CATEGORIES --- */
// index
$route->get('/categories', 'CategoryController:index');
// form for insert
$route->get('/category/new', 'CategoryController:new');
// insert
$route->post('/categories', 'CategoryController:create');
// show edit form
$route->get('/category/{id}/edit', 'CategoryController:edit');
// update
$route->post('/categories/{id}', 'CategoryController:update');
// delete a particular habit
$route->get('/categories/{id}/delete', 'CategoryController:destroy');


$route->get('/routine', 'RoutineController:index');


// Not found
$route->notFound('PageController:notFound');

$route->dissolve();