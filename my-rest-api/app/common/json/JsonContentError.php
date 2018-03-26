<?php
namespace Modules\Common\Json;

class JsonContentError
{
    public function __construct($error) {
        $this->jsonContent = [
                                'status'   => 'ERROR',
                                'messages' => $error,
                             ];
    }
}
