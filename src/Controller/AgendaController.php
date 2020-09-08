<?php

namespace App\Controller;

use App\Entity\AgendaEvent;
use App\Form\AgendaEventType;
use App\Service\CalenderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AgendaController extends AbstractController
{
    private CalenderService $calenderService;

    public function __construct(CalenderService $calenderService)
    {
        $this->calenderService = $calenderService;
    }

    /**
     * @Route("/agenda", name="agenda")
     */
    public function index(Request $request)
    {
        $day = $request->get('day', date("j"));
        $month = $request->get('month', date("n"));
        $year = $request->get('year', date("Y"));

        $agenda = $this->calenderService->getView($year, $month, $day);

        return $this->render('agenda/index.html.twig', [
            'agenda' => $agenda,
        ]);
    }

    /**
     * @Route("/agenda/new", name="agenda_new")
     */
    public function addEvent(Request $request)
    {
        try {
            $datetime = new \DateTimeImmutable($request->get('datetime'));
        } catch (\Exception $exception) {
            throw new BadRequestHttpException('Invalid date', $exception);
        }

        $agendaEvent = new AgendaEvent();
        $agendaEvent->setStartsAt($datetime);

        $form = $this->createForm(AgendaEventType::class, $agendaEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // TODO: Perform extra validation to check if the chosen datatime is really available

            $agendaEvent->setUser($this->getUser());
            $agendaEvent->setEndsAt($agendaEvent->getStartsAt()->add(new \DateInterval("PT2H")));

            $this->calenderService->addEvent($agendaEvent);
            return $this->redirectToRoute('agenda');
        }

        return $this->render('agenda/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
