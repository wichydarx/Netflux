<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VideoCrudController extends AbstractCrudController
{


    public static function getEntityFqcn(): string
    {
        return Video::class;
    }


    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('title'),
            TextEditorField::new('description'),
            ChoiceField::new('category')->setChoices([
                'Film' => 'film',
                'Série' => 'serie',
            ]),
            ImageField::new('thumbnail')
                ->SetBasePath('uploads/') // destination du fichier image
                ->setUploadDir('public/uploads/') // destination final du fichier image
                ->setUploadedFileNamePattern('[randomhash].[extension]') //selection de l extention du fichier ET GENERATION D'UNE CHAINE DE CARACTERE
                ->setRequired(false),
            IntegerField::new('duration'),
            UrlField::new('path'), // A voir s'il y a un autre field plus interessant à mettre
            AssociationField::new('genre')

        ];
    }
}
