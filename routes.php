<?php

require_once __DIR__ . '/router.php';

get('/project-cinema', 'views/index.php');

get('/project-cinema/empty-page', 'views/empty-page.php');


//Error 404 page, this needs to be last
any('/404', 'views/error404.php');
