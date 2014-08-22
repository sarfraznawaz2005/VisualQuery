<?php
Flight::route('GET /', array('Dashboard', 'index'));
Flight::route('GET /home', array('Dashboard', 'index'));
Flight::route('GET /login', array('Login', 'index'));
Flight::route('POST /login', array('Login', 'loginuser'));
Flight::route('GET /login/logout', array('Login', 'logout'));
Flight::route('GET /dashboard', array('Dashboard', 'index'));
Flight::route('GET /export/csv', array('Export', 'csv'));
Flight::route('GET /export/excel', array('Export', 'excel'));
Flight::route('GET /table/[a-zA-Z0-9-_?+]+', array('Table', 'index'));
Flight::route('POST /table/[a-zA-Z0-9-_?+]+', array('Table', 'runquery'));
Flight::route('POST /ajax/[a-zA-Z0-9-_?+]+', array('Ajax', Flight::get('lastSegment')));