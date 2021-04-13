<?php

namespace App\Action\EditEvents;

use App\Action\AbstractView;
use App\Action\SchedulerRepository;

use Exception;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class EditEventsView extends AbstractView
{
    protected $menu = null;
    private $description;


    /**
     * EditEventsView constructor
     * @param Container $container
     * @param SchedulerRepository $repository
     *
     */
    public function __construct(Container $container, SchedulerRepository $repository)
    {
        parent::__construct($container, $repository);

        $this->sr = $repository;
        $this->description = 'No events scheduled';
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');

        if ($request->isPost()) {
            $_POST = $request->getParsedBody();
            if (in_array('Update Events', array_values($_POST))) {
                $formData = [];

                foreach ($_POST as $key => $value) {
                    $datKey = explode('+', $key);
                    if (isset($datKey[1])) {
                        $formData['data'][$datKey[0]][$datKey[1]] = $value;
                    }
                }

//                $this->sr->modifyEvents($formData);
            }
        }
    }

    /**
     * @param Response $response
     * @return Response
     *
     */
    public function render(Response $response)
    {
        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => $this->renderEditEvents(),
                'topmenu' => $this->menu(),
                'menu' => $this->menu,
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => $this->description,
            ),
        );

        $this->view->render($response, 'editevents.html.twig', $content);

        return $response;
    }

    /**
     * @return string|null
     *
     */
    protected function renderEditEvents()
    {
        $html = null;
        $this->menu = null;

        $events = $this->sr->getAllEvents();

        if (count($events)) {
            $this->description = $this->user->name.": Update Events";

            $html .= "<form name=\"editref\" method=\"post\" action=".$this->getBaseURL('editEventsPath').">\n";

            $html .= "<table class=\"edit-table sched-table\">\n";
            $html .= "<tr class=\"center colorTitle\">";
            $html .= "<th>Name</th>";
            $html .= "<th>Dates</th>";
            $html .= "<th>Location</th>";
            $html .= "<th>Info Link</th>";
            $html .= "<th>Archived</th>";
            $html .= "<th>View in Header</th>";
            $html .= "<th>Enable Event</th>";
            $html .= "<th>Label</th>";
            $html .= "<th>Number of Referees</th>";
            $html .= "<th>Start Date</th>";
            $html .= "<th>End Date</th>";
            $html .= "<th>Field Map Link</th>";
            $html .= "</tr>\n";

            foreach ($events as $event) {
                $html .= "<tr class=\"center colorTitle\">";
                $html .= "<td style='font-weight: bold' colspan='13'>$event->projectKey
                        <input type=\"hidden\" name=\"$event->id+id\" value=\"$event->id\">
                        </td>";
                $html .= "</tr>\n";
                $html .= "<tr class=\"center colorGreen\">";
                $html .= "<td><input type=\"text\" name=\"$event->id+name\" value=\"$event->name\"></td>";
                $html .= "<td><input type=\"text\" name=\"$event->id+dates\" value=\"$event->dates\" title=\"Dates are in the form 'March 14-15, 2020'\"></td>";
                $html .= "<td><input type=\"text\" name=\"$event->id+location\" value=\"$event->location\" title=\"Field is text string, e.g. 'Field Name, City, State'\"></td>";
                $html .= "<td><input type=\"text\" name=\"$event->id+infoLink\" value=\"$event->infoLink\" title=\"URL to more information\"></td>";
                $html .= "<td class='center'><input class='input-checkbox' type=\"checkbox\" name=\"$event->id+archived\" value=\"archived\" title=\"Archived means no changes allowed\"";
                if ($event->archived) {
                    $html .= " checked></td>";
                } else {
                    $html .= "</td>";
                }
                $html .= "<td class='center'><input class='input-checkbox' type=\"checkbox\" name=\"$event->id+view\" value=\"view\" title=\"View means event information will be listed in the headers\"";
                if ($event->view) {
                    $html .= " checked></td>";
                } else {
                    $html .= "</td>";
                }
                $html .= "<td class='center'><input class='input-checkbox' type=\"checkbox\" name=\"$event->id+enabled\" value=\"enabled\"  title=\"Enabled means this event will be included in the login select list\"";
                if ($event->enabled) {
                    $html .= " checked></td>";
                } else {
                    $html .= "</td>";
                }
                $html .= "<td><input type=\"text\" name=\"$event->id+label\" value=\"$event->label\"  title=\"Label is how the event is listed in the dropdown (if enabled)\"></td>";
                $html .= "<td><input type=\"text\" name=\"$event->id+num_refs\" value=\"$event->num_refs\"  title=\"Number of Referees per match (typically 3 or 4)\" ></td>";
                $html .= "<td><input type=\"text\" name=\"$event->id+start_date\" value=\"$event->start_date\" title=\"Event start date in the form 'yyyy-mm-dd'\"></td>";
                $html .= "<td><input type=\"text\" name=\"$event->id+end_date\" value=\"$event->end_date\" title=\"Event end date in the form 'yyyy-mm-dd'\"></td>";
                $html .= "<td><input type=\"text\" name=\"$event->id+field_map\" value=\"$event->field_map\"  title=\"URL to PDF field map\"></td>";
                $html .= "</tr>\n";
            }
            $html .= "</table>\n";

            $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"UpdateEvents\" value=\"Update Events\">\n";
            $html .= "<div class='clear-fix'></div>";

            $html .= "</form>\n";
        }

        try {
            $this->menu = sizeof($events) ? $this->menu() : null;
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return $html;

    }

    /**
     * @return string
     *
     */
    private function menu()
    {
        $html = "<h3 class=\"center h3-btn\">";

        $html .= "<a href=".$this->getBaseURL('greetPath').">Home</a>&nbsp;-&nbsp;";
        $html .= "<a href=".$this->getBaseURL('fullPath').">View the full schedule</a>&nbsp;-&nbsp";
        $html .= "<a href=".$this->getBaseURL('schedPath').">View Assignors</a>&nbsp;-&nbsp;";
        $html .= "<a href=".$this->getBaseURL('editGamePath').">Edit matches</a>&nbsp;-&nbsp;";
        $html .= "<a href=".$this->getBaseURL('masterPath').">Select Assignors</a>&nbsp;-&nbsp;";
        $html .= "<a href=".$this->getBaseURL('refsPath').">Edit Referee Assignments</a>&nbsp;-&nbsp";
        $html .= "<a href=".$this->getBaseURL('endPath').">Log off</a>";

        $html .= "</h3>\n";

        return $html;
    }

}