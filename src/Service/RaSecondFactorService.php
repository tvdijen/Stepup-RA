<?php

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupRa\RaBundle\Service;

use Psr\Log\LoggerInterface;
use Surfnet\StepupMiddlewareClient\Identity\Dto\RaSecondFactorSearchQuery;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RaSecondFactorCollection;
use Surfnet\StepupMiddlewareClientBundle\Identity\Service\RaSecondFactorService as ApiRaSecondFactorService;
use Surfnet\StepupMiddlewareClientBundle\Service\CommandService;

class RaSecondFactorService
{
    /**
     * @var ApiRaSecondFactorService
     */
    private $apiRaSecondFactorService;

    /**
     * @var CommandService
     */
    private $commandService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ApiRaSecondFactorService $apiRaSecondFactorService
     * @param CommandService $commandService
     * @param LoggerInterface $logger
     */
    public function __construct(
        ApiRaSecondFactorService $apiRaSecondFactorService,
        CommandService $commandService,
        LoggerInterface $logger
    ) {
        $this->apiRaSecondFactorService = $apiRaSecondFactorService;
        $this->commandService = $commandService;
        $this->logger = $logger;
    }

    /**
     * @param RaSecondFactorSearchQuery $query
     * @return RaSecondFactorCollection
     */
    public function search(RaSecondFactorSearchQuery $query)
    {
        return $this->apiRaSecondFactorService->search($query);
    }
}
