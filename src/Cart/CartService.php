<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    protected function getCart() : array{
        return $this->session->get('cart', []);
    }

    protected function saveCart(array $cart){
        $this->session->set('cart', $cart);
    }


    public function empty()
    {
        $this->saveCart([]);
    }

    public function add(int $id)
    {

        // 1. Retrouver le panier dans la session sous forme de tableau
        // 2. Si il n'existe pas encore, alors prendre un tableau vide
        $cart = $this->getCart();

        // 3.  Voir si le produit ($id) existe déjà dans le tableau
        // 4. Si c'est le cas, simplement augmenter la quantité
        // 5. Sinon, ajouter le produit avec la quantité 1
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        } 

        $cart[$id]++;

        // 6. Enregistrer le tableau mis à jour dans la session
        $this->saveCart($cart);
        // $request->getSession()->remove('cart');
    }



    public function getTotal(): int
    {
        $total = 0;
        foreach ($this->getCart() as $id => $qty) {
            $product =  $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += ($product->getPrice() * $qty);
        }
        return $total;
    }


    /** 
     * 
     * @return cartItem[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];
        //ce qu'on veut : [12 => ['product' => ..., 'quantity' => qté]]
        foreach ($this->getCart() as $id => $qty) {
            $product =  $this->productRepository->find($id);

            if (!$product) {
                continue;
            }
            $detailedCart[] = new CartItem($product, $qty);
        }
        return $detailedCart;
    }




    public function remove(int $id){
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->saveCart($cart);
    }





    public function decrement(int $id){
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        //soit le produit est a 1 et il faut le supprimé
        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }

        //soit le produit est a + de 1 et il faut le décrémenté
        $cart[$id]--;
        $this->saveCart($cart);

    }

}
