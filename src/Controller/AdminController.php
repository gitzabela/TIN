<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\UserDoesNotExist;
use App\Repository\UserEmailIsAlreadyConfirmed;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController as BaseAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminController extends BaseAdminController
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return RedirectResponse
     */
    public function confirmAction(): RedirectResponse
    {
        $id = $this->request->query->get('id');
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->findOneBy(['id' => $id]);
        try {
            $userRepository->confirmUserEmail($user->getConfirmationToken());
            $this->addFlash('success', 'You have successfully confirmed an user');
        } catch (UserDoesNotExist|UserEmailIsAlreadyConfirmed $e) {
            $this->addFlash('warning', 'User is already confirmed!');
        }

        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => $this->request->query->get('entity'),
        ]);
    }
}
