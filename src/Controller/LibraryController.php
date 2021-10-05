<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{


    /**
     * @Route("/books", name="books_get")
     */
    public function list(Request $request, LoggerInterface $logger, BookRepository $bookRepository)
    {
        $title= $request->get('title', 'Alegria');
        $books= $bookRepository->findAll();

        $booksAsArray= [];
        foreach($books as $book){
            $booksAsArray[]= [
                'id'=> $book->getId(),
                'title'=> $book->getTitle(),
                'image'=> $book->getImage(),
            ];
        }
        $logger->info('Get all Books called');
        $response= new JsonResponse();
        $response->setData([
            'success'=> true,
            'data' => $booksAsArray
        ]);
        return $response;
    }

    /**
     * @Route("/book/create", name="create_book")
     */
    public function createBook(Request $request, EntityManagerInterface $em){
        $book= new Book();
        $response= new JsonResponse();
        $title= $request->get('title', null);
        if(empty($title)){
            $response->setData([
                'success'=> false,
                'error'=> "Title cannot be empty",
                'data' => null,
            ]);
        }
        $book->setTitle($title);
        // $book->setImage('p');
        $em->persist($book);
        $em->flush();

        $response->setData([
            'success'=> true,
            'error'=> $title,
            'data' => null,
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