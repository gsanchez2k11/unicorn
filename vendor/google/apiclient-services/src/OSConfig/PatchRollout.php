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

namespace Google\Service\OSConfig;

class PatchRollout extends \Google\Model
{
  /**
   * @var FixedOrPercent
   */
  public $disruptionBudget;
  protected $disruptionBudgetType = FixedOrPercent::class;
  protected $disruptionBudgetDataType = '';
  /**
   * @var string
   */
  public $mode;

  /**
   * @param FixedOrPercent
   */
  public function setDisruptionBudget(FixedOrPercent $disruptionBudget)
  {
    $this->disruptionBudget = $disruptionBudget;
  }
  /**
   * @return FixedOrPercent
   */
  public function getDisruptionBudget()
  {
    return $this->disruptionBudget;
  }
  /**
   * @param string
   */
  public function setMode($mode)
  {
    $this->mode = $mode;
  }
  /**
   * @return string
   */
  public function getMode()
  {
    return $this->mode;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PatchRollout::class, 'Google_Service_OSConfig_PatchRollout');
