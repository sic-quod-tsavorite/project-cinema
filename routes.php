<?php

require_once __DIR__ . '/router.php';

// Front page
get('/project-cinema', 'views/index.php');

// Dynamic page for tags make $id variable
get('/project-cinema/tag/id/$id', 'views/tag-page.php');

// Dynamic page for movies make $id variable
get('/project-cinema/movie/id/$id', 'views/film-page.php');

// Dynamic page for bookings make $id variable
get('/project-cinema/booking/showing/$id', 'views/booking_simple.php');
post('/project-cinema/booking/showing/$id', 'views/booking_simple.php');

// Login page and post action for login
get('/project-cinema/login', 'views/login.php');
post('/project-cinema/login', 'views/login.php');
post('/project-cinema/login?signup=success', 'views/login.php');

// Signup "page" and post action for signup
get('/project-cinema/logout', 'views/content/signout.php');
post('/project-cinema', 'views/content/signout.php');

// Account page for users
get('/project-cinema/account-page', 'views/account.php');
post('/project-cinema/account-page', 'views/account.php');
post('/project-cinema/account-page?update=pfp', 'views/account.php');
post('/project-cinema/account-page?update=psw', 'views/account.php');
post('/project-cinema/login?user=deleted', 'views/login.php');

// Admin board for admins and post actions
get('/project-cinema/admin-board', 'views/dashboard.php');
post('/project-cinema/admin-board', 'views/dashboard.php');

//Error 404 page, this needs to be last
any('/404', 'constants/error404.php');
