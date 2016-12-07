<?php

namespace App\Action\PDF;

use Slim\Http\Response;
use Slim\Http\Request;

class ExportPDF
{
    public function handler(Request $request, Response $response)
    {
        $field_map = $request->getAttribute('field_map');

        // generate the response
        $response = $response->withHeader('Content-Type','application/pdf');
        $response = $response->withHeader('Content-Disposition', "inline; filename=$field_map");

        $content = file_get_contents(__DIR__ . "/$field_map");

        /** @noinspection PhpUndefinedMethodInspection */
        $body = $response->getBody();
        /** @noinspection PhpUndefinedMethodInspection */
        $body->write($content);

        return $response;
    }
}