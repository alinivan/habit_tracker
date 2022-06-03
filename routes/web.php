<?php

use Core\Routing\Route;

$route = new Route();

/* ------------ WEB ------------ */

/* --- PAGES --- */
$route->get('/', 'PageController:index');
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


// Not found
$route->notFound('PageController:notFound');

$route->dissolve();