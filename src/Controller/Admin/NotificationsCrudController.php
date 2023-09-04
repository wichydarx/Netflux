<?php

namespace App\Controller\Admin;

use App\Entity\Notifications;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NotificationsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Notifications::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
