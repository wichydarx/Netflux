<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
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

        $fields = [
            EmailField::new('email', 'Email'),
            TextField::new('lastname', 'Nom'),
            TextField::new('firstname', 'Prénom'),
            ChoiceField::new('roles', 'Roles')->setChoices($roles)->allowMultipleChoices(true),
        ];


        if (Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) {
            $fields[] = TextField::new('password', 'Mot de passe')
                ->setFormType(PasswordType::class)
                ->hideOnIndex();
        }

        return $fields;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Hachage du mot de passe
        if ($entityInstance instanceof User) {
            $plainPassword = $entityInstance->getPassword();
            if (!empty($plainPassword)) {
                $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
                $entityInstance->setPassword($hashedPassword);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Hachage du mot de passe à la création
        if ($entityInstance instanceof User) {
            $plainPassword = $entityInstance->getPassword();
            if (!empty($plainPassword)) {
                $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
                $entityInstance->setPassword($hashedPassword);
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
