<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth
$routes->get('/', 'SignInController::Main');
$routes->get('login', 'SignInController::LogIn');
$routes->post('login_info', 'SignInController::LogInInfo');
$routes->post('signup', 'SignInController::SignUp');
$routes->get('logout', 'SignInController::LogOut');

// Google OAuth
$routes->get('auth/google', 'SignInController::google');
$routes->get('auth/google/callback', 'SignInController::googleCallback'); //function that google, not the user uses - callback basically

// Home 
$routes->get('homepage', 'HomeController::HomeView', ['filter' => 'auth']);
$routes->get('cart', 'HomeController::Cart', ['filter' => 'auth']);
$routes->get('delivery', 'HomeController::Delivery', ['filter' => 'auth']);
$routes->get('billing', 'HomeController::Billing', ['filter' => 'auth']);
$routes->get('checkout', 'HomeController::returnCheckout', ['filter' => 'auth']);
$routes->get('account', 'HomeController::returnAccount', ['filter' => 'auth']);
$routes->patch('alter-account', 'HomeController::changeAccountSettings', ['filter' => 'auth']);

// Home / Data
$routes->get('loadImages(:segment)', 'HomeController::loadImages/$1');
$routes->get('retrieveBasicInfo', 'HomeController::retrieveInfo');

// Cart
$routes->post('AddItem/(:num)/(:num)', 'CartController::addItem/$1/$2', ['filter' => 'auth']);
$routes->post('RemoveItem/(:num)', 'CartController::removeItem/$1', ['filter' => 'auth']);
$routes->patch('quantityChange/(:num)/(:segment)', 'CartController::changeQuantity/$1/$2', ['filter' => 'auth']);

// Wishlist / Favorites
$routes->post('PopulateWishList/(:segment)', 'HomeController::modifyWishlist/$1', ['filter' => 'auth']);
$routes->get('Favorites', 'HomeController::FavoritesView', ['filter' => 'auth']);

// Admin
$routes->get('adminPanel', 'AdminController::adminView', ['filter' => 'admin']);
$routes->post('AddNewProduct', 'AdminController::addProduct', ['filter' => 'admin']);
$routes->post('addNewTag', 'AdminController::insertTag', ['filter' => 'admin']);
$routes->patch('ChangeTag', 'AdminController::alterTagName', ['filter' => 'admin']);
$routes->delete('removeTag', 'AdminController::deleteTag', ['filter' => 'admin']);
$routes->patch('changeProducts', 'AdminController::alterProduct', ['filter' => 'admin']);

// Admin / Product 
$routes->get('GetItemTags', 'AdminController::findItemTags', ['filter' => 'admin']);
$routes->get('retrieveProductImages', 'AdminController::findItemImages', ['filter' => 'admin']);
$routes->delete('deleteProduct', 'AdminController::deleteProduct', ['filter' => 'admin']);
$routes->delete('deleteProductImage', 'AdminController::removeItemImage', ['filter' => 'admin']);
$routes->post('updateImage', 'AdminController::updateItemImages', ['filter' => 'admin']);
