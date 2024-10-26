<?php

require_once __DIR__ . '/router.php';

get('/project-cinema', 'views/index.php');

get('/project-cinema/film-page', 'views/film-page.php');

get('/project-cinema/login', 'views/login.php');
post('/project-cinema/login', 'views/login.php');

get('/project-cinema/logout', 'constants/signout.php');
post('/project-cinema', 'constants/signout.php');

get('/project-cinema/account-page', 'views/account.php');

get('/project-cinema/admin-board', 'views/dashboard.php');
post('/project-cinema/admin-board', 'views/dashboard.php');


//Error 404 page, this needs to be last
any('/404', 'constants/error404.php');
