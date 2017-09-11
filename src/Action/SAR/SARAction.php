<?php
namespace App\Action\SAR;

use App\Action\SchedulerRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

final class SARAction
{
    private $sr;

    public function __construct(Container $container, SchedulerRepository $sr)
    {
        $this->sr = $sr;
    }

    public function __invoke(Request $request, Response $response)
    {
//        $portal = $request->getAttribute('portal');
        if(array_key_exists('portal', $request->getParams()) ){
            $portalName = $request->getParams();

            $content = $this->retrieveSAR($portalName['portal']);

            echo $content;

        }
    }

    protected function retrieveSAR($portalName)
    {
        $sarRec = $this->sr->getSARRec($portalName);

        if (!empty($sarRec)) {
            $sar = $sarRec->section.'/'.$sarRec->area.'/'.$sarRec->region;
        } else {
            $sar = null;
        }

        return $sar;

    }

}
