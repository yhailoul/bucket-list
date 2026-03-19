<?php

namespace App\Controller\API;

use App\Form\EventsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api/events', name: 'events_')]
final class WishController extends AbstractController
{
#[Route('', name: 'all', methods: ['GET'])]
public function allEvents(): Response{
    $json =file_get_contents('https://public.opendatasoft.com/api/records/1.0/search/?dataset=evenements-publics-openagenda');
    $data = json_decode($json,true);
    return $this->render('events/allEvents.html.twig', ['data'=>$data['records']]);
}

#[Route('/search', name: 'search', methods: ['POST', 'GET'])]
public function events(Request $request):Response {
    $eventForm = $this->createForm(EventsType::class);
    $eventForm->handleRequest($request);
    $data = ['records' => []];
    if ($eventForm->isSubmitted() && $eventForm->isValid()) {
        $formData = $eventForm->getData();
        $city = $formData['city'];
        $date = $formData['date'];
        if ($date instanceof \DateTime) {
            $date = $date->format('Y-m-d');
        }
        $city = ucfirst($city);
        $url='https://public.opendatasoft.com/api/records/1.0/search/?dataset=evenements-publics-openagenda';
        $url .= '&refine.location_city=' .urlencode($city);;
        $url .= '&refine.firstdate_begin=' .urlencode($date);
        $json=file_get_contents($url);
        if($json===false){
            $this->addFlash('error', 'Oops! We could not find the events you are looking for!');
        }else {
            $data = json_decode($json, true);
        }

    }

    return $this->render('events/events.html.twig', ['data'=>$data, 'eventForm'=>$eventForm]);
}

}
