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

use Exception;
use Psr\Log\LoggerInterface;
use Surfnet\StepupMiddlewareClient\Identity\Dto\IdentitySearchQuery;
use Surfnet\StepupMiddlewareClientBundle\Identity\Command\CreateIdentityCommand;
use Surfnet\StepupMiddlewareClientBundle\Identity\Command\UpdateIdentityCommand;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity;
use Surfnet\StepupMiddlewareClientBundle\Identity\Service\IdentityService as ApiIdentityService;
use Surfnet\StepupMiddlewareClientBundle\Service\CommandService;
use Surfnet\StepupRa\RaBundle\Exception\RuntimeException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class IdentityService implements UserProviderInterface
{
    /**
     * @var \Surfnet\StepupMiddlewareClientBundle\Identity\Service\IdentityService
     */
    private $apiIdentityService;

    /**
     * @var \Surfnet\StepupMiddlewareClientBundle\Service\CommandService
     */
    private $commandService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        ApiIdentityService $apiIdentityService,
        CommandService $commandService,
        LoggerInterface $logger
    ) {
        $this->apiIdentityService = $apiIdentityService;
        $this->commandService = $commandService;
        $this->logger = $logger;
    }

    /**
     * For now this functionality is disabled, unsure if actually needed
     *
     * If needed, the username is the UUID of the identity so it can be fetched rather easy
     */
    public function loadUserByUsername($username)
    {
        throw new RuntimeException(sprintf('Cannot Load User By Username "%s"', $username));
    }

    /**
     * For now this functionality is disabled, unsure if actually needed
     */
    public function refreshUser(UserInterface $user)
    {
        throw new RuntimeException(sprintf('Cannot Refresh User "%s"', $user->getUsername()));
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity';
    }

    /**
     * @param string $nameId
     * @param string $institution
     * @return null|\Surfnet\StepupMiddlewareClientBundle\Identity\Dto\Identity
     * @throws \Surfnet\StepupRa\RaBundle\Exception\RuntimeException
     */
    public function findByNameIdAndInstitution($nameId, $institution)
    {
        $searchQuery = new IdentitySearchQuery($institution);
        $searchQuery->setNameId($nameId);

        try {
            $result = $this->apiIdentityService->search($searchQuery);
        } catch (Exception $e) {
            $message = sprintf('Exception when searching identity: "%s"', $e->getMessage());
            $this->logger->critical($message);
            throw new RuntimeException($message, 0, $e);
        }

        $elements = $result->getElements();
        if (count($elements) === 0) {
            return null;
        }

        if (count($elements) === 1) {
            return reset($elements);
        }

        throw new RuntimeException(sprintf(
            'Got an unexpected amount of identities, expected 0 or 1, got "%d"',
            count($elements)
        ));
    }

    /**
     * Save or Update an existing Identity
     *
     * @param Identity $identity
     */
    public function createIdentity(Identity $identity)
    {
        $command = new CreateIdentityCommand();
        $command->id          = $identity->id;
        $command->nameId      = $identity->nameId;
        $command->institution = $identity->institution;
        $command->email       = $identity->email;
        $command->commonName  = $identity->commonName;

        $this->processCommand($command);
    }

    /**
     * @param Identity $identity
     */
    public function updateIdentity(Identity $identity)
    {
        $command = new UpdateIdentityCommand($identity->id);
        $command->email      = $identity->email;
        $command->commonName = $identity->commonName;

        $this->processCommand($command);
    }

    /**
     * @param $command
     */
    public function processCommand($command)
    {
        $messageTemplate = 'Exception when saving Identity[%s]: with command "%s", error: "%s"';

        try {
            $result = $this->commandService->execute($command);
        } catch (Exception $e) {
            $message = sprintf($messageTemplate, $command->id, get_class($command), $e->getMessage());
            $this->logger->critical($message);

            throw new RuntimeException($message, 0, $e);
        }

        if (!$result->isSuccessful()) {
            $note = sprintf($messageTemplate, $command->id, get_class($command), implode('", "', $result->getErrors()));
            $this->logger->critical($note);

            throw new RuntimeException($note);
        }
    }

    /**
     * @param Identity $identity
     * @return \Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RegistrationAuthorityCredentials|null
     */
    public function getRaCredentials(Identity $identity)
    {
        try {
            $credentials = $this->apiIdentityService->getRegistrationAuthorityCredentials($identity);
        } catch (Exception $e) {
            $message = sprintf('Exception when retrieving RA credentials: "%s"', $e->getMessage());
            $this->logger->critical($message);

            throw new RuntimeException($message, 0, $e);
        }

        return $credentials;
    }
}