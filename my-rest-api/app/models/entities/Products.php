<?php
namespace Modules\Models\Entities;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\Callback;

class Products extends Model
{
    public function validation()
    {
        $validator = new Validation();

        // 商品タイトル必須チェック
        $validator->add(
            "title",
            new PresenceOf()
        );

        // 商品タイトル桁数チェック
        $validator->add(
            'title',
            new Callback(
                [
                    'callback' => function($data) {
                        $titleCnt = mb_strlen($data->title);
                        if($titleCnt == 0 || $titleCnt > 100) {
                            return false;
                        }
                        return true;
                    },
                    'message' => "商品タイトルは1文字〜100文字の間で設定してください。"
                ]
            )
        );

        // 商品タイトル桁数チェック
        $validator->add(
            'description',
            new Callback(
                [
                    'callback' => function($data) {
                        $descriptionCnt = mb_strlen($data->description);
                        if($descriptionCnt == 0 || $descriptionCnt > 500) {
                            return false;
                        }
                        return true;
                    },
                    'message' => "商品説明文は1文字〜500文字の間で設定してください。"
                ]
            )
        );

        // 商品価格属性チェック
        $validator->add(
            "price",
            new Numericality(
                [
                    "message" => "価格には数値を設定して下さい。",
                ]
            )
        );

        // 商品価格範囲チェック
        $validator->add(
            "price",
            new Between(
                [
                    "minimum" => 0,
                    "maximum" => 4294967295,
                    "message" => "商品価格には0~4294967295の間の値を設定して下さい。",
                ]
            )
        );

        return $this->validate($validator);
    }
}