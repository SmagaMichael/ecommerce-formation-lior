<?php

namespace App\Controller;

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
    public function add($id, Request $request, ProductRepository $productRepository, SessionInterface $session, FlashBagInterface $flashBag)
    {

        // 0. Securisation : Est ce que le produit existe ? 
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createAccessDeniedException("Le produit $id n'existe pas ! ");
        }

        // 1. Retrouver le panier dans la session sous forme de tableau
        // 2. Si il n'existe pas encore, alors prendre un tableau vide
            $cart = $session->get('cart', []);

        // 3.  Voir si le produit ($id) existe déjà dans le tableau
        // 4. Si c'est le cas, simplement augmenter la quantité
        // 5. Sinon, ajouter le produit avec la quantité 1
        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }


        // 6. Enregistrer le tableau mis à jour dans la session
        $session->set('cart',$cart);
        // $request->getSession()->remove('cart');

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
    public function show(SessionInterface $session, ProductRepository $productRepository){

        $detailedCart = [];
        //ce qu'on veut : [12 => ['product' => ..., 'quantity' => qté]]

        $total = 0;

        foreach($session->get('cart', []) as $id => $qty){
            $product = $productRepository->find($id);
            
            $detailedCart[] = [
                'product' => $product,
                'qty' => $qty
            ];

            $total += ($product->getPrice() * $qty);
        }

        // dd($session->get('cart'));
        // dd($detailedCart);
        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }
}
