<?php

namespace App\Controller\Product;

use App\Entity\Product;
use App\Form\Product\Step\ProductTypeStepType;
use App\Form\Product\Step\ProductDetailsStepType;
use App\Form\Product\Step\ProductLogisticsStepType;
use App\Form\Product\Step\ProductLicenseStepType;
use App\Form\Product\Step\ProductConfirmationStepType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product/create')]
class ProductCreationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/step-1-type', name: 'product_creation_step1')]
    public function step1(Request $request, SessionInterface $session): Response
    {
        $product = $session->get('product', new Product());
        $form = $this->createForm(ProductTypeStepType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('product', $product);
            return $this->redirectToRoute('product_creation_step2');
        }

        return $this->render('product/creation/step1.html.twig', [
            'form' => $form->createView(),
            'steps' => $this->getSteps($product),
            'current_step' => 'Type',
        ]);
    }

    #[Route('/step-2-details', name: 'product_creation_step2')]
    public function step2(Request $request, SessionInterface $session): Response
    {
        $product = $session->get('product');
        if (!$product) {
            return $this->redirectToRoute('product_creation_step1');
        }

        $form = $this->createForm(ProductDetailsStepType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('product', $product);
            return $this->redirectToRoute($this->getNextStepRoute('step2', $product));
        }

        return $this->render('product/creation/step2.html.twig', [
            'form' => $form->createView(),
            'steps' => $this->getSteps($product),
            'current_step' => 'Details',
        ]);
    }

    #[Route('/step-3-logistics', name: 'product_creation_logistics')]
    public function logistics(Request $request, SessionInterface $session): Response
    {
        $product = $session->get('product');
        if (!$product || $product->getType() !== 'physical') {
            return $this->redirectToRoute('product_creation_step1');
        }

        $form = $this->createForm(ProductLogisticsStepType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('product', $product);
             return $this->redirectToRoute($this->getNextStepRoute('logistics', $product));
        }

        return $this->render('product/creation/step3.html.twig', [
            'form' => $form->createView(),
            'steps' => $this->getSteps($product),
            'current_step' => 'Logistics',
        ]);
    }

    #[Route('/step-4-license', name: 'product_creation_license')]
    public function license(Request $request, SessionInterface $session): Response
    {
        $product = $session->get('product');
        if (!$product || $product->getType() !== 'digital') {
            return $this->redirectToRoute('product_creation_step1');
        }

        $form = $this->createForm(ProductLicenseStepType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('product', $product);
            return $this->redirectToRoute($this->getNextStepRoute('license', $product));
        }

        return $this->render('product/creation/step4.html.twig', [
            'form' => $form->createView(),
            'steps' => $this->getSteps($product),
            'current_step' => 'License',
        ]);
    }

    #[Route('/step-5-confirmation', name: 'product_creation_confirmation')]
    public function confirmation(Request $request, SessionInterface $session): Response
    {
        $product = $session->get('product');
        if (!$product) {
            return $this->redirectToRoute('product_creation_step1');
        }

        $form = $this->createForm(ProductConfirmationStepType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('product', $product);
            return $this->redirectToRoute('product_creation_summary');
        }

        return $this->render('product/creation/step5.html.twig', [
            'form' => $form->createView(),
            'steps' => $this->getSteps($product),
            'current_step' => 'Confirmation',
        ]);
    }

    #[Route('/summary', name: 'product_creation_summary')]
    public function summary(Request $request, SessionInterface $session): Response
    {
        $product = $session->get('product');
        if (!$product) {
            return $this->redirectToRoute('product_creation_step1');
        }

        if ($request->isMethod('POST')) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $session->remove('product');

            $this->addFlash('success', 'Product created successfully!');
            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/creation/summary.html.twig', [
            'product' => $product,
            'steps' => $this->getSteps($product),
            'current_step' => 'Summary',
        ]);
    }

    private function getNextStepRoute(string $currentStep, Product $product): string
    {
        switch ($currentStep) {
            case 'step2':
                if ($product->getType() === 'physical') {
                    return 'product_creation_logistics';
                } else {
                    return 'product_creation_license';
                }
            case 'logistics':
            case 'license':
                if ($product->getPrice() > 1000) {
                    return 'product_creation_confirmation';
                }
                return 'product_creation_summary';
            default:
                return 'product_creation_step1';
        }
    }

    private function getSteps(Product $product): array
    {
        $steps = ['Type', 'Details'];

        if ($product->getType() === 'physical') {
            $steps[] = 'Logistics';
        } elseif ($product->getType() === 'digital') {
            $steps[] = 'License';
        }

        if ($product->getPrice() > 1000) {
            $steps[] = 'Confirmation';
        }

        $steps[] = 'Summary';

        return $steps;
    }
}
