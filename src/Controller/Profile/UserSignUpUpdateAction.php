<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Component\Profile\Dto\SignUpRequestDto;
use App\Component\Profile\Services\ProfileUpdateService;
use App\Controller\Base\AbstractController;
use App\Entity\Profile;
use App\Repository\ProfileRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Profile findEntityOrError(ServiceEntityRepository $repository, int $id)
 */
class UserSignUpUpdateAction extends AbstractController
{

    public function __invoke(
        int $id,
        Request $request,
        ProfileUpdateService $profileUpdateService,
        ProfileRepository $profileRepository
    ): Profile {
        $profile = $this->findEntityOrError($profileRepository, $id);
        $requestDto = $this->convertRequestToDto($request);

        return $profileUpdateService->update($profile, $requestDto);
    }

    private function convertRequestToDto(Request $request): SignUpRequestDto
    {
        /** @var SignUpRequestDto $singUpRequestDto */
        $singUpRequestDto = $this->getDtoFromRequest($request, SignUpRequestDto::class);
        $this->validate($singUpRequestDto);

        return $singUpRequestDto;
    }
}
