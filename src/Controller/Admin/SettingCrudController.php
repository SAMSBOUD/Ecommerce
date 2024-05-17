<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
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
            TextField::new('website_name')->hideOnIndex(), // hideOnIndex to hide on admin page.,
            TextField::new('description'),
            IntegerField::new('taxe_rate'),
            ImageField::new('logo')
            ->setBasePath("/assets/images/setting")
            ->setUploadDir("public/assets/images/setting")
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired($pageName === Crud::PAGE_NEW),// When editing a product will not be required.

            ChoiceField::new('currency')->setChoices([
                'EUR'=> 'EUR',
                'USD'=> 'USD',
                'XOF'=> 'XOF',

            ]),
            TextField::new('facebookLink')->hideOnIndex(), // hideOnIndex to hide on admin page. 
            TextField::new('youTubeLink')->hideOnIndex(),
            TextField::new('instagramLink')->hideOnIndex(),
            TelephoneField::new('phone')->hideOnIndex(),
            TextField::new('street'),
            TextField::new('city'),
            TextField::new('code_postal'),
            TextField::new('state'),
            EmailField::new('mail'),





        ];
    }
    
}
