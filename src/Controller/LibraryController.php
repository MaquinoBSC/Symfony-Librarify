<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger= $logger;
    }
    
    /**
     * @Route("/library/list", name="library_list")
     */
    public function list(Request $request)
    {
        $title= $request->get('title', 'Alegria');
        $this->logger->info('List action called');
        $response= new JsonResponse();
        $response->setData([
            'success'=> true,
            'data' => [
                [
                    'id' => 1,
                    'title' => 'Hacia rutas salvajes'
                ],
                [
                    'id' => 2,
                    'title' => 'El nombre del viento'
                ],
                [
                    'id' => 3,
                    'title' => $title
                ]
            ]
        ]);
        return $response;
    }


    /**
     * @Route("/library/hola-mundo", name="library_hello")
     */
    public function sayHello(){
        $response= new Response();
        $response->setContent('<div> Hello world </div>');
        return $response;
    }

}