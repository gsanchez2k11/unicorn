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

class NlpSemanticParsingLocalContactLocation extends \Google\Model
{
  /**
   * @var NlpSemanticParsingModelsCommunicationRecipient
   */
  public $contact;
  protected $contactDataType = '';
  /**
   * @var NlpSemanticParsingModelsCommunicationPhoneType
   */
  public $contactType;
  protected $contactTypeType = NlpSemanticParsingModelsCommunicationPhoneType::class;
  protected $contactTypeDataType = '';

  /**
   * @param NlpSemanticParsingModelsCommunicationRecipient
   */
  public function setContact(NlpSemanticParsingModelsCommunicationRecipient $contact)
  {
    $this->contact = $contact;
  }
  /**
   * @return NlpSemanticParsingModelsCommunicationRecipient
   */
  public function getContact()
  {
    return $this->contact;
  }
  /**
   * @param NlpSemanticParsingModelsCommunicationPhoneType
   */
  public function setContactType(NlpSemanticParsingModelsCommunicationPhoneType $contactType)
  {
    $this->contactType = $contactType;
  }
  /**
   * @return NlpSemanticParsingModelsCommunicationPhoneType
   */
  public function getContactType()
  {
    return $this->contactType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(NlpSemanticParsingLocalContactLocation::class, 'Google_Service_Contentwarehouse_NlpSemanticParsingLocalContactLocation');
