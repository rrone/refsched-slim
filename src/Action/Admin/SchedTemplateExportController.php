<?php
namespace App\Action\Admin;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedTemplateExportController extends AbstractController
{
	private $exportXl;

	public function __construct(Container $container, SchedTemplateExport $exportXl)
    {
		parent::__construct($container);

		$this->exportXl = $exportXl;
    }
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

        $response = $this->exportXl->handler($request, $response);

        return $response;
    }
}
