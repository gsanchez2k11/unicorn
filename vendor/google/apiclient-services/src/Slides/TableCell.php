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

namespace Google\Service\Slides;

class TableCell extends \Google\Model
{
  /**
   * @var int
   */
  public $columnSpan;
  /**
   * @var TableCellLocation
   */
  public $location;
  protected $locationType = TableCellLocation::class;
  protected $locationDataType = '';
  /**
   * @var int
   */
  public $rowSpan;
  /**
   * @var TableCellProperties
   */
  public $tableCellProperties;
  protected $tableCellPropertiesType = TableCellProperties::class;
  protected $tableCellPropertiesDataType = '';
  /**
   * @var TextContent
   */
  public $text;
  protected $textType = TextContent::class;
  protected $textDataType = '';

  /**
   * @param int
   */
  public function setColumnSpan($columnSpan)
  {
    $this->columnSpan = $columnSpan;
  }
  /**
   * @return int
   */
  public function getColumnSpan()
  {
    return $this->columnSpan;
  }
  /**
   * @param TableCellLocation
   */
  public function setLocation(TableCellLocation $location)
  {
    $this->location = $location;
  }
  /**
   * @return TableCellLocation
   */
  public function getLocation()
  {
    return $this->location;
  }
  /**
   * @param int
   */
  public function setRowSpan($rowSpan)
  {
    $this->rowSpan = $rowSpan;
  }
  /**
   * @return int
   */
  public function getRowSpan()
  {
    return $this->rowSpan;
  }
  /**
   * @param TableCellProperties
   */
  public function setTableCellProperties(TableCellProperties $tableCellProperties)
  {
    $this->tableCellProperties = $tableCellProperties;
  }
  /**
   * @return TableCellProperties
   */
  public function getTableCellProperties()
  {
    return $this->tableCellProperties;
  }
  /**
   * @param TextContent
   */
  public function setText(TextContent $text)
  {
    $this->text = $text;
  }
  /**
   * @return TextContent
   */
  public function getText()
  {
    return $this->text;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TableCell::class, 'Google_Service_Slides_TableCell');
