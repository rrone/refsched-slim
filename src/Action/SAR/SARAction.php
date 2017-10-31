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
        $portal = $request->getAttribute('portal');

        $content = $this->retrieveSAR($portal);

        //$newResponse = $response->withJson($content, 200,JSON_UNESCAPED_SLASHES);
        echo $content;

        return null;

    }

    protected function retrieveSAR($portalName)
    {
        $sarRec = $this->sr->getSARRec($portalName);
        $sar = null;
        if (!empty($sarRec)) {
            if(!empty($sarRec->region)) {
                $sar = $sarRec->section.'/'.$sarRec->area.'/'.$sarRec->region;
            } elseif(!empty($sarRec->area)) {
                $sar = $sarRec->section.'/'.$sarRec->area;
            } elseif(!empty($sarRec->area)) {
                $sar = $sarRec->section;
            }
        } else {
            $sar = 'SAR not found';
        }

        return $sar;

    }

}
