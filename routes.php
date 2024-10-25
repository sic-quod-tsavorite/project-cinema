<?php

require_once __DIR__ . '/router.php';

get('/project-cinema', 'views/index.php');

get('/project-cinema/film-page', 'views/film-page.php');

get('/project-cinema/new-user', 'views/newuser.php');
post('/project-cinema/new-user', 'views/newuser.php');

get('/project-cinema/login', 'views/login.php');
post('/project-cinema/login', 'views/login.php');

get('/project-cinema/logout', 'views/logout.php');
post('/project-cinema/login/$logout=1', 'views/logout.php');


//Error 404 page, this needs to be last
any('/404', 'constants/error404.php');
