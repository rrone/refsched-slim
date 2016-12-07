<?php

namespace App\Action\PDF;

use Slim\Http\Response;
use Slim\Http\Request;

class ExportPDF
{
    public function handler(Request $request, Response $response)
    {
        // generate the response
        $response = $response->withHeader('Content-Type','application/pdf');
        $response = $response->withHeader('Content-Disposition', 'inline; filename=extra_map.pdf');

        $content = file_get_contents(__DIR__ . '/Extra_Map.pdf');

        /** @noinspection PhpUndefinedMethodInspection */
        $body = $response->getBody();
        /** @noinspection PhpUndefinedMethodInspection */
        $body->write($content);

        return $response;
    }
}