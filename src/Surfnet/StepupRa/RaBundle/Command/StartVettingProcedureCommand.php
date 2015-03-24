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

namespace Surfnet\StepupRa\RaBundle\Command;

use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\VerifiedSecondFactor;
use Symfony\Component\Validator\Constraints as Assert;

class StartVettingProcedureCommand
{
    /**
     * The ID of the authority that will vet a second factor.
     *
     * @var string
     */
    public $authorityId;

    /**
     * The LoA of the authority.
     *
     * @var \Surfnet\StepupBundle\Value\Loa
     */
    public $authorityLoa;

    /**
     * @Assert\NotBlank(message="ra.start_vetting_procedure.registration_code.may_not_be_empty")
     * @Assert\Type(type="string", message="ra.start_vetting_procedure.registration_code.must_be_string")
     *
     * @var string
     */
    public $registrationCode;

    /**
     * @var VerifiedSecondFactor
     */
    public $secondFactor;
}
