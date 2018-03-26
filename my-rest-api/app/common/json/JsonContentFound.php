<?php
namespace Modules\Common\Json;

class JsonContentFound
{
    public function __construct($product) {
        $this->jsonContent = [
                                'status'   => 'FOUND',
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
