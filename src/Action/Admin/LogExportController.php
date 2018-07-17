<?php
namespace App\Action\Admin;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class LogExportController extends AbstractController
{
    private $exporter;

    /**
     * LogExportController constructor.
     * @param Container $container
     * @param LogExport $logExport
     */
    public function __construct(Container $container, LogExport $logExport)
    {
        parent::__construct($container);

        $this->exporter = $logExport;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws \Interop\Container\Exception\ContainerException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->getBaseURL('logonPath'));
        };

        if (!$this->user->admin) {
            return $response->withRedirect($this->getBaseURL('greetPath'));
        }

        $this->logStamp($request);

        $request = $request->withAttributes([
            'user' => $this->user,
            'event' => $this->event
        ]);

        $response = $this->exporter->handler($request, $response);

        return $response;
    }
}
