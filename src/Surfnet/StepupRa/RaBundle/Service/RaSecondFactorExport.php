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

use EE\DataExporterBundle\Service\DataExporter;
use Psr\Log\LoggerInterface;
use Surfnet\StepupMiddlewareClientBundle\Identity\Dto\RaSecondFactorExportCollection;

class RaSecondFactorExport
{
    /**
     * @var DataExporter
     */
    private $exporter;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        DataExporter $exporter,
        LoggerInterface $logger
    ) {
        $this->exporter = $exporter;
        $this->logger = $logger;
    }

    public function export(RaSecondFactorExportCollection $collection, $fileName)
    {
        $this->logger->notice(sprintf('Exporting %d rows to "%s"', $collection->count(), $fileName));

        $this->exporter->setOptions('csv', ['fileName' => $fileName]);
        $this->exporter->setColumns($collection->getColumnNames());
        $this->exporter->setData($collection->getElements());

        return $this->exporter->render();
    }
}
