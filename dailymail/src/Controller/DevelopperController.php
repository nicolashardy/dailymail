<?php

namespace App\Controller;

use App\Entity\Developper;
use App\Form\DevelopperType;
use App\Repository\DevelopperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/developper")
 */
class DevelopperController extends Controller
{
    /**
     * @Route("/", name="developper_index", methods="GET")
     */
    public function index(DevelopperRepository $developperRepository): Response
    {
        return $this->render('developper/index.html.twig', ['developpers' => $developperRepository->findAll()]);
    }

    /**
     * @Route("/new", name="developper_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $developper = new Developper();
        $form = $this->createForm(DevelopperType::class, $developper);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($developper);
            $em->flush();

            return $this->redirectToRoute('developper_index');
        }

        return $this->render('developper/new.html.twig', [
            'developper' => $developper,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="developper_show", methods="GET")
     */
    public function show(Developper $developper): Response
    {
        return $this->render('developper/show.html.twig', ['developper' => $developper]);
    }

    /**
     * @Route("/{id}/edit", name="developper_edit", methods="GET|POST")
     */
    public function edit(Request $request, Developper $developper): Response
    {
        $form = $this->createForm(DevelopperType::class, $developper);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('developper_edit', ['id' => $developper->getId()]);
        }

        return $this->render('developper/edit.html.twig', [
            'developper' => $developper,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="developper_delete", methods="DELETE")
     */
    public function delete(Request $request, Developper $developper): Response
    {
        if ($this->isCsrfTokenValid('delete'.$developper->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($developper);
            $em->flush();
        }

        return $this->redirectToRoute('developper_index');
    }
}
