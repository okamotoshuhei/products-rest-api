<?php
namespace Modules\Models\Services\Service;

use Modules\Models\Entities\Products as EntityProducts;


class Products
{
    public function findAllProducts($deleteFlg = null)
    {
        $query = EntityProducts::query();
        //引数が渡されなかった場合、削除済の商品は除外して検索(デフォルト)
        if(!$deleteFlg){
            $query->where('delete_flg = :delete_flg:', ['delete_flg' => 0]);
        }
        $query->orderBy('id');
        return $query->execute();
    }

    public function findProductsByTitle($title, $deleteFlg = null)
    {
        $query = EntityProducts::query();
        $query->where("title LIKE :title:", ["title" => '%'.$title.'%']);
        if(!$deleteFlg){
            $query->andWhere("delete_flg = :delete_flg:", ["delete_flg" => 0]);
        }
        $query->orderBy("id");
        return $query->execute();
    }

    public function findProductById($id, $deleteFlg = null)
    {
        $query = EntityProducts::query();
        $query->where("id = :id:", ["id" => $id]);
        if(!$deleteFlg){
            $query->andWhere("delete_flg = :delete_flg:", ["delete_flg" => 0]);
        }
        $query->limit(1);
        return $query->execute()->getFirst();


        return EntityProducts::query()
            ->where(
                "id = :id: AND delete_flg = :delete_flg:",
                [
                    "id"         => $id,
                    "delete_flg" => 0,
                ]
            )
            ->limit(1)
            ->execute()
            ->getFirst();
    }

    // 集計日付に登録された商品および削除された商品(更新日時が集計日時かつ削除フラグが1)を取得
    public function findProductsToday()
    {
        $nowDate = date("Y-m-d");
        $nextDate = date("Y-m-d", strtotime("+1 day"));

        return EntityProducts::query()
                        ->where(
                            "created_at >= :nowDate: AND created_at < :nextDate:",
                            [
                                "nowDate"  => $nowDate,
                                "nextDate" => $nextDate,
                            ]
                        )
                        ->orWhere(
                            "updated_at >= :nowDate: AND updated_at < :nextDate: AND delete_flg = :delete_flg:",
                            [
                                "nowDate"    => $nowDate,
                                "nextDate"   => $nextDate,
                                "delete_flg" => 1,
                            ]
                        )
                        ->orderBy("created_at")
                        ->execute();
    }

    public function createProduct($title, $description, $price, $image)
    {
        $product = new EntityProducts();

        $product->title = $title;
        $product->description = $description;
        $product->price = $price;
        $product->image = $image;
        $product->status = $product->save();
        return $product;
    }

    public function updateProduct($id, $title, $description, $price, $image)
    {
        $product = $this->findProductById($id);

        //更新対象存在チェック
        if(!$product){
            return false;
        }

        $product->id = $id;
        $product->title = $title;
        $product->description = $description;
        $product->price = $price;
        $product->image = $image;
        $product->updated_at = date("Y-m-d H:i:s");
        $product->status = $product->save();
        return $product;
    }

    public function deleteProduct($id)
    {
        $product = $this->findProductById($id);

        //削除対象存在チェック
        if(!$product){
            return false;
        }

        $product->delete_flg = 1;
        $product->updated_at = date("Y-m-d H:i:s");
        $product->status = $product->save();
        return $product;
        
        //削除対象存在チェック
        if(!$product){
            return false;
        }

        $product->status = $product->save();
        return $product;
    }
}
