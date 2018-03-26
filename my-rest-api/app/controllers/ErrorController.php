<?php
namespace Modules\Controllers;


class ErrorController extends ControllerBase
{
    public function show404Action()
    {
        $response = $this->response;

        $response->setStatusCode(404, "Not Found")->sendHeaders();
        $response->setContent("404 File not Found");
        
        return $response;
    }

    public function show500Action()
    {
        $response = $this->response;

        $response->setStatusCode(500, "Internal Server Error")->sendHeaders();
        $response->setContent("Internal Server Error");
        
        return $response;
    }

    public function show401Action()
    {
        $response = $this->response;
        
        $response->setStatusCode(401, "Unauthorized")->sendHeaders();
        $response->setJsonContent("Authentication error");

        return $response;
    }
}