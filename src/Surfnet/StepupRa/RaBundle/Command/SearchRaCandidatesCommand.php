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

use Symfony\Component\Validator\Constraints as Assert;

class SearchRaCandidatesCommand
{
    /**
     * @Assert\NotBlank(message="ra.search_ra_candidates.institution.blank")
     * @Assert\Type("string", message="ra.search_ra_candidates.institution.type")
     *
     * @var string
     */
    public $institution;

    /**
     * @var string|null
     */
    public $name;

    /**
     * @var string|null
     */
    public $email;

    /**
     * @Assert\Choice(
     *     {"name", "email"},
     *     message="ra.search_ra_candidates.order_by.invalid_choice"
     * )
     *
     * @var string|null
     */
    public $orderBy;

    /**
     * @Assert\Choice({"asc", "desc"}, message="ra.search_ra_candidates.order_direction.invalid_choice")
     *
     * @var string|null
     */
    public $orderDirection;

    /**
     * @Assert\Type("integer", message="ra.search_ra_candidates.page_number.type")
     * @Assert\GreaterThan(0, message="ra.search_ra_candidates.page_number.greater_than_zero")
     *
     * @var int
     */
    public $pageNumber;
}
