<?php

declare(strict_types=1);

namespace App\Filters;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

class ProfileRoleFilter extends AbstractFilter
{
    public function getDescription(string $resourceClass): array
    {
        return [
            'isNotRole' => [
                'property' => true,
                'type' => 'string',
                'required' => false,
            ]
        ];
    }

    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        Operation $operation = null,
        array $context = []
    ): void {
        if ($property !== 'isNotRole') {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->leftJoin("$alias.user", 'user')
            ->andWhere("user.roles NOT LIKE :sear")
            ->setParameter("sear", "%$value%");
    }
}
