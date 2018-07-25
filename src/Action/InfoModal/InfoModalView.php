<?php
namespace App\Action\InfoModal;

use App\Action\AbstractView;
use App\Action\SchedulerRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class InfoModalView extends AbstractView
{
    private $info;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);
    }

    public function handler(Request $request, Response $response)
    {
        $name = $request->getAttribute('id');

        $this->info = $this->retrievePersonData($name);

        return null;
    }

    public function render(Response &$response)
    {
        if(!is_array($this->info)) {
            return 'Not Found';
        }

        $info = null;

        foreach($this->info as $rec){
            $info[] = $this->formatInfo($rec);
        }
        $html = null;
        foreach($info as $p){
            if(!is_null($html)) $html .= '<hr>';
            $html .= $p;
        }

        return $html;
    }

    /**
     * @param $id
     * @return null|string
     */
    protected function retrievePersonData($id)
    {
        $personRec = $this->sr->getPersonInfo($id);

        if (empty($personRec)) {
            $personRec = "$id not found";
        }

        return $personRec;

    }

    protected function formatInfo($rec){

        if(!is_array($rec)) {
            return null;
        }
        $html = <<<EOT
<div>        
<p><b>Name:</b> {$rec['Name']}</p>  
<p><b>S/A/R:</b> {$rec['SAR']}</p>  
<p><b>Level:</b> {$rec['CertificationDesc']}</p>  
<p><b>Cell Phone:</b> {$rec['Cell Phone']}</p>  
<p><b>eMail:</b> <a href='mailto:{$rec['Email']}'>{$rec['Email']}</a></p>  
</div>
EOT;

        return $html;
    }
}