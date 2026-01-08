<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'SignInController::Main');
$routes->get('login', 'SignInController::LogIn');

$routes->get('auth/google', 'SignInController::google');
$routes->get('auth/google/callback', 'SignInController::googleCallback'); //function that google, not the user uses - callback basically

$routes->post('login_info', 'SignInController::LogInInfo');
$routes->get('logout', 'SignInController::LogOut');
$routes->post('signup', 'SignInController::SignUp');

$routes->get('homepage', 'HomeController::HomeView');
$routes->get('loadImages(:segment)', 'HomeController::loadImages/$1');

$routes->get('cart', 'HomeController::Cart');

$routes->post('AddItem/(:num)/(:num)', 'CartController::addItem/$1/$2');
$routes->post('RemoveItem/(:segment)', 'CartController::removeItem/$1');

$routes->patch('quantityChange/(:num)/(:segment)', 'CartController::changeQuantity/$1/$2');
$routes->patch('ChangeTag', 'AdminController::alterTagName');

$routes->get('adminPanel', 'AdminController::adminView');
$routes->post('AddNewProduct', 'AdminController::addProduct');
$routes->post('addNewTag', 'AdminController::insertTag');

$routes->delete('removeTag', 'AdminController::deleteTag');

$routes->patch('changeProducts', 'AdminController::alterProduct');

$routes->get('GetItemTags', 'AdminController::findItemTags');
$routes->get('retrieveProductImages', 'AdminController::findItemImages');
$routes->delete('deleteProductImage', 'AdminController::removeItemImage');
$routes->post('updateImage', 'AdminController::updateItemImages');

$routes->get('retrieveBasicInfo', 'HomeController::retrieveInfo');