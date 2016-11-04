<?php
namespace App\Action\Admin;

use App\Action\AbstractController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LogDumpController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->user) || !$this->user->admin) {
            return $response->withRedirect($this->greetPath);
        }

        $response = $this->handleRequest($request, $response);

        if(is_null($response)) {
            return $response->withRedirect($this->logonPath);
        } else {
            return $response;
        }
    }
    protected function handleRequest($request, $response)
    {
        if(isset( $_SESSION['admin'])) {
            $file = __DIR__ . '/../../../var/logs/app.log';

            $response = $response->withHeader('Content-Description', 'File Transfer')
                ->withHeader('Content-Type', 'application/octet-stream')
                ->withHeader('Content-Disposition', 'attachment;filename="' . basename($file) . '"')
                ->withHeader('Expires', '0')
                ->withHeader('Cache-Control', 'must-revalidate')
                ->withHeader('Pragma', 'public');
//            ->withHeader('Content-Length', filesize($file));

            $log = readfile($file);
            $response->write($log);

            return $response;
        }

        return $response;
    }
}