<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, Request $request, ProductRepository $productRepository, SessionInterface $session, FlashBagInterface $flashBag, CartService $cartService)
    {

        // 0. Securisation : Est ce que le produit existe ? 
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createAccessDeniedException("Le produit $id n'existe pas ! ");
        }

     $cartService->add($id);

        $this->addFlash('success', "Le produit a bien été ajouté au panier");

        // ou en appelant le FlashBagInterface dans la méthode :
        //   $flashBag->add('success', "Le produit a bien été ajouté au panier");

        
        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
     
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(SessionInterface $session, ProductRepository $productRepository, CartService $cartService){

       $detailedCart = $cartService->getDetailedCartItems();
        $total = $cartService->getTotal();
 
        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }
}
