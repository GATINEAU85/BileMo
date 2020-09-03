<?php

namespace App\Paging;

use App\Exceptions\ApiException;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class UserPaging
{
    private const NB_USER_PAGED = 5;
    private $userRepository;
    private $security;
    private $nbUser;
    private $maxPages;
    private $idUserClient;

    public function __construct(UserRepository $userRepository, Security $security) {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->idUserClient = $this->security->getUser()->getId();
        $this->nbUser = $this->userRepository->count([]);
        $this->maxPages = intval(ceil($this->nbUser / self::NB_USER_PAGED));
    }

    public function getDatas($page)
    {
        if (null === $page) {
            return $user = $this->userRepository->findAll();
        }

        if (1 > $page || $page > $this->maxPages) {
            throw new ApiException('The page must be between 1 and '.$this->maxPages.'.', 404);
        }

        $offset = self::NB_USER_PAGED * ($page - 1);

        return $user = $this->repository->findBy([], [], self::NB_USER_PAGED, $offset);
    }
}