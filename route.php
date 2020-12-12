<?php

// URL
$_uriRouteHtaccess = $_SERVER['DOCUMENT_ROOT'] . "/" . trim($_GET['url_route_htaccess']);
unset($_GET['url_route_htaccess'], $_REQUEST['url_route_htaccess']);

// Verifica rota
if (!is_file($_uriRouteHtaccess)) {
    $_uriRouteHtaccess .= (substr($_uriRouteHtaccess, -1) == '/') ? '' : '/';
    if (file_exists($_uriRouteHtaccess . 'index.php')) {
        $_uriRouteHtaccess .= 'index.php';
    } else {
        throw new Exception('Not found ' . $_uriRouteHtaccess . 'index.php', 404);
    }
} else if (!file_exists($_uriRouteHtaccess)) {
    header('Location: /index.php');
    exit;
}

if (pathinfo($_uriRouteHtaccess)['extension'] == 'php') {

    // Variáveis a partir do .env
    $_fileEnv = ".env";
    if (!file_exists($_fileEnv)) {
        echo "O arquivo \".env\" não foi encontrado.";
        exit;
    }
    $_env = parse_ini_file($_fileEnv);
    foreach ($_env as $key => $value) {
        putenv($key . "=" . $value);
    }
    unset($_env);

    // DEBUG
    if (getenv("DEBUG")) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(getenv("ERROR_REPORTING") ?: E_ERROR);
    }
}

require_once($_uriRouteHtaccess);
