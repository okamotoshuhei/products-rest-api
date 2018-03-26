<?php
use Phalcon\Cli\Task;
use Modules\Models\Services\Services;


class ProductsTask extends Task
{	
	public function mainAction()
    {
        $todayProducts = $this->getProductsService()->findProductsToday();
        if (!$todayProducts) {
            $this->logger->log("今日、新しく登録・削除された商品はありません。");
        } else {
            $this->db->begin();
            $result = $this->getSummariesService()->registerTodayProducts($todayProducts);
            if($result === true){
                $this->logger->log("日毎集計の登録に成功しました。");
                $this->db->commit();
            } else {
                $this->logger->error("日毎集計の登録に失敗しました。");
                $this->logger->error(print_r($result,true));
                $this->db->rollback();
                return;
            }
        }
    }
    
    private function getProductsService()
    {
        return Services::getService('Products');
    }

    private function getSummariesService()
    {
        return Services::getService('Summaries');
    }
}