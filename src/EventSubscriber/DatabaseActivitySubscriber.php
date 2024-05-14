<?php

namespace App\EventSubscriber;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabaseActivitySubscriber implements EventSubscriberInterface
{
    
    private $appKernel; 
    private $rootDir; 


    public function __construct(KernelInterface $appKernel)
    {
        // A chaque fois que besoin de la racine ou les images se trouve on fait appel à 
        $this->appKernel = $appKernel; 
        $this->rootDir = $appKernel->getProjectDir();
    }

    public  function getSubscribedEvents(): array
    {
        return [

                Events::postRemove,
        ];
    }

    public function postRemove(PostRemoveEventArgs $args): void 
    {
        $this->logActivity ('remove', $args->getObject()); 
    }

    public function logActivity (string $action, mixed $entity): void 
    {
        if (($entity instanceof Product) && $action === "remove") {
            //remove image 
            $imageUrls = $entity->getImageUrls(); 
                foreach ($imageUrls as $imageUrl) {
                    $filelink = $this->rootDir. "/public/assets/images/products/".$imageUrl; 
                    $this->deleteImage($filelink);

                }

        }
        if (($entity instanceof Category) && $action === "remove") {
            //remove image
            
            $filename = $entity->getImageUrl(); 

            $filelink = $this->rootDir. "/public/assets/images/categories/".$filename; 

            // deleteImage methode
            $this->deleteImage($filelink);

        }


    }

    // Create a method for deleting to avoid repeating code. 

    public function  deleteImage(string $filelink): void
    {
        try {
            // To do ; code
            $result = unlink($filelink);
        } catch(\Throwable $th){
            // To throw...

            }
    }
}
