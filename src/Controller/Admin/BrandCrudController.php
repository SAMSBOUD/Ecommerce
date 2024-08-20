<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
class BrandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Brand::class;
    }

    public function configureActions(Actions $actions): Actions{
        return $actions 
        ->add(Crud::PAGE_EDIT, Action::INDEX)
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->add(Crud::PAGE_EDIT, Action::DETAIL)
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('fkCategory'),
            TextField::new('libelle'),
            TextEditorField::new('description'),
        ];
    }
    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            // imports the given entrypoint defined in the importmap.php file of AssetMapper
            // it's equivalent to adding this inside the <head> element:
            // {{ importmap('admin') }}
           // ->addAssetMapperEntry('admin')
            // you can also import multiple entries
            // it's equivalent to calling {{ importmap(['app', 'admin']) }}
           // ->addAssetMapperEntry('app', 'admin')

            // adds the CSS and JS assets associated to the given Webpack Encore entry
            // it's equivalent to adding these inside the <head> element:
            // {{ encore_entry_link_tags('...') }} and {{ encore_entry_script_tags('...') }}
           // ->addWebpackEncoreEntry('admin-app')

            // it's equivalent to adding this inside the <head> element:
            // <link rel="stylesheet" href="{{ asset('...') }}">
           // ->addCssFile('build/admin.css')
            //->addCssFile('https://example.org/css/admin2.css')

            // it's equivalent to adding this inside the <head> element:
            // <script src="{{ asset('...') }}"></script>
           // ->addJsFile('js/admin.js')
           ->addJsFile(Asset::new('js/admin.js')->onlyWhenCreating())
           // ->addJsFile('https://example.org/js/admin2.js')

            // use these generic methods to add any code before </head> or </body>
            // the contents are included "as is" in the rendered page (without escaping them)
           /* ->addHtmlContentToHead('<link rel="dns-prefetch" href="https://assets.example.com">')
            ->addHtmlContentToBody('<script> ... </script>')
            ->addHtmlContentToBody('<!-- generated at '.time().' -->')*/
        ;
    }
}
