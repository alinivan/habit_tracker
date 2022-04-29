<?php


use Core\Route;

Route::get('/', 'DashboardController:test');


//Route::get('/login', 'DashboardController:test');

/*

/
-- daca este logat, va fi pagina cu dashboard-ul DashboardController:index
-- daca nu este logat, redirect catre /login

/login
-- daca este logat, redirect catre "/"
-- daca nu este logat, formular de login UserController:login

/logout
-- daca este logat, delogarea va fi dintr-un serviciu(?), dupa care redirect catre /login
-- daca nu este logat, redirect catre /login

/habits
-- lista cu habit-uri, si butoane de Add, Edit, Delete

/

 */