<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CartController extends BaseController
{
    public function removeItem($id)
    {
        $productsUsersModel = new \App\Models\UsersProductsModel();

        $person = (int) session()->get('user_id');
        $id = (int) $id;

        if ($person <= 0 || $id <= 0) 
            {
            return $this->response->setStatusCode(422)->setJSON([
                'error' => true,
                'message' => 'Valid user and product IDs are required.',
            ]);
        }

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

        $person = (int) session()->get('user_id');
        $id = (int) $id;
        $quantity = (int) $quantity;

        if ($person <= 0 || $id <= 0 || $quantity <= 0) 
            {
            return $this->response->setStatusCode(422)->setJSON([
                'error' => true,
                'message' => 'Valid user, product, and quantity values are required.',
            ]);
        }

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
        $productsUsersModel = new \App\Models\UsersProductsModel();

        $person = (int) session()->get('user_id');
        $id = (int) $id;
        $change = (int) $change;

        if ($person <= 0 || $id <= 0 || ! in_array($change, [-1, 1], true)) 
            {
            return $this->response->setStatusCode(422)->setJSON([
                'error' => true,
                'message' => 'Valid user, product, and quantity change values are required.',
            ]);
        }

        $productsUsersModel->updatePersonProduct($person, $id, $change);
        $quantity = $productsUsersModel->where('person_id', $person)->where('product_id', $id)->first();    

        if (! $quantity) 
            {
            return $this->response->setStatusCode(404)->setJSON([
                'error' => true,
                'message' => 'Cart item was not found.',
            ]);
        }

        log_message('info', "______");

        return $this->response->setStatusCode(200)->setJSON([
            'error' => false,
            'message' => 'Successfully changed the product quantity.',
            'quantity' => $quantity['quantity']
        ]);
    }
}
