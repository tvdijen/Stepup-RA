<?php

/**
 * Copyright 2018 SURFnet bv
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

namespace Surfnet\StepupRa\RaBundle\Form\Extension;


use Psr\Log\LoggerInterface;
use Surfnet\StepupBundle\Service\SecondFactorTypeService;
use Surfnet\StepupRa\SamlStepupProviderBundle\Provider\ViewConfig;
use Surfnet\StepupRa\SamlStepupProviderBundle\Provider\ViewConfigCollection;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Used to build a choice list of second factor types
 *
 * Second factor types are indexed on their identifier. Some examples: 'sms', 'tiqr', 'u2f'. These not very human
 * readable keys are linked to a more human readable value which is read from the translator. This results in an
 * associative array like this:
 *
 * [
 *     'sms' => 'SMS',
 *     'yubi' => 'Yubikey',
 *     'tiqr' => 'Tiqr'
 * ]
 *
 * Translations should be provided in the translations file for this project and should follow the format specified in
 * the 'translationIdFormat' field.
 *
 * A message is logged when the second factor type id cannot be translated. Second factor type id's that cannot be
 * translated, are not added to the choice list.
 */
class SecondFactorTypeChoiceList
{
    private $translationIdFormat = 'ra.form.ra_search_ra_second_factors.choice.type.%s';
    /**
     * @var SecondFactorTypeService
     */
    private $secondFactorTypeService;

    /**
     * @var ViewConfig
     */
    private $gsspConfigCollection;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        SecondFactorTypeService $service,
        ViewConfigCollection $gsspConfigCollection,
        TranslatorInterface $translator,
        LoggerInterface $logger
    ) {
        $this->secondFactorTypeService = $service;
        $this->gsspConfigCollection = $gsspConfigCollection;
        $this->translator = $translator;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function create()
    {
        $selectOptions = [];
        $collection = $this->secondFactorTypeService->getAvailableSecondFactorTypes();

        sort($collection);

        foreach ($collection as $sfTypeIdentifier) {
            $translationId = sprintf($this->translationIdFormat, $sfTypeIdentifier);

            if ($this->gsspConfigCollection->isGssp($sfTypeIdentifier)) {
                $translation = $this->gsspConfigCollection
                    ->getByIdentifier($sfTypeIdentifier)
                    ->getName();
            } else {
                $translation = $this->translator->trans($translationId);
            }

            // Test if the translator was able to translate the second factor type
            if ($translationId === $translation) {
                $this->logger->warning(
                    sprintf(
                        'Unable to add a filter option on the second factor type select list for type: "%s"',
                        $sfTypeIdentifier
                    )
                );
                continue;
            }
            $selectOptions[$sfTypeIdentifier] = $translation;
        }

        return $selectOptions;
    }
}
