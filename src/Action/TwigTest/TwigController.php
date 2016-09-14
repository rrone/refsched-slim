<?php
namespace App\Action\TwigTest;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class TwigController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Twig test page action dispatched");
        
        $content = array(
            'logon' => $this->render()
        );
        
        $this->view->render($response, 'twig.html.twig', $content);      
        
        return $response;
    }
    private function render()
    {
        $html =
<<<EOD
      <form name="form1" method="post" action="/sched_greet">
        <h3 align="center">Scheduling System Logon</h3>
        <div align="center">
		  <table>
          <tr><td width="50%"><div align="right">ARA or representative from:&nbsp;</div></td>
            <td width="50%"><select name="area">
              <option>Area 1B</option>
              <option>Area 1C</option>
              <option>Area 1D</option>
              <option>Area 1F</option>
              <option>Area 1G</option>
              <option>Area 1H</option>
              <option>Area 1N</option>
              <option>Area 1P</option>
              <option>Area 1R</option>
              <option>Area 1S</option>
              <option>Area 1U</option>
              <option>Section 1</option>
              <option>Section One</option>
              <option>Section 2</option>
              <option>Section 10</option>
              <option>Section 11</option>
            </select></td>
          </tr>
          <tr><td width="50%"><div align="right">Password:&nbsp;</div></td>
            <td><input type="password" name="passwd"></td></tr>
            <!--Edit Below To Display Event In Dropdown-->
            <!--Be sure it matches event and schedule selection in sched_greed.php-->
            <tr><td width="50%"><div align="right">Competition:&nbsp;</div></td>
            <td width="50%">
                <select name="event">
                  <option>Western States Championships - Mar 19 - 20</option>
                </select>
            </td>
          </tr>
		  </table>
          <p>
            <input type="submit" name="Submit" value="Logon">      
          </p>
        </div>
      </form>
EOD;

        return $html;
    }
}
