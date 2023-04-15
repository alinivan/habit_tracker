<?php

use Core\Routing\Route;

$route = new Route();

/* ------------ WEB ------------ */

/* --- PAGES --- */
$route->get('/', 'PageController:homePage');

$route->get('/login', 'UserController:loginPage');
$route->post('/login', 'UserController:login');

$route->get('/register', 'UserController:registerPage');
$route->post('/register', 'UserController:register');

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
$route->get('/tracker', 'TrackerController:index');
$route->get('/tracker/new', 'TrackerController:new');
$route->post('/tracker/create', 'TrackerController:create');
$route->post('/tracker/fast-create', 'TrackerController:fastCreate');
$route->post('/tracker/upload', 'TrackerController:upload');

/* --- CATEGORIES --- */
$route->get('/categories', 'CategoryController:index');
$route->get('/category/new', 'CategoryController:new');
$route->post('/categories', 'CategoryController:create');
$route->get('/category/{id}/edit', 'CategoryController:edit');
$route->post('/categories/{id}', 'CategoryController:update');
$route->get('/categories/{id}/delete', 'CategoryController:destroy');

/* --- TASKS --- */
$route->get('/tasks', 'TaskController:index');
$route->get('/task/new', 'TaskController:new');
$route->post('/tasks', 'TaskController:create');
$route->get('/task/{id}', 'TaskController:show');
$route->get('/task/{id}/edit', 'TaskController:edit');
$route->post('/tasks/{id}', 'TaskController:update');
$route->get('/tasks/{id}/delete', 'TaskController:destroy');

$route->get('/routine', 'RoutineController:index');
//$route->get('/routine/{id}/edit', 'RoutineController:edit');

/* --- NOT FOUND --- */
$route->notFound('PageController:notFound');

$route->dissolve();