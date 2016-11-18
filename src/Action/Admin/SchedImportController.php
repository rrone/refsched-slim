<?php
namespace  App\Action\Admin;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedImportController extends AbstractController
{
    private $importer;

	public function __construct(Container $container, SchedImport $importer)
    {
		parent::__construct($container);

        $this->importer = $importer;
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

        $request = $request->withAttribute('user', $this->user);
        $request = $request->withAttribute('event', $this->event);

        $path = $this->importer->handler($request);

        if (!empty($path)){
            return $response->withRedirect($path);
        }

        $this->importer->render($response);

        return $response;
    }
}
