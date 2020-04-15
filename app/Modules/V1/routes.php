<?php
$router->group(['prefix' => 's', 'namespace' => 'App\Modules\V1\File\Controllers'], function() use($router) {
    $router->get('/file/{name}', 'Files@read');
});

$router->group(['prefix' => 'v1','middleware' => 'api.auth'],  function () use ($router) {

    $router->get('/', function () use ($router) {
        return array(
            'status'=>200,
            'message'=>'Welcome to Absensi API',
            'data' => array('version'=>'1.0')
        );
    });

    //Not login
    //Not use token
    $router->group(['prefix' => 'user', 'namespace' => 'App\Modules\V1\User\Controllers'], function() use($router) {
        $router->post('/auth/login', 'Auth@login');
        $router->post('/register', 'User@register');
        $router->post('/reset-password', 'User@reset');
        $router->get('/me', 'User@me');

    });
    //end no use token
    $router->group(['prefix' => 'user', 'namespace' => 'App\Modules\V1\User\Controllers','middleware' => 'jwt.auth'], function() use($router) {
        $router->get('/me', 'User@me');
        $router->put('/change-password', 'User@changePassword');
        $router->get('/kinerja', 'Kinerjaharian@list');
        $router->post('/kinerja', 'Kinerjaharian@create');
        $router->put('/kinerja/{id}', 'Kinerjaharian@update');
        $router->delete('/kinerja/{id}', 'Kinerjaharian@delete');


    });
    $router->group(['prefix' => 'absensi', 'namespace' => 'App\Modules\V1\Absensi\Controllers','middleware' => 'jwt.auth'], function() use($router) {
        $router->get('/list', 'Absensi@list');
        $router->get('/checkpoint', 'Absensi@checkpoint');
        $router->post('/punchin', 'Absensi@punchin');
        $router->put('/punchout', 'Absensi@punchout');
        $router->get('/export', 'Absensi@export');
    });

    
    

});