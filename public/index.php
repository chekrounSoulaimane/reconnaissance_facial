<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Finder\Finder;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);


// user photos folder's name
function getUsernames() {

    $finder = new Finder();

    $dirs = iterator_to_array($finder->directories()->in('./build/img/photos/'), true);

    $usernames = array();

    foreach($dirs as $element) {
        array_push($usernames, explode('\\', $element)[1]);
    }
    return $usernames;
}


function createUtilisateurFolderImages($cin) {

    $finder = new Finder();
    $i = 0;

    $imgs = iterator_to_array($finder->files()->in('./build/img/photos/'.$cin), true);

    foreach($imgs as $element) {
        $i++;
        rename($element, './build/img/photos/'.$cin.'/'.$i.'.jpg');
    }
    
}