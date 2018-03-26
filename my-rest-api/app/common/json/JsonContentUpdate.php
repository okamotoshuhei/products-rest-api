<?php
namespace Modules\Common\Json;

class JsonContentUpdate
{
    public function __construct($product) {
        $this->jsonContent = [
                                'status'   => 'UPDATE',
                                'data'   => [
                                    'id'          => $product->id,
                                    'title'       => $product->title,
                                    'description' => $product->description,
                                    'price'       => $product->price,
                                    'image'       => $product->image,
                                ]
                             ];
    }
}
