<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService {
    private $requestStack;
    private $articleRepository;

    public function __construct(RequestStack $requestStack, ArticleRepository $articleRepository)
    {
        $this->requestStack = $requestStack;
        $this->articleRepository = $articleRepository;
    }

    public function add(int $id)
    {
        $cart = $this->requestStack->getSession()->get('cart');
        !empty($cart[$id]) ? $cart[$id]++ : $cart[$id] = 1;
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function remove(int $id)
    {
        $cart = $this->requestStack->getSession()->get('cart');
        if (!empty($cart[$id])) unset($cart[$id]);
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function getCartInfos()
    {
        $cart= $this->requestStack->getSession()->get('cart');
        $cartInfos = [];
        foreach ($cart as $id => $qty) {
            $cartInfos[] = [
                'article' => $this->articleRepository->find($id),
                'qty' => $qty
            ];
        }
        return $cartInfos;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getCartInfos() as $cartInfo) {
            $total += $cartInfo['article']->getPrice() * $cartInfo['qty'];
        }
        return $total;
    }
}