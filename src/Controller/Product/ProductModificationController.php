<?php

namespace App\Controller\Product;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductModificationController extends AbstractController
{
    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET'])]
    public function edit(Product $product, SessionInterface $session): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $product);

        $session->set('product', $product);

        return $this->redirectToRoute('product_creation_step1');
    }
}
