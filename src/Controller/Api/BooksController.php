<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use League\Flysystem\FilesystemOperator;

class BooksController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(BookRepository $bookRepository)
    {
        return $bookRepository->findAll();
    }


    /**
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(Request $request, EntityManagerInterface $em, FilesystemOperator $defaultStorage)
    {
        $bookDto= new BookDto();
        $form= $this->createForm(BookFormType::class, $bookDto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $extension= explode('/', mime_content_type($bookDto->base64Image))[1];//Obtenemos la extension de la imagen
            $data= explode(',', $bookDto->base64Image);//la codificacion de la imagen consta de 2 partes, informacion de la ruta y la codificacion
            // estas dos partes estan separadas por una coma, hacemos las separacion
            $filename= sprintf('/books/%s.%s', uniqid('book_', true), $extension);//Generar un nombre de fichero
            $defaultStorage->write($filename, base64_decode($data[1]));//escribimos la imagen
            $book= new Book();
            $book->setTitle($bookDto->title);
            $book->setImage($filename);
            $em->persist($book);
            $em->flush();

            return $book;
        }

        return $form;
    }
}