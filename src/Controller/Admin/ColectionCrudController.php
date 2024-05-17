<?php

namespace App\Controller\Admin;

use App\Entity\Colection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class ColectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Colection::class;
    }

    public function configureActions(Actions $actions): Actions {
        return $actions
        ->add(Crud::PAGE_EDIT,Action::INDEX)  /* permet de revenir a index lorsque sur page Edit */
        ->add(Crud::PAGE_INDEX,Action::DETAIL) /* permet de voir bouton show lorsque sur index */
        ->add(Crud::PAGE_EDIT,Action::DETAIL); /* permet de voir affiche de détail quand Edit user */

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextField::new('description'),
            TextField::new('button_text'),
            TextField::new('button_link'),
            BooleanField::new('isMega'),
            ImageField::new('imageUrl')
            ->setBasePath("/assets/images/collections")
            ->setUploadDir("public/assets/images/collections")
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired($pageName === Crud::PAGE_NEW) // When editing a product will not be required. 

          
             /* il faut préciser l'emplacement ou stocker les images. dans  public/assets/images/categories */
            //  setUploudFileNamePattern : permet de donner en nom d'extension unique
// to do : when deleting an image it needs also to be deleted from the image file. 
            ,
        ];
    }
}
