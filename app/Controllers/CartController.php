<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CartController extends BaseController
{
    public function removeItem($id)
    {
        $productsUsersModel = new \App\Models\UsersProductsModel();

        $person = session()->get('user_id');

        $productsUsersModel->where('person_id', $person)->where('product_id', $id)->delete();

        $cartCount = $productsUsersModel->getCartCount($person);

        session()->set('cart_items_count', $cartCount);

        return $this->response->setStatusCode(200)->setJSON([
            'error' => false,
            'message' => 'Successfully removed the item from your cart.',
            'cartCount' => $cartCount
        ]);
    }

    public function addItem($id, $quantity)
    {
        $productsUsersModel = new \App\Models\UsersProductsModel();

        $person = session()->get('user_id');

        $checkIfExists = $productsUsersModel->where('person_id', $person)->where('product_id', $id)->first();

        if($checkIfExists)
        {
            $productsUsersModel->updatePersonProduct($person, $id, $quantity);
        }

        else
        {
            $productsUsersModel->insert([
                'person_id' => $person,
                'product_id' => $id,
                'quantity' => $quantity
            ]);
        }

        $cartCount = $productsUsersModel->getCartCount($person);
        session()->set('cart_items_count', $cartCount);

        
        return $this->response->setStatusCode(200)->setJSON([
            'error' => false,
            'message' => 'Successfully added the item to your cart.',
            'cartCount' => $cartCount
        ]);
    }

    public function changeQuantity($id, $change)
    {
        $productsUsersModel = new \App\Models\UsersproductsModel();

        $person = session()->get('user_id');

        $productsUsersModel->updatePersonProduct($person, $id, $change);
        $quantity = $productsUsersModel->where('person_id', $person)->where('product_id', $id)->first();    

        log_message('info', "______");

        return $this->response->setStatusCode(200)->setJSON([
            'error' => false,
            'message' => 'Successfully changed the product quantity.',
            'quantity' => $quantity['quantity']
        ]);
    }
}
