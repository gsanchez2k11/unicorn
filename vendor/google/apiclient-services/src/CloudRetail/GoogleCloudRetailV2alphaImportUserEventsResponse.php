<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\CloudRetail;

class GoogleCloudRetailV2alphaImportUserEventsResponse extends \Google\Collection
{
  protected $collection_key = 'errorSamples';
  /**
   * @var GoogleRpcStatus[]
   */
  public $errorSamples;
  protected $errorSamplesType = GoogleRpcStatus::class;
  protected $errorSamplesDataType = 'array';
  /**
   * @var GoogleCloudRetailV2alphaImportErrorsConfig
   */
  public $errorsConfig;
  protected $errorsConfigType = GoogleCloudRetailV2alphaImportErrorsConfig::class;
  protected $errorsConfigDataType = '';
  /**
   * @var GoogleCloudRetailV2alphaUserEventImportSummary
   */
  public $importSummary;
  protected $importSummaryType = GoogleCloudRetailV2alphaUserEventImportSummary::class;
  protected $importSummaryDataType = '';

  /**
   * @param GoogleRpcStatus[]
   */
  public function setErrorSamples($errorSamples)
  {
    $this->errorSamples = $errorSamples;
  }
  /**
   * @return GoogleRpcStatus[]
   */
  public function getErrorSamples()
  {
    return $this->errorSamples;
  }
  /**
   * @param GoogleCloudRetailV2alphaImportErrorsConfig
   */
  public function setErrorsConfig(GoogleCloudRetailV2alphaImportErrorsConfig $errorsConfig)
  {
    $this->errorsConfig = $errorsConfig;
  }
  /**
   * @return GoogleCloudRetailV2alphaImportErrorsConfig
   */
  public function getErrorsConfig()
  {
    return $this->errorsConfig;
  }
  /**
   * @param GoogleCloudRetailV2alphaUserEventImportSummary
   */
  public function setImportSummary(GoogleCloudRetailV2alphaUserEventImportSummary $importSummary)
  {
    $this->importSummary = $importSummary;
  }
  /**
   * @return GoogleCloudRetailV2alphaUserEventImportSummary
   */
  public function getImportSummary()
  {
    return $this->importSummary;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRetailV2alphaImportUserEventsResponse::class, 'Google_Service_CloudRetail_GoogleCloudRetailV2alphaImportUserEventsResponse');
