<?php

namespace App\Utils;



use Symfony\Component\HttpFoundation\Request;

class Json
{
    public static function getJsonBody(Request $request): ?array
    {
        $requestData = json_decode($request->getContent(), true);

        if  (sizeof($requestData) == 0)
            return ["error" => "No data provided"];

        return null;
    }

}