<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {

        $roles = [
            'User' => 'ROLE_USER',
            'Admin' => 'ROLE_ADMIN',
        ];

        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('firstName', 'Nom'),
            TextField::new('lastName', 'Prenom'),
            EmailField::new('email', 'Email'),
            ChoiceField::new('roles', 'Roles')->setChoices($roles)->allowMultipleChoices(true),
        ];
    }
    
}
