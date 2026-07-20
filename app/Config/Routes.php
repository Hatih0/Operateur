<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
*/
$routes->get('/', 'LoginController::index');

$routes->get('/login_operateur', 'LoginController::LoginOperateur');
$routes->post('/checkOperateur', 'LoginController::checkOperateur');

$routes->get('/login_client', 'LoginController::index');
$routes->post('/login', 'LoginController::checkClient');
$routes->get('/ClientHome', 'ClientController::index');
$routes->get('/logout', 'LoginController::logout');

$routes->get('/ajouter_prefixe', 'PrefixeController::create' , ['filter' => 'authOperateur']);
$routes->post('/ajouter_prefixe', 'PrefixeController::store' , ['filter' => 'authOperateur']);
$routes->get('/liste_prefixe', 'PrefixeController::liste', ['filter' => 'authOperateur']);
$routes->get('/modifier_prefixe/(:num)', 'PrefixeController::edit/$1', ['filter' => 'authOperateur']);
$routes->post('/modifier_prefixe/(:num)', 'PrefixeController::update/$1', ['filter' => 'authOperateur']);
$routes->get('/supprimer_prefixe/(:num)', 'PrefixeController::delete/$1', ['filter' => 'authOperateur']);

$routes->get('/ajouter_configuration', 'ConfigurationController::create', ['filter' => 'authOperateur']);
$routes->post('/ajouter_configuration', 'ConfigurationController::store', ['filter' => 'authOperateur']);

$routes->get('/liste_configuration', 'ConfigurationController::liste', ['filter' => 'authOperateur']);
$routes->get('/modifier_configuration/(:num)', 'ConfigurationController::edit/$1', ['filter' => 'authOperateur']);
$routes->post('/modifier_configuration/(:num)', 'ConfigurationController::update/$1', ['filter' => 'authOperateur']);
$routes->get('/supprimer_configuration/(:num)', 'ConfigurationController::delete/$1', ['filter' => 'authOperateur']);

$routes->get('/ajouter_type_operation', 'TypeOperationController::create', ['filter' => 'authOperateur']);
$routes->post('/ajouter_type_operation', 'TypeOperationController::store', ['filter' => 'authOperateur']);
$routes->get('/liste_type_operation', 'TypeOperationController::liste', ['filter' => 'authOperateur']);
$routes->get('/modifier_type_operation/(:num)', 'TypeOperationController::edit/$1', ['filter' => 'authOperateur']);
$routes->post('/modifier_type_operation/(:num)', 'TypeOperationController::update/$1', ['filter' => 'authOperateur']);
$routes->get('/supprimer_type_operation/(:num)', 'TypeOperationController::delete/$1', ['filter' => 'authOperateur']);

$routes->get('/operateur/gain', 'OperateurController::getSituationGain' , ['filter' => 'authOperateur']);
$routes->get('/operateur/clients', 'OperateurController::getAllClients' , ['filter' => 'authOperateur']);
$routes->get('/operateur/situationClient/(:num)', 'OperateurController::situationClient/$1', ['filter' => 'authOperateur']);

$routes->get('/client/situation', 'ClientController::situation', ['filter' => 'authClient']);
$routes->get('client/formulaire/depot','ClientController::formulaire/depot', ['filter' => 'authClient']);
$routes->get('client/formulaire/retrait','ClientController::formulaire/retrait', ['filter' => 'authClient']);
$routes->get('client/formulaire/transfert','ClientController::formulaire/transfert', ['filter' => 'authClient']);
$routes->post('client/operation','ClientController::operation', ['filter' => 'authClient']);
