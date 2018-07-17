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
<p>Name: {$rec['Name']}</p>  
<p>S/A/R: {$rec['SAR']}</p>  
<p>Level: {$rec['CertificationDesc']}</p>  
<p>Cell Phone: {$rec['Cell Phone']}</p>  
<p>eMail: <a href='mailto:{$rec['Email']}'>{$rec['Email']}</a></p>  
</div>
EOT;

        return $html;
    }
}