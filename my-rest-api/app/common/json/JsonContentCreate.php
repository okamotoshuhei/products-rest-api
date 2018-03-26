<?php
namespace Modules\Common\Json;

class JsonContentCreate
{
    public function __construct($product) {
        $this->jsonContent = [
                                'status'   => 'CREATE',
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
