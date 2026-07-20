<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/operateur/gain', 'OperateurController::getSituationGain');
$routes->get('/operateur/clients', 'OperateurController::getAllClients');
$routes->get('/operateur/situationClient/(:num)', 'OperateurController::situationClient/$1');
$routes->get('/client/situation/(:num)', 'ClientController::situation/$1');
