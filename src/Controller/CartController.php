<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCartInfos(),
            'total' => $cartService->getTotal()
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function addToCart(Article $article, CartService $cartService): Response
    {
        $cartService->add($article->getId());
        return $this->redirectToRoute('app_article');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_delete')]
    public function deleteToCart(Article $article, CartService $cartService): Response
    {
        $cartService->remove($article->getId());
        return $this->redirectToRoute('app_cart');
    }
    
}
