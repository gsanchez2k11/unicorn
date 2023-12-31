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

namespace Google\Service\Contentwarehouse;

class AssistantApiLiveTvProvider extends \Google\Model
{
  /**
   * @var AssistantApiCoreTypesProvider
   */
  public $providerInfo;
  protected $providerInfoType = AssistantApiCoreTypesProvider::class;
  protected $providerInfoDataType = '';
  /**
   * @var string
   */
  public $providerKey;
  /**
   * @var string
   */
  public $providerType;

  /**
   * @param AssistantApiCoreTypesProvider
   */
  public function setProviderInfo(AssistantApiCoreTypesProvider $providerInfo)
  {
    $this->providerInfo = $providerInfo;
  }
  /**
   * @return AssistantApiCoreTypesProvider
   */
  public function getProviderInfo()
  {
    return $this->providerInfo;
  }
  /**
   * @param string
   */
  public function setProviderKey($providerKey)
  {
    $this->providerKey = $providerKey;
  }
  /**
   * @return string
   */
  public function getProviderKey()
  {
    return $this->providerKey;
  }
  /**
   * @param string
   */
  public function setProviderType($providerType)
  {
    $this->providerType = $providerType;
  }
  /**
   * @return string
   */
  public function getProviderType()
  {
    return $this->providerType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AssistantApiLiveTvProvider::class, 'Google_Service_Contentwarehouse_AssistantApiLiveTvProvider');
