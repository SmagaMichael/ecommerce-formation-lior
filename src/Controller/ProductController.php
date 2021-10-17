<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController
{


    #[Route('/{slug}', name: 'product_category')]
    public function category($slug, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw new NotFoundHttpException("La catégorie demandée n'existe pas !");
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }




    #[Route('/{category_slug}/{slug}', name: 'product_show')]
    public function show($slug, ProductRepository $productRepository)
    {
        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$product) {
            throw new NotFoundHttpException("le produit demandé n'existe pas !");
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }






    #[Route('/admin/product/create', name: 'product_create')]
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->persist($product);
            $em->flush();


            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }




    #[Route('/admin/product/{id}/edit', name: 'product_edit')]
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {


        // Exemple de Validation pour données complexes
        // $client = [
        //     'nom' => '',
        //     'prenom' => 'Lior',
        //     'voiture' => [
        //         'marque' => '',
        //         'couleur' => 'Noire'
        //     ]
        // ];
        // $collection = new Collection([
        //     'nom' => new NotBlank(['message' => "Le nom ne doit pas être vide"]),
        //     'prenom' => [
        //         new NotBlank(['message' => "Le nom ne doit pas être vide"]),
        //         new Length(['min' => 3, 'minMessage' => "Le prenom ne doit pas faire moins de 34 caractères"])
        //     ],
        //     'voiture' => new Collection([
        //         'marque' => new NotBlank(['message' => "La marque de la voiture est obligatoire"]),
        //         'couleur' => new NotBlank(['message' => "La couleur de la voiture est obligatoire"])
        //     ])
        // ]);
        // $resultat = $validator->validate($client, $collection);
        //        if ($resultat->count() > 0) {
        //     dd("Il y a des erreurs", $resultat);
        // }
        // dd("Tout va bien");



        // Exemple de Validation pour données simples
        // $age = 150;
        // $resultat = $validator->validate($age, [
        //     new LessThanOrEqual([
        //         'value' => 120,
        //         'message' => "L'âge doit être inférieur à {{ compared_value }} mais vous avez donné {{ value }}"
        //     ]),
        //     new GreaterThan([
        //         'value' => 0,
        //         'message' => "L'âge doit être supérieur à 0"
        //     ])
        // ]);
        // if ($resultat->count() > 0) {
        //     dd("Il y a des erreurs");
        // }
        // dd("Tout va bien");
        // ---------------------------------------------------------

        // Validation grace au fichier yaml
        //     $product = new Product;
        //     $resultat = $validator->validate($product);
        //    if ($resultat->count() > 0) {
        //         dd("Il y a des erreurs", $resultat);
        //     }
        //     dd("Tout va bien");


        // ---------------------------------------------------------

        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product,[
            "validation_groups" => ["Default", "large-name" ,"with-price"]
        ]);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            // $url = $urlGenerator->generate('product_show',[
            //     'category_slug' => $product->getCategory()->getSlug(),
            //     'slug' => $product->getSlug(),
            // ]);
            // $response->headers->set('Location',$url);
            // $response->setStatusCode(302);

            // $response = new RedirectResponse($url);
            // return $response;

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug(),
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView
        ]);
    }
}
