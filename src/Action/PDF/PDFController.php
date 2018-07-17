<?php

namespace  App\Action\PDF;

use Slim\Container;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use App\Action\AbstractController;

class PDFController extends AbstractController
{
    private $exportPDF;

	public function __construct(Container $container, ExportPDF $exportPDF)
    {
		parent::__construct($container);

		$this->exportPDF = $exportPDF;

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return ExportPDF|Response
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->getBaseURL('greetPath'));
        };

        $this->logStamp($request);

        $request = $request->withAttribute('field_map', $this->event->field_map);

        $response = $this->exportPDF->handler($request, $response);

        return $response;
		
    }
}
