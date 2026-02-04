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
        $userFavoriteModel = new \App\Models\UserFavoriteModel();

        $tags = $tagsModel->findAll();

        $sql =
        "
            SELECT 
            p.*, 
            COALESCE(JSON_ARRAYAGG(t.name), JSON_ARRAY()) AS tags
            FROM products AS p
            LEFT JOIN product_tags AS pt ON pt.item_id = p.id
            LEFT JOIN tags AS t ON t.id = pt.tag_id
            GROUP BY p.id;
        ";

        $query = $db->query($sql);
        $products = $query->getResultArray();

        $user_id = session()->get('user_id') ?? null;

        $favoritesIds = [];
        if ($user_id !== null) 
        {
            $favoritesIds = $userFavoriteModel->select('product_id')->where('person_id', $user_id)->findColumn('product_id') ?? [];
        }

        $idArray = $favoritesIds ? array_fill_keys($favoritesIds, true) : [];

        foreach ($products as &$product) 
        {
            $productId = $product['id'];
            $product['favorite'] = isset($idArray[$productId]);
            $product['tags'] = $product['tags'] ? json_decode($product['tags'], true) : [];
        }
        unset($product); 

        foreach ($products as $i => $product) 
        {
            $displayImage = $productsImagesModel->retrieveImages($product['id'], false);
            $images = $displayImage ?? null;
            log_message('info', 'display item: ' . json_encode($images));
            $products[$i]['images'] = $images;
        }

        log_message('info', 'products in retrieveinfo: ' . json_encode($products));
        return 
        [
            'products' => $products,
            'tags' => $tags
        ];
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

    public function returnCheckout()
    {
        $loadInfo = $this->retrieveInfo();

        return view('Checkout', 
        [
            'username' => session()->get('username'),
            'info' => $loadInfo
        ]);
    }

    public function Delivery()
    {
        $loadInfo = $this->retrieveInfo();

        return view('Delivery', 
        [
            'username' => session()->get('username'),
            'info' => $loadInfo
        ]);
    }

    public function Billing()
    {
        $loadInfo = $this->retrieveInfo();

        return view("Billing", 
        [
            'username' => session()->get('username'),
            'info' => $loadInfo
        ]);
    }

    public function FavoritesView()
    {
        $userFavoriteModel = new \App\Models\UserFavoriteModel();

        $loadInfo = $this->retrieveInfo();

        log_message('info', 'load info favorites: ' . json_encode($loadInfo));
        return view("Favorites", 
        [
            'username' => session()->get('username'),
            'info' => $loadInfo,
        ]);
    }

    public function returnAccount()
    {
        $userModel = new \App\Models\UserModel();
        $loadInfo = $this->retrieveInfo();
        $userId = session()->get("user_id");
        $userInfo = (object) $userModel->select('username, email, phone, address, full_name, role')->where('id', $userId)->first(); 

        log_message('info', 'user and item ids: ' . json_encode(
        [
            'user info' => $userInfo,
            'user id ' => $userId
        ]));

        return view("Account", 
        [
            'username' => session()->get('username'),
            'info' => $loadInfo,
            'userInformation' => $userInfo
        ]);
    }

    public function modifyWishlist($status)
    {
        $userFavoriteModel = new \App\Models\UserFavoriteModel();

        $data = $this->request->getJSON(true);
        $item_id = $data['item_id'] ?? null;
        $user_id = session()->get('user_id');

        
        log_message('info', 'user and item ids: ' . json_encode(
        [
            'data' => $data,
            'user_id' => $user_id,
            'item_id' => $item_id
        ]));

        if ($status === 'delete') 
        {
            $userFavoriteModel->where(['person_id' => $user_id, 'product_id' => $item_id])->delete();

            return $this->response->setStatusCode(200)->setJSON([
                'error' => false,
                'message' => 'Item removed from Favorites List'
            ]);
        }

        if ($status === 'populate') 
        {
            $userFavoriteModel->insert(
            [
                'person_id' => $user_id,
                'product_id' => $item_id
            ]);

            return $this->response->setStatusCode(200)->setJSON(
            [
                'error' => false,
                'message' => 'Item added to Favorites List'
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON(
        [
            'error' => true,
            'message' => 'Invalid action'
        ]);
    }

    public function changeAccountSettings()
    {
        $userModel = new \App\Models\UserModel();
        $data = $this->request->getJSON(true);
        if ($data === null) 
        {
            return $this->response->setStatusCode(400)->setJSON(
            [
                'error' => true,
                'message' => 'Invalid JSON body.'
            ]);
        }
        $userId = session()->get('user_id');

        log_message("info", 'data check: ' .json_encode($data));
        $userModel->update($userId, $data);

        return $this->response->setStatusCode(200)->setJSON(
        [
            'error' => false,
            'message' => 'Successfully updated account information'
        ]);
    }

}
