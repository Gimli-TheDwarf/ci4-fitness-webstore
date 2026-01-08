<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsImagesModel extends Model
{
    protected $table            = 'product_images';
    protected $primaryKey       = 'id';  
    protected $useAutoIncrement = true;  
    protected $allowedFields    = ['id', 'item_id','img', 'slot'];

    public function retrieveImages(int $id, bool $retrieveFirst): ?array
    {
        $db = \Config\Database::connect();
        log_message('info', "ID: " . json_encode($id) . ' is retrieve false true: ' . json_encode($retrieveFirst));
        $sql = 
        "
        SELECT *
        FROM product_images
        WHERE product_images.item_id = ? 
        AND IF(?, product_images.slot = 1, TRUE);
        ";

        $images = $db->query($sql, [$id, (int)$retrieveFirst])->getResultArray();
        //turning the bool into int cuz sql likes numbers
        log_message('info', 'PRODUCTS IMAGES MODEL -RETRIEVE IMAGES FUNCTION- result is: ' . json_encode($images));
        return $images ? $images : [];
    }

    public function removeProductImage(int $id, string $image, int $slot): void
    {
        $db = \Config\Database::connect();
        $sql = 
        "
        DELETE FROM product_images
        WHERE item_id = ? AND img = ? AND slot = ?;
        ";
        log_message('info', "model status: removed image");
        $db->query($sql, [$id, $image, $slot]);
    }
}
