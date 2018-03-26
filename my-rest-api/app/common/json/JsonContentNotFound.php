<?php
namespace Modules\Common\Json;

class JsonContentNotFound
{
    public function __construct() {
        $this->jsonContent = [
                                'status'   => 'NOT-FOUND',
                             ];
    }
}
