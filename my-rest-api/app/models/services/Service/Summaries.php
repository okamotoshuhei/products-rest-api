<?php
namespace Modules\Models\Services\Service;

use Modules\Models\Entities\Summaries as EntitySummaries;


class Summaries
{
    public function findAllSummaries (){
        $query = EntitySummaries::query();
        $query->orderBy('summary_date');
        return $query->execute();
    }

    public function findSummaries($summaryDate, $deleteFlg = null)
    {
        $query = EntitySummaries::query();
        $query->where('summary_date = :summary_date:', ['summary_date' => $summaryDate]);
        if($deleteFlg !== null){
            $query->andWhere('delete_flg = :delete_flg:', ['delete_flg' => $deleteFlg]);
        }
        $query->orderBy('summary_date');
        return $query->execute();
    }

    public function registerTodayProducts($products){

        $summaryProduct = new EntitySummaries();

        $summaryProduct->summary_date = date('Ymd');

        foreach($products as $product){            
            $summaryProduct->id = $product->id;
            $summaryProduct->delete_flg = $product->delete_flg;
            // 登録処理に失敗した場合
            if($summaryProduct->create() === false){
                foreach ($summaryProduct->getMessages() as $message) {
                    $errors = $message->getMessage();
                }
                return $errors;
            }
        }
        return true;
    }
}
