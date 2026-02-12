<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function adminView()
    {
        $info = [];

        $usersModel = new \App\Models\UserModel();
        $productsModel = new \App\Models\ProductModel();
        $tagsModel = new \App\Models\TagModel();
        $productsImagesModel = new \App\Models\ProductsImagesModel();

        $users = $usersModel->findAll();
        $products = $productsModel->findAll();
        $tags = $tagsModel->findAll();

        foreach($products as $i => $product)
        {
            $displayImage = $productsImagesModel->retrieveImages($product['id'], false);
            $images = $displayImage ?? null;
            $products[$i]['images'] = $images ?? [];
        };
        
        log_message('info', 'products in admin view return: ' . json_encode($products));
        log_message('info', '______________________________________________________');
        $info =
        [
            'users' => $users,
            'products' => $products,
            'tags' => $tags
        ]; 

        if(!session()->get('isLoggedIn') || session()->get('role') !== 'administrator')
        {
            return redirect()->to('logout');
        }
        
        return view('Admin', 
        [
            'adminInfo' => $info,
        ]);
    }

    public function addProduct()
    {
        $productsModel = new \App\Models\ProductModel();
        $productTagsModel = new \App\Models\ProductsTagsModel();
        $productsImagesModel = new \App\Models\ProductsImagesModel();

        $postData = $this->request->getPost();
        $images = $this->request->getFileMultiple('images'); //accessing the array images[]
        $status = isset($postData['status']) ? 1 : 0;
        
        $id = $productsModel->insert(
        [
            'name' => $postData['name'],
            'description' => $postData['description'],
            'price' => $postData['price'],
            'status' => $status,
            'discount_percentage' => $postData['discount_percentage']
        ]);

        foreach((array) $images as $i => $imageFile)
        {
            $field = "images.$i"; //accessing individual items within the array : images.0, images.1, images.2 etc
            if (!$this->validate($this->validateImage($field))) //validating each of the images
            {
                $errors = array_values($this->validator->getErrors()); //array values returns the values, dropping the original keys | The validator object is the validation service instance that runs the rules and stores results
                // we validate only rules for $field (e.g. images.0, images.1, images.2)
                // if it fails, $this->validator->getErrors() returns errors from that most recent validate call (or the current field)
                return $this->returnFunction(true, $errors);
            }
            // $filename = $imageFile->getTempName();
            // $imageSize = @getimagesize($filename);
            // log_message('info' , 'image size: ' . json_encode($imageSize));
            $imageName = $imageFile->getName();
            $productsImagesModel->insert(
            [
                'item_id' => $id,
                'img' => $imageName,
                'slot' => $i+1
            ]);
            $imageFile->move(FCPATH.'images/productsImages', $imageName);
        }

        return $this->returnFunction(false, "Successfully uploaded product to the database");
    }

    public function alterTagName()
    {
                
        log_message('info', '____________TAG NAME CHANGE_____________');
        $tagsModel = new \App\Models\TagModel();
        $info = $this->request->getJSON(true);

        $id = $info['id'] ?? null;
        $name = $info['name'] ?? null;

        log_message("info", json_encode($info));

        $updatedTag = $tagsModel->update($id, [
            'name' => $name,
        ]);

        if(!$updatedTag)
        {
            log_message("info", "MAMMA MIA");
            return $this->response->setStatusCode(422)->setJSON([
                'error' => true,
                'message' => 'Tag name was not updated.',
            ]);
        }

        log_message("info", "not MAMMA MIA");

        return $this->response->setStatusCode(200)->setJSON([
            'error' => false,
            'message' => 'Successfully changed the name of the tag.',
            'tags' => $tagsModel->findAll(),
        ]);
    }

    public function deleteTag()
    {
        $tagsModel = new \App\Models\TagModel();
        $info = $this->request->getJSON(true);
        log_message('info', 'info thing: '. json_encode($info));
        $id = $info['tag_id'] ?? null; 
        log_message('info', 'id: '. json_encode($id));
        if ($id)
        {
            $tagsModel->delete($id);
            return $this->response->setStatusCode(200)->setJSON([
                'error'   => false,
                'message' => 'Tag was successfully deleted.',
                'tags'    => $tagsModel->findAll(),
            ]);
        }

        return $this->response->setStatusCode(422)->setJSON([
            'error'   => true,
            'message' => 'No tag ID provided.'
        ]);
    }

    public function insertTag()
    {
        log_message('info', '____________TAG INSERT____________');
        $tagsModel = new \App\Models\TagModel();
        $info = $this->request->getJSON(true);

        $nameSection = $info['tag_name'] ?? null;

        log_message('info', 'NAME OF TAG: '. json_encode($info));

        if ($nameSection)
        {
            $tagsModel->insert([
                'name' => $nameSection,
            ]);

            return $this->response->setStatusCode(200)->setJSON([
                'error'   => false,
                'message' => 'Tag was successfully added.',
                'tags'    => $tagsModel->findAll()
            ]);
        }

        return $this->response->setStatusCode(422)->setJSON([
            'error'   => true,
            'message' => 'No tag name was provided.'
        ]);
    }

    public function alterProduct()
    {
        $productsModel = new \App\Models\ProductModel();
        $tagsModel = new \App\Models\TagModel();
        $productTagsModel = new \App\Models\ProductsTagsModel();
        $productsImagesModel = new \App\Models\ProductsImagesModel();

        $infoArray = $this->request->getJSON(true);
        $tagsValue = [];

        log_message('info', 'stuff test yadayadayada: ' . json_encode($infoArray));

        foreach($infoArray as $array)
        {

            log_message('info' ,'ARRAY:' . json_encode($array));
            $id = $array["id"] ?? null;
            if($id === null || trim($id) === '')
            {
                return $this->returnFunction(true, "No Id provided, contact a developer.");
            }
            
            foreach($array as $key => $value)
            {
                if ($key == "tags")
                {
                    $productTagsModel->where('item_id', $id)->delete();
                    foreach($value as $val)
                    {
                        log_message('info', 'tag value: ' . json_encode($val));
                        $productTagsModel->insert([
                            'item_id' => $id,
                            'tag_id' => $val
                        ]);
                    }
                }

                else if ($key === "id")
                {
                    continue;
                }

                else
                {
                    log_message('info' ,'ID:' . json_encode($id));
                    log_message('info' ,'VALUE:' . json_encode($value));
                    log_message('info' ,'KEY:' . json_encode($key));
                    $productsModel->update($id, [ $key => $value ]);
                }
            }
        }

        $products = $productsModel->findAll();
        
        foreach($products as $i => $product)
        {
            $displayImage = $productsImagesModel->retrieveImages($product['id'], false);
            $images = $displayImage ?? null;
            $products[$i]['images'] = $images ?? [];
        };

        log_message('info', 'info return: ' . json_encode($products));
        return $this->returnFunction(false, "Successfully updated field values", ["data" => $products]);
    }

    public function findItemTags()
    {
        $tagsModel = new \App\Models\TagModel();
        $item_id = $this->request->getGet('item_id');
        log_message('info', 'trash: ' . json_encode($item_id));

        $tagNames = $tagsModel->retrieveTags((int) $item_id);

        return $this->response->setStatusCode(200)->setJSON([
            'error' => false,
            'message' => 'Product tags retrieved.',
            'data' => $tagNames,
        ]);
    }

    public function findItemImages()
    {
        $productsImagesModel = new \App\Models\ProductsImagesModel();
        $item_id = (int) $this->request->getGet('id');

        $images = $productsImagesModel->retrieveImages((int) $item_id, false);

        return $this->response->setStatusCode(200)->setJSON(
        [
            'error' => false,
            'message' => 'Successfully retireved product images.',
            'images' => $images,
        ]);
    }

    public function removeItemImage()
    {
        $productsImagesModel = new \App\Models\ProductsImagesModel();
        $params = $this->request->getJSON(true);
        log_message('info', 'here 1: '.json_encode($params));
        
        $productsImagesModel->removeProductImage((int) $params['id'], (string) $params['img'], (int) $params['slot']);
        $fileName = FCPATH . '/images/productsImages/' . $params['img'];
        if(is_file($fileName))
        {
            unlink($fileName);
        }

        log_message('info', 'CONTROLLER STATUS: removed image');

        return $this->response->setStatusCode(200)->setJSON([
            'error' => false,
            'message' => 'Successfully removed the image from the images list.',
        ]);
    }

    public function updateItemImages()
    {
        $productsImagesModel = new \App\Models\ProductsImagesModel();
        $formData = $this->request->getPost('items');
        $parsedData = [];
        
        foreach($formData as $index => $dataCell)
        {
            $instructionsArray = [];
            $returnMessage = null;
            $file = null;

            $productId = (int) $dataCell['product_id'];
            $slot = (int) $dataCell['slot'];

            $field = "items.$index.img_file";
            $file = $this->request->getFile($field) ?? null;

            $oldImage = $dataCell['old_img_name'] ?? null;

            $instructionsArray = 
            [
                'item_id' => $productId,
                'slot' => $slot,
            ];
            
            if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) 
            {
                if (!$this->validate($this->validateImage($field))) 
                {
                    $errors = array_values($this->validator->getErrors());
                    return $this->returnFunction(true, $errors);
                }

                $newName = $file->getRandomName();
                $file->move(FCPATH . 'images/productsImages', $newName);
                $instructionsArray['img'] = $newName;
            }

            if ($oldImage && $file && $file->hasMoved()) 
            {
                $oldPath = FCPATH . 'images/productsImages/' . $oldImage;
                log_message('info' ,'old thing: '.json_encode($oldPath));

                if (is_file($oldPath)) 
                {
                    unlink($oldPath);
                }
            }

            $id = array_key_exists("id", $dataCell) ? (int) $dataCell['id'] : null;
            if($id)
            {
                $instructionsArray['id'] = $id;
            }

            if (!$id && empty($instructionsArray['img'])) 
            {
                return $this->returnFunction(true, 'Image is required for new items.');
            }
            $parsedData[] = $instructionsArray;
        };

        log_message('info' ,'parsed data: ' . json_encode($parsedData));
        foreach($parsedData as $index => $insert)
        {
            log_message('info', 'ITEM TO BE PARSED: ' . json_encode($insert));
            $imgValue = (string)($insert['img'] ?? $insert['old_img_name'] ?? '');
            $sameValueRow = $productsImagesModel->where('item_id', (int)$insert['item_id'])->where('slot', (int)$insert['slot'])->where('img !=', $imgValue)->first(); //if null then update/insert instead
            //a row with the same values but where ids dont match (a different, unique row with matching values)
            if($sameValueRow)
            {
        
                $id = (int)$sameValueRow['id'];
                $potentialInsert = null;

                foreach($parsedData as $array)
                {
                    if(isset($array['id']) && (int)$array['id'] === $id)
                    //if we find an item that will later be used to make changes to the row with this id
                    {
                        $potentialInsert = true;
                    }
                }
                 
                if($potentialInsert === true)
                {
                    $negativeIndex = -((int)$index + 1); 
                    log_message('info' ,'RAAAAAAAAAAAAAAAAAA ' . json_encode($negativeIndex));
                    $productsImagesModel->update($id, ['slot' => $negativeIndex]);
                    $a = !empty($insert['id']) ? $productsImagesModel->update($insert['id'], $insert) : $productsImagesModel->insert($insert);
                    log_message('info' ,'succesful new insert: ' . json_encode($a));
                }   
                else
                {
                    $errorMessage = "Inserts/alterations to an existing row must have unique slot values.";
                    return $this->returnFunction(true, $errorMessage);
                }
            }
            else
            {
                $a2 = isset($insert['id']) ? $productsImagesModel->update($insert['id'], $insert) : $productsImagesModel->insert($insert);
                log_message('info' ,'succesful new insert ELSE: ' . json_encode($a2));
            }
        }

        $returnMessage = "Successfully updated product images.";
        $newUploads = $productsImagesModel->retrieveImages((int) $productId, false);
        log_message('info', 'i dont even know anymore: '. json_encode($newUploads));
        $returnInfo = ['updatedImages' => $newUploads];

        return $this->returnFunction(false, $returnMessage, $returnInfo);
    }

    private function returnFunction(bool $status, array|string $message, array $additionalFields = [])
    {
        $statusCode = $status ? 422 : 200;

        return $this->response->setStatusCode($statusCode)->setJSON(array_merge(['error' => $status, 'message' => $message], $additionalFields));
    }

    private function validateImage(string $file): array
    {
        //this function takes the name of the field (key) of the items we sent and finds it in the request/$_FILES section.
        //image1: {name: jadadada...} 

        // field = "image1" 
        // $_files: 
        // image1: {info...}
        return //returns true/false
        [
            $file => 
            [
                'label' => 'Image File', 

                'rules' => 
                [
                    "min_dims[$file, 200, 300]",
                    "max_dims[$file, 400, 600]",
                    "mime_in[$file,image/jpg,image/jpeg,image/png,image/webp]",
                    "is_image[$file]",
                    "uploaded[$file]"
                ],

                'errors' => 
                [
                    'mime_in'  => 'Allowed types: JPG, PNG, WEBP.',
                    'min_dims' => 'Minimum image scale is 400x600.', 
                    'max_dims' => 'Maxium image scale is 400x600.', 
                    'is_image' => 'The file must be a valid image.', 
                    'uploaded' => 'You have to upload an image.'
                ],
            ],
        ];
    }
}
