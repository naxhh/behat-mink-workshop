<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));
function userShouldBeLogin($app) {
    if (! ($user = $app['session']->get('user')) ) {
        return $app->redirect('/login');
    }
    return $user;
}

$app->get('/', function () use ($app) {
    $obj = new \Kitty\DataSource;

    $user = array( 'username' => 'kitty69' );
    $user = $obj->get( $user['username'] );

    $kitties = $obj->getList();
    unset($kitties[$user['name']]);

    return $app['twig']->render('index.twig', array( 'user' => $user, 'kitties' => $kitties ));
})
->bind('homepage')
;

$app->get('/kitty/{name}', function ($name) use ($app) {
    $user = array( 'username' => 'kitty69' );
    $obj = new \Kitty\DataSource;
    $kitty = $obj->get($name);

    if (!$kitty)
    {
        throw new Exception('404');
    }

    return $app['twig']->render('profile.twig', array('user' => $user, 'kitty' => $kitty));
})
->bind('profile')
;

$app->get('/kitty/{name}/miau', function ($name) use($app) {
    $user = array( 'username' => 'kitty69' );
    $obj = new \Kitty\DataSource;
    $kitty = $obj->get($name);

    if (!$kitty)
    {
        return $app->json( array( 'msg' => 'Woops! something went wrong' ), 404);
    }

    // using $name here can cause a bug.
    $kitty['interactions']['miau'][$user['username']] = time();
    $obj->set( $kitty );

    return $app->json( array( 'msg' => 'You said hi! to ' . $name . ' luck in this adventure, maybe you want to catch some butterfly for him/her') );
})
->bind('miau')
;

$app->get('/kitty/{name}/prrr', function($name) use($app) {
    $user = array( 'username' => 'kitty69' );
    $obj = new \Kitty\DataSource;
    $kitty = $obj->get($name);

    if (!$kitty)
    {
        return $app->json( array( 'msg' => 'Woops! something went wrong' ), 404);
    }

    $kitty['interactions']['prrr'][$user['username']] = time();
    $obj->set( $kitty );

    return $app->json( array( 'msg' => "You contacted with this pretty kitty! For sure you will have luck and win an extra life here, if you know what I mean....") );
})
->bind('prrr')
;

$app->get('/kitty/{name}/fzzzz', function($name) use($app) {
    $user = array( 'username' => 'kitty69' );
    $obj = new \Kitty\DataSource;
    $kitty = $obj->get($name);

    if (!$kitty)
    {
        return $app->json( array( 'msg' => 'Woops! something went wrong' ), 404);
    }

    $kitty['interactions']['fzzzz'][$user['username']] = time();
    $obj->set( $kitty );

    return $app->json( array( 'msg' => "oh! I can't belive we have a mice in our site! we will take care of him for sure. ") );
})
->bind('fzzzz') //setting here prrr makes prrr url not work :D
;

// API SYSTEM!
$app->get('/api/cats', function() use($app) {
    $data_soruce = new \Kitty\DataSource;
    $kitties = $data_soruce->getList();

    return $app->json($kitties);
})
;

$app->get('/api/cats/{name}', function($name) use($app) {
    $data_soruce = new \Kitty\DataSource;
    $kitty = $data_soruce->get($name);
    $return_code = 200;

    if (!$kitty)
    {
        $kitty = array();
        $return_code = 404;
    }

    return $app->json($kitty, $return_code);
})
;

$app->get('/api/cats/{name}/images', function($name) use($app) {
    $data_soruce = new \Kitty\DataSource;
    $kitty = $data_soruce->get($name);

    if (!$kitty)
    {
        return $app->json(array(), 404);
    }

    return $app->json($kitty['gallery']);
})
;