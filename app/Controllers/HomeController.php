<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class HomeController extends BaseController
{
    public function retrieveInfo()
    {
        $db = \Config\Database::connect();
        $tagsModel = new \App\Models\TagModel();
        $productsImagesModel = new \App\Models\ProductsImagesModel();
        $productsModel = new \App\Models\ProductModel();

        $tags = $tagsModel->findAll();

        $sql = 
        "
        SELECT 
            p.*,
            GROUP_CONCAT(t.name SEPARATOR ', ') AS tags
            FROM products AS p
            LEFT JOIN product_tags AS pt ON pt.item_id = p.id
            LEFT JOIN tags AS t ON t.id = pt.tag_id
        GROUP BY p.id
        ";

        $query = $db->query($sql);
        $products = $query->getResultArray();

        foreach($products as $i => $product)
        {
            $displayImage = $productsImagesModel->retrieveImages($product['id'], false);
            $images = $displayImage ?? null;
            log_message('info', 'display item: ' . json_encode($images));
            $products[$i]['images'] = $images;
        };

        return([
            'products' => $products,
            'tags' => $tags
        ]);
    }

    public function adminCheck()
    {
        if(session()->get('role') === 'administrator')
        {
            return redirect()->to('adminPanel');
        }
        else
        {
            return null;
        };
    }

    public function HomeView()
    {
        $adminCheck = $this->adminCheck();
        if($adminCheck)
        {
            return $adminCheck;
        }

        $loadInfo = $this->retrieveInfo();

        return view('Main', 
        [
            'username' => session()->get('username'),
            'info' => $loadInfo,
        ]);
    }

    public function LogOut()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Logged Out Succesfully.');
    }

    public function loadImages($folder)
    {
        $pathToFolder = FCPATH . 'images/' . $folder . '/';
        $images = [];

        if (!is_dir($pathToFolder)) //checks if its a directory
        {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => true,
                'message' => 'Failed To Locate Folder',
                'data' => '',
            ]);
        }

        $files = scandir($pathToFolder); //generates a list of all files in directory

        foreach ($files as $file) 
        {
            if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) 
            {
                $images[] = $file;
            }
        }

        return $this->response->setStatusCode(200)->setJSON([
            'error' => false,
            'message' => 'Images Located',
            'data' => $images,
        ]);
    }

    public function Cart()
    {
        $db = \Config\Database::connect();
        $productsModel = new \App\Models\ProductModel();
        $UsersProductsModel = new \App\Models\UsersProductsModel();
        $productsImagesModel = new \App\Models\ProductsImagesModel();

        $id = (int) session()->get('user_id');

        $sql = 
        "
        SELECT
            p.*,
            up.quantity
        
            FROM users_products AS up
            LEFT JOIN products AS p ON p.id = up.product_id
            WHERE up.person_id = {$id}

        GROUP BY up.person_id, p.name
        ";

        $query = $db->query($sql);
        $result = $query->getResultArray();
        foreach($result as $i => $item)
        {
            $imagesArray = $productsImagesModel->retrieveImages($item['id'], false);
            $result[$i]['images'] = $imagesArray;
        };

        $cartCount = $UsersProductsModel->getCartCount($id);
        session()->set('cart_items_count', $cartCount);

        $loadInfo = $this->retrieveInfo();

        return view('Cart', 
        [
            'cartItems' => $result,
            'username' => session()->get('username'),
            'info' => $loadInfo,
        ]);
    }
}
