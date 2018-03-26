<?php
namespace Modules\Models\Entities;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class Summaries extends Model
{
    public function validation()
    {
        $validator = new Validation();

        // 集計日付必須チェック
        $validator->add(
            "summary_date",
            new PresenceOf()
        );

        // 集計商品id必須チェック
        $validator->add(
            "id",
            new PresenceOf()
        );

        // 削除フラグ必須チェック
        $validator->add(
            "delete_flg",
            new PresenceOf()
        );

        return $this->validate($validator);
    }
}