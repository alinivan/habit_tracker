<?php

use Core\Routing\Route;

Route::get('/', 'DashboardController:test');

// Login
Route::get('/login', 'UserController:loginPage');
Route::post('/login', 'UserController:login');

// Register
Route::get('/register', 'UserController:registerPage');
Route::post('/register', 'UserController:register');

// Logout
Route::get('/logout', 'UserController:logout');

//Route::broken();