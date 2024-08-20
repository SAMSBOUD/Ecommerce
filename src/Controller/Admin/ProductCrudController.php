<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\SlugType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id' => 'DESC']); // Tri par défaut par ID décroissant
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
            TextField::new('name'),
            TextField::new('description'),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextEditorField::new('more_description')->hideOnIndex(),
            TextEditorField::new('additional_infos')->hideOnIndex(),
            AssociationField::new('relatedProducts')->hideOnIndex(),
            /*AssociationField::new('fkCategory'),*/
            ImageField::new('imageUrls')
            ->setFormTypeOptions([
                "multiple"=>true,
                "attr"=>[
                    'accept'=> 'image/*'
                    //To specify image formats: 'accept'=> 'image/x-png,image/gif,image/jpeg,image/jpg',

                ]
                
            ])
            ->setBasePath("assets/images/products")
            ->setUploadDir("public/assets/images/products")
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired($pageName === Crud::PAGE_NEW) // When editing a product will not be required. 
            
            ,
            MoneyField::new('solde_price')->setCurrency("EUR"),
            MoneyField::new('regular_price')->setCurrency("EUR"),
            IntegerField::new('stock'),
            //AssociationField::new('categories'),
            AssociationField::new('categories')
            ->setFormTypeOptions([
                'attr' => [
                    'data-dependency-source' => 'categories',
                ],
            ]),
            AssociationField::new('fkBrand')
                ->setFormTypeOptions([
                    'attr' => [
                        'data-dependent-field' => 'fkBrand',
                    ],
                ])
                ,
            BooleanField::new('isBestSeller'),
            BooleanField::new('isNewArrival'),
            BooleanField::new('isFeatured'),
            BooleanField::new('isSpecialOffer'),
          


        ];
    }
   
   
}
