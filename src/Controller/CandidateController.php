<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateApplicationFlow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CandidateController extends AbstractController
{
    private $flow;

    public function __construct(CandidateApplicationFlow $flow)
    {
        $this->flow = $flow;
    }

    #[Route("/apply", name: "app_apply")]
    public function start(SessionInterface $session): Response
    {
        $session->remove('candidate_id');
        return $this->redirectToRoute('app_apply_step', ['step' => 1]);
    }

    #[Route("/apply/{step}", name: "app_apply_step", requirements: ["step" => "\d+"])]
    public function step(int $step, Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $candidateId = $session->get('candidate_id');
        $candidate = $candidateId ? $entityManager->getRepository(Candidate::class)->find($candidateId) : new Candidate();

        if (!$candidate) {
            $session->remove('candidate_id');
            return $this->redirectToRoute('app_apply');
        }

        if ($step > $this->flow->getTotalSteps()) {
            return $this->redirectToRoute('app_apply_summary');
        }

        $stepConfig = $this->flow->getStep($step);
        if (!$stepConfig) {
            throw $this->createNotFoundException('Invalid step');
        }

        // Skip experience step if not needed
        if (isset($stepConfig['is_conditional']) && $stepConfig['is_conditional'] && !$candidate->isHasExperience()) {
            return $this->redirectToRoute('app_apply_step', ['step' => $step + 1]);
        }

        $form = $this->createForm($this->flow->getFormTypeForStep($step), $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$candidate->getId()) {
                $entityManager->persist($candidate);
            }
            $entityManager->flush();
            $session->set('candidate_id', $candidate->getId());

            return $this->redirectToRoute('app_apply_step', ['step' => $step + 1]);
        }

        return $this->render('application/step.html.twig', [
            'form' => $form->createView(),
            'step' => $step,
            'total_steps' => $this->flow->getTotalSteps(),
            'candidate' => $candidate, // Pass candidate to the view
        ]);
    }

    #[Route("/apply/summary", name: "app_apply_summary")]
    public function summary(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $candidateId = $session->get('candidate_id');
        if (!$candidateId) {
            return $this->redirectToRoute('app_apply');
        }
        $candidate = $entityManager->getRepository(Candidate::class)->find($candidateId);

        if ($request->isMethod('POST')) {
            $candidate->setStatus('submitted');
            $entityManager->flush();
            $session->remove('candidate_id');

            $this->addFlash('success', 'Your application has been submitted successfully!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('application/summary.html.twig', [
            'candidate' => $candidate,
        ]);
    }
}
