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

class VideoContentSearchInstructionTrainingDataAnchorFeatures extends \Google\Collection
{
  protected $collection_key = 'instructionAnchorsMatchInfo';
  /**
   * @var VideoContentSearchSimilarityMatchInfo[]
   */
  public $bestAsrAndDescriptionAnchorsMatchInfo;
  protected $bestAsrAndDescriptionAnchorsMatchInfoType = VideoContentSearchSimilarityMatchInfo::class;
  protected $bestAsrAndDescriptionAnchorsMatchInfoDataType = 'array';
  /**
   * @var VideoContentSearchSimilarityMatchInfo[]
   */
  public $bestDescriptionAndInstructionAnchorsMatchInfo;
  protected $bestDescriptionAndInstructionAnchorsMatchInfoType = VideoContentSearchSimilarityMatchInfo::class;
  protected $bestDescriptionAndInstructionAnchorsMatchInfoDataType = 'array';
  /**
   * @var VideoContentSearchSimilarityMatchInfo[]
   */
  public $instructionAnchorsMatchInfo;
  protected $instructionAnchorsMatchInfoType = VideoContentSearchSimilarityMatchInfo::class;
  protected $instructionAnchorsMatchInfoDataType = 'array';

  /**
   * @param VideoContentSearchSimilarityMatchInfo[]
   */
  public function setBestAsrAndDescriptionAnchorsMatchInfo($bestAsrAndDescriptionAnchorsMatchInfo)
  {
    $this->bestAsrAndDescriptionAnchorsMatchInfo = $bestAsrAndDescriptionAnchorsMatchInfo;
  }
  /**
   * @return VideoContentSearchSimilarityMatchInfo[]
   */
  public function getBestAsrAndDescriptionAnchorsMatchInfo()
  {
    return $this->bestAsrAndDescriptionAnchorsMatchInfo;
  }
  /**
   * @param VideoContentSearchSimilarityMatchInfo[]
   */
  public function setBestDescriptionAndInstructionAnchorsMatchInfo($bestDescriptionAndInstructionAnchorsMatchInfo)
  {
    $this->bestDescriptionAndInstructionAnchorsMatchInfo = $bestDescriptionAndInstructionAnchorsMatchInfo;
  }
  /**
   * @return VideoContentSearchSimilarityMatchInfo[]
   */
  public function getBestDescriptionAndInstructionAnchorsMatchInfo()
  {
    return $this->bestDescriptionAndInstructionAnchorsMatchInfo;
  }
  /**
   * @param VideoContentSearchSimilarityMatchInfo[]
   */
  public function setInstructionAnchorsMatchInfo($instructionAnchorsMatchInfo)
  {
    $this->instructionAnchorsMatchInfo = $instructionAnchorsMatchInfo;
  }
  /**
   * @return VideoContentSearchSimilarityMatchInfo[]
   */
  public function getInstructionAnchorsMatchInfo()
  {
    return $this->instructionAnchorsMatchInfo;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(VideoContentSearchInstructionTrainingDataAnchorFeatures::class, 'Google_Service_Contentwarehouse_VideoContentSearchInstructionTrainingDataAnchorFeatures');
