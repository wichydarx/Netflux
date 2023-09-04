<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use App\Entity\Episode;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EpisodeCrudController extends AbstractCrudController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    public static function getEntityFqcn(): string
    {
        return Episode::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextareaField::new('description'),
            TextField::new('saison'),
            AssociationField::new('video', 'Vidéo')
            ->setFormTypeOption('choices', $this->getVideoChoices())
        ];
    }

    private function getVideoChoices(): array
{
    $videos = $this->manager->getRepository(Video::class)->findAll();
    $choices = [];


    foreach ($videos as $video) {
        if($video->getCategory()=="serie"){
            $choices[$video->getTitle()] = $video;
        }
    }
    return $choices;
}
    
}
