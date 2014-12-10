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

use Surfnet\StepupRa\RaBundle\VettingProcedure;
use Symfony\Component\Validator\Constraints as Assert;

class SendSmsChallengeCommand
{
    /**
     * @Assert\NotBlank(message="ra.send_sms_challenge_command.phone_number.may_not_be_empty")
     * @Assert\Type(type="string", message="ra.send_sms_challenge_command.phone_number.must_be_string")
     * @Assert\Regex(
     *     pattern="~^\d+$~",
     *     message="ra.send_sms_challenge_command.phone_number.must_be_full_number_with_country_code_no_plus"
     * )
     *
     * The recipient as a string of digits (31612345678 for +31 6 1234 5678).
     *
     * @var string
     */
    public $phoneNumber;

    /**
     * @var string Filled in by the VettingService
     */
    public $expectedPhoneNumber;

    /**
     * The requesting identity's ID (not name ID).
     *
     * @var string Filled in by the VettingService
     */
    public $identity;

    /**
     * The requesting identity's institution.
     *
     * @var string Filled in by the VettingService
     */
    public $institution;
}
