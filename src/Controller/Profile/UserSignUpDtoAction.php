<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Component\Profile\Builders\DirectorUsers;
use App\Component\Profile\Dto\SignUpRequestDto;
use App\Controller\Base\AbstractController;
use App\Entity\Profile;
use App\Repository\UserRepository;
use Random\RandomException;
use Symfony\Component\HttpFoundation\Request;

class UserSignUpDtoAction extends AbstractController
{
    /**
     * @throws RandomException
     */
    public function __invoke(
        Request $request,
        DirectorUsers $directorUsers,
        UserRepository $userRepository,
    ): Profile {
        $singUpRequestDto = $this->convertRequestToDto($request);
        return $directorUsers->createSignUpUserAndSave($singUpRequestDto);
    }


    private function convertRequestToDto(Request $request): SignUpRequestDto
    {
        /** @var SignUpRequestDto $singUpRequestDto */
        $singUpRequestDto = $this->getDtoFromRequest($request, SignUpRequestDto::class);
        $this->validate($singUpRequestDto);

        return $singUpRequestDto;
    }

}
