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

namespace Google\Service\Speech;

class RecognizeRequest extends \Google\Model
{
  /**
   * @var RecognitionAudio
   */
  public $audio;
  protected $audioType = RecognitionAudio::class;
  protected $audioDataType = '';
  /**
   * @var RecognitionConfig
   */
  public $config;
  protected $configType = RecognitionConfig::class;
  protected $configDataType = '';

  /**
   * @param RecognitionAudio
   */
  public function setAudio(RecognitionAudio $audio)
  {
    $this->audio = $audio;
  }
  /**
   * @return RecognitionAudio
   */
  public function getAudio()
  {
    return $this->audio;
  }
  /**
   * @param RecognitionConfig
   */
  public function setConfig(RecognitionConfig $config)
  {
    $this->config = $config;
  }
  /**
   * @return RecognitionConfig
   */
  public function getConfig()
  {
    return $this->config;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RecognizeRequest::class, 'Google_Service_Speech_RecognizeRequest');
