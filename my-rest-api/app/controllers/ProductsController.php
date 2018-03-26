<?php
namespace Modules\Controllers;

use Modules\Models\Services\Services;
use Modules\Common\Json\JsonContentFound;
use Modules\Common\Json\JsonContentNotFound;
use Modules\Common\Json\JsonContentCreate;
use Modules\Common\Json\JsonContentUpdate;
use Modules\Common\Json\JsonContentDelete;
use Modules\Common\Json\JsonContentError;

class ProductsController extends ControllerBase
{
    public function beforeExecuteRoute($dispatcher)
    {
        $headers = $this->request->getHeaders();
        //ヘッダに含まれるトークンが正しいかチェック
        if($headers['Token'] !== $this->security->getSessionToken()){
            return $this->dispatcher->forward(
                [
                    'controller' => 'error',
                    'action'     => 'show401'
                ]
            );
        }
    }

    public function searchAction()
    {
        $products = $this->getProductsService()->findAllProducts();

        $response = $this->response;

        // 検索結果が存在しない場合
        if(count($products) === 0){
            $response->setJsonContent(new JsonContentNotFound());

        // 検索結果が存在する場合
        } else {
            foreach ($products as $product) {
                $content = new JsonContentFound($product);
                $data[] = $content->jsonContent;
            }
            
            $response->setJsonContent($data);
        }

        return $response;
    }

    public function searchByTitleAction($title)
    {
        $products = $this->getProductsService()->findProductsByTitle($title);

        $response = $this->response;

        // 検索結果が存在しない場合
        if(count($products) === 0){
            $content = new JsonContentNotFound();
            $response->setJsonContent($content->jsonContent);

        // 検索結果が存在する場合
        } else {
            foreach ($products as $product) {
                $content = new JsonContentFound($product);
                $data[] = $content->jsonContent;
            }

            $response->setJsonContent($data);
        }

        return $response;
    }

    public function searchByIdAction($id)
    {   
        $product = $this->getProductsService()->findProductById($id);

        $response = $this->response;

        // 検索結果が存在しない場合
        if (!$product) {
            $content = new JsonContentNotFound();
            $response->setJsonContent($content->jsonContent);

        // 検索結果が存在した場合
        } else {
            $content = new JsonContentFound($product);
            $response->setJsonContent($content->jsonContent);
        }

        return $response;
    }

    public function registerAction()
    {   
        $params = $this->request->getJsonRawBody();

        $title = $params->title;
        $description = $params->description;
        $price = $params->price;
        $image = $params->image;

        $product = $this->getProductsService()->createProduct($title, $description, $price, $image);

        $response = $this->response;

        // 登録が成功した場合
        if ($product->status === true) {
            $response->setStatusCode(201, 'Created');

            $content = new JsonContentCreate($product);
            $response->setJsonContent($content->jsonContent);
            
        // 登録が失敗した場合
        } else {
            $response->setStatusCode(409, 'Conflict');

            foreach ($product->getMessages() as $message) {
                $error = new JsonContentError($message->getMessage());
                $errors[] = $error->jsonContent;
            }

            $response->setJsonContent($errors);
        }

        return $response;
    }

    public function updateAction($id)
    {   
        $params = $this->request->getJsonRawBody();

        $title = $params->title;
        $description = $params->description;
        $price = $params->price;
        $image = $params->image;

        $product = $this->getProductsService()->updateProduct($id, $title, $description, $price, $image);

        $response = $this->response;

        //更新対象が存在しない場合
        if(!$product){
            $response->setJsonContent(new JsonContentNotFound());
            return $response;
        }

        // 更新が成功した場合
        if ($product->status === true) {
            $content = new JsonContentUpdate($product);
            $response->setJsonContent($content->jsonContent);

        // 更新が失敗した場合
        } else {

            $response->setStatusCode(409, 'Conflict');

            foreach ($product->getMessages() as $message) {
                $error = new JsonContentError($message->getMessage());
                $errors[] = $error->jsonContent;
            }

            $response->setJsonContent($errors);
        }

        return $response;
    }

    public function deleteAction($id)
    {   
        $params = $this->request->getJsonRawBody();

        $product = $this->getProductsService()->deleteProduct($id);

        $response = $this->response;

        //削除対象が存在しない場合
        if(!$product){
            $response->setJsonContent(new JsonContentNotFound());
            return $response;
        }

        // 削除が成功した場合
        if ($product->status === true) {
            $content = new JsonContentDelete($product);
            $response->setJsonContent($content->jsonContent);

        // 削除が失敗した場合
        } else {

            $response->setStatusCode(409, 'Conflict');

            foreach ($product->getMessages() as $message) {
                $error = new JsonContentError($message->getMessage());
                $errors[] = $error->jsonContent;
            }

            $response->setJsonContent($errors);
        }

        return $response;
    }

    private function getProductsService()
    {
        return Services::getService('Products');
    }
}

