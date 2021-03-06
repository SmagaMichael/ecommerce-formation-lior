<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom du produit',
            'attr' => [
                'placeholder' => 'Tapez le nom du produit'
            ],
            'required' => false,

        ])

            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description courte',
                'attr' => [
                    'placeholder' => 'Tapez une description courte mais parlante pour le visiteur'
                ],
               


            ])

            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit',
                'attr' => [
                    'placeholder' => 'Tapez le prix du produit en €'
                ],
                'divisor' => 100,
                'required' => false,
            ])


            ->add('mainPicture', UrlType::class,[
                'label' => 'image du produit',
                'attr' => ['placeholder' => 'Tapez une Url d\'image !']
            ])



            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                }
            ])

            ;

            // VERSION TRANSFORMER
            // $builder->get('price')->addModelTransformer(new CentimesTransformer);

            // VERSION EVENT
            // permet de remettre en centime dans la bdd avec ce que l'utilisateur a passé comme prix  
            // $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
            //     $product = $event->getData();

            //     if($product->getPrice()!== null){
            //         $product->setPrice($product->getPrice() * 100);
            //     }
            // });


            // permet a l'utilisateur de mettre un prix en euro et pas en centime afin de ne pas calculer soit meme 
            // $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){

            //     $form = $event->getForm();

            //     /** @var Product */
            //     $product = $event->getData();

            //     if($product->getPrice()!== null){
            //         $product->setPrice($product->getPrice() / 100);
            //     }




                // SI ON VEUT MASQUER UN CHAMP SI ON EST SUR EDIT  ET LE LAISSER SUR CREATE :

                // if($product->getId() === null){
                //     $form->add('category', EntityType::class, [
                //         'label' => 'Catégorie',
                //         'placeholder' => '-- Choisir une catégorie --',
                //         'class' => Category::class,
                //         'choice_label' => function (Category $category) {
                //             return strtoupper($category->getName());
                //         }
                //     ]);
                // }
            
    //         });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
