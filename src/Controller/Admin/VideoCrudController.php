<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use App\Form\VideoFileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class VideoCrudController extends AbstractCrudController
{
    public const ACTION_DUPLICATE = 'duplicate';

    public static function getEntityFqcn(): string
    {
        return Video::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicateAction = Action::new(self::ACTION_DUPLICATE, 'Duplicate')
            ->setIcon('fa fa-clone')
            ->linkToCrudAction('duplicate')
            ->setCssClass('btn btn-info');

        return $actions
            ->add(Crud::PAGE_EDIT, $duplicateAction)
            ->reorder(Crud::PAGE_EDIT, [self::ACTION_DUPLICATE, Action::SAVE_AND_RETURN]);
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('title'),
            TextareaField::new('description'),
            ChoiceField::new('category')->setChoices([
                'Film' => 'film',
                'Série' => 'serie',
            ]),
            SlugField::new('slug')->setTargetFieldName('title')
                ->hideOnIndex()
                ->setUnlockConfirmationMessage('Attention, si vous modifiez le slug, l\'url de la page
            contenant ce film ou cette episode  va changer !'),
            ImageField::new('thumbnail')
                ->SetBasePath('uploads/thumbnail/') // destination du fichier image
                ->setUploadDir('public/uploads/thumbnail/') // destination final du fichier image
                ->setUploadedFileNamePattern('[randomhash].[extension]') //selection de l extention du fichier ET GENERATION D'UNE CHAINE DE CARACTERE
                ->setRequired(false),
            IntegerField::new('duration'),
            ImageField::new('path') -> SetBasePath('uploads/videos/')
                ->setUploadDir('public/uploads/videos/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->onlyOnForms(),
            AssociationField::new('genre')

        ];
    }

    private function duplicate(
        AdminContext $context,
        EntityManagerInterface $em,
        AdminUrlGenerator $adminUrlGenerator
    ): Response {
        /** @var Video $video */
        $video = $context->getEntity()->getInstance();
        $duplicatedVideo = clone $video;
        parent::persistEntity($em, $duplicatedVideo);
        $url = $adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($duplicatedVideo->getId())
            ->generateUrl();
        return $this->redirect($url);
    }
}
