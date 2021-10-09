<?php

namespace App\Serializer;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BookNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;
    private $urlHelper;

    public function __construct(ObjectNormalizer $normalizer, UrlHelper $urlHelper)
    {
        $this->normalizer= $normalizer;
        $this->urlHelper= $urlHelper;
    }

    public function normalize($book, $format= null, array $context= [])
    {
        $data= $this->normalizer->normalize($book, $format, $context);
        // $data['message']= "Hola mundo";
        if(!empty($book->getImage())){
            $data['image']= $this->urlHelper->getAbsoluteUrl('/storge/default' . $book->getImage()); 
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Book;
    }
}