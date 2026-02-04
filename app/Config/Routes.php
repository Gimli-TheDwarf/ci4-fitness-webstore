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
$routes->get('homepage', 'HomeController::HomeView');
$routes->get('cart', 'HomeController::Cart');
$routes->get('delivery', 'HomeController::Delivery');
$routes->get('billing', 'HomeController::Billing');
$routes->get('checkout', 'HomeController::returnCheckout');
$routes->get('account', 'HomeController::returnAccount');
$routes->patch('alter-account', 'HomeController::changeAccountSettings');

// Home / Data
$routes->get('loadImages(:segment)', 'HomeController::loadImages/$1');
$routes->get('retrieveBasicInfo', 'HomeController::retrieveInfo');

// Cart
$routes->post('AddItem/(:num)/(:num)', 'CartController::addItem/$1/$2');
$routes->post('RemoveItem/(:segment)', 'CartController::removeItem/$1');
$routes->patch('quantityChange/(:num)/(:segment)', 'CartController::changeQuantity/$1/$2');

// Wishlist / Favorites
$routes->post('PopulateWishList/(:segment)', 'HomeController::modifyWishlist/$1');
$routes->get('Favorites', 'HomeController::FavoritesView');

// Admin
$routes->get('adminPanel', 'AdminController::adminView');
$routes->post('AddNewProduct', 'AdminController::addProduct');
$routes->post('addNewTag', 'AdminController::insertTag');
$routes->patch('ChangeTag', 'AdminController::alterTagName');
$routes->delete('removeTag', 'AdminController::deleteTag');
$routes->patch('changeProducts', 'AdminController::alterProduct');

// Admin / Product 
$routes->get('GetItemTags', 'AdminController::findItemTags');
$routes->get('retrieveProductImages', 'AdminController::findItemImages');
$routes->delete('deleteProductImage', 'AdminController::removeItemImage');
$routes->post('updateImage', 'AdminController::updateItemImages');
