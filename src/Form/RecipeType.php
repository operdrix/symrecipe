<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RecipeType extends AbstractType
{

    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $submitLabel = 'Ajouter ma recette';
        if (strpos($options['action'], 'edition') !== false) {
            $submitLabel = 'Enregistrer les modifications';
        }
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank(),
                ],
            ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => '1',
                    'max' => '1440'
                ],
                'label' => 'Temps de préparation (en minutes)',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'required' => false,
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1441)
                ],
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => '1',
                    'max' => '50'
                ],
                'label' => 'Nombre de personnes',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'required' => false,
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(51)
                ],
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => '1',
                    'max' => '5'
                ],
                'label' => 'Difficulté',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'required' => false,
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(51)
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'required' => false,
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1001)
                ],
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input mt-4'
                ],
                'label' => 'Recette favorite ?',
                'label_attr' => [
                    'class' => 'form-check-label mt-4',
                ],
                'required' => false,
            ])
            ->add('ingredients', EntityType::class, [
                'class' => 'App\Entity\Ingredient',
                'query_builder' => function (IngredientRepository $r) {
                    return $r->createQueryBuilder('i')
                        ->where('i.user = :user')
                        ->orderBy('i.name', 'ASC')
                        ->setParameter('user', $this->token->getToken()->getUser());
                },
                'label' => 'Les ingrédients',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
                'attr' => [
                    'class' => 'form-control d-flex flex-wrap'
                ],
                'choice_label' => 'name',
                'choice_attr' => function ($choice, $key, $value) {
                    return ['class' => 'form-check-input mx-2'];
                },
                'multiple' => true,
                'expanded' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => $submitLabel,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'action' => null,
        ]);
    }
}
