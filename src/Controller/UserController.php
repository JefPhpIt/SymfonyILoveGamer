<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_show', methods: ['GET'])]
    #[Route('/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function show(Request $request, EntityManagerInterface $entityManager): Response
    {
        $routeName = $request->attributes->get('_route');
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user , [
            'disabled' => !($routeName === 'app_user_edit')
        ]);
        $form->handleRequest($request);

        if ($routeName === 'app_user_edit') {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                $this->addFlash('suucess', 'Profile mise à jour');
                return $this->redirectToRoute('app_user_show', [], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->render('user/show.html.twig', [
            'user' => $this->getUser(),
            'form' => $form
        ]);

    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_register', [], Response::HTTP_SEE_OTHER);
    }
}
