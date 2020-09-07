<?php

namespace App\Controller;


use App\Service\CalenderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

        dump($agenda);

        return $this->render('agenda/index.html.twig', [
            'agenda' => $agenda,
        ]);
    }
}
