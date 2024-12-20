<?php

declare(strict_types=1);

namespace App\Controller\PasswordGetAction;

use App\Component\User\password\HashPasswordFactory;
use App\Controller\Base\AbstractController;
use App\Entity\Password;
use App\Repository\PasswordRepository;
use Symfony\Component\HttpFoundation\Response;

class PasswordGetAction extends AbstractController
{
    public function __invoke(
        Password $password,
        PasswordRepository $passwordRepository,
        HashPasswordFactory $passwordFactory
    ): Response {
        $res = $passwordRepository->findOneByUser((int)$password->getPassword());

        $result = $passwordFactory->decrypt($res->getPassword());

        return $this->responseNormalized([
            "password" => $result
        ]);
    }
}
