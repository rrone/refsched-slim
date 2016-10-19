<?php

namespace App\Action;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\FigRequestCookies;

abstract class AbstractController
{
    //database connection
    protected $conn;

    //schedule repository
    protected $sr;
	
    //shared variables
    protected $view;
    protected $logger;
    protected $container;
    protected $root;

	//view variables
    protected $page_title;
	protected $dates;
	protected $location;
	protected $msg;
    protected $msgStyle;

	//session variables	
	protected $event;
    protected $user;
    protected $authed;
    
    //default layout colors
    protected $colorTitle = '#80ccff';
    protected $colorOpen = '#FFF484';
    protected $colorGroup = '#00FF88';
    protected $colorNotGroup = '#ffcccc';
    protected $colorHighlight = '#FFBC00';
    protected $colorAlert = '#CC0000';
    protected $colorWarning = '#CC00CC';
    protected $colorSuccess = '#02C902';
    
    //named routes
    protected $assignPath;
    protected $controlPath;
    protected $editrefPath;
    protected $endPath;
    protected $fullPath;
    protected $greetPath;
    protected $lockPath;
    protected $logonPath;
    protected $masterPath;
    protected $refsPath;
    protected $schedPath;
    protected $unlockPath;
	protected $fullXlsPath;
	protected $adminUpdatePath;
    protected $schedTemplatePath;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->view = $container->get('view');
        $this->logger = $container->get('logger');
        $this->root = __DIR__ . '/../../var';

        $this->page_title = "Section 1 Referee Scheduler";

        $this->assignPath = $this->container->get('router')->pathFor('assign');
        $this->controlPath = $this->container->get('router')->pathFor('control');
        $this->editrefPath = $this->container->get('router')->pathFor('editref');
        $this->endPath = $this->container->get('router')->pathFor('end');
        $this->fullPath = $this->container->get('router')->pathFor('full');
        $this->greetPath = $this->container->get('router')->pathFor('greet');
        $this->lockPath = $this->container->get('router')->pathFor('lock');
        $this->logonPath = $this->container->get('router')->pathFor('logon');
        $this->masterPath = $this->container->get('router')->pathFor('master');
        $this->refsPath = $this->container->get('router')->pathFor('refs');
        $this->schedPath = $this->container->get('router')->pathFor('sched');
        $this->unlockPath = $this->container->get('router')->pathFor('unlock');
        $this->fullXlsPath = $this->container->get('router')->pathFor('fullexport');
        $this->adminPath = $this->container->get('router')->pathFor('admin');
        $this->schedTemplatePath = $this->container->get('router')->pathFor('sched_template');
        $this->schedImportPath = $this->container->get('router')->pathFor('sched_import');

    }
    protected function errorCheck()
    {
        $html = null;
        
        if ( !$this->authed ) {
            $html .= "<h2 class=\"center\">You need to <a href=\"$this->logonPath\">logon</a> first.</h2>";
        }
        else {
            $html .= "<h1 class=\"center\">Something is not right</h1>";
        }
        
        return $html;
    }
	protected function divisionAge($div)
	{
		return substr($div,0,3);
	}
    protected function isAuthorized(Request $request)
    {
        $result = $this->hasAuthorizedCookie($request);

        return $result['valid'];
    }
	protected function hasAuthorizedCookie(Request $request)
    {
        $isValid = array(
            'valid' => false,
            'data' => null
        );

        /*
         * Look for the 'token' cookie
         */
        $requestCookie = FigRequestCookies::get($request, 'token');

        if (!empty($requestCookie)) {
            /*
             * Extract the jwt from the cookie value
             */
            $jwt = $requestCookie->getValue();
            if ($jwt) {
                try {
                    /*
                     * decode the jwt using the key from config
                     */
                    $secret = getenv("JWT_SECRET");
                    $data = JWT::decode($jwt, $secret, array('HS256'));

                    $isValid = array(
                        'valid' => true,
                        'data' => $data
                    );

                } catch (\Exception $e) {
                    /*
                     * the token was not able to be decoded.
                     * this is likely because the signature was not able to be verified (tampered token)
                     */
                    $isValid['data'] = 'HTTP/1.0 401 Unauthorized';
                }
            } else {
                /*
                 * No token was able to be extracted from the authorization header
                 */
                $isValid['data'] = 'HTTP/1.0 400 Bad Request';
            }
        } else {
            /*
             * The request lacks the authorization token
             */
            $isValid['data'] = 'HTTP/1.0 400 Bad Request';
        }

        return $isValid;
    }
}
