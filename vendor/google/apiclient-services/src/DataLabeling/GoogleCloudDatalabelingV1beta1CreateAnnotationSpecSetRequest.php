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

namespace Google\Service\DataLabeling;

class GoogleCloudDatalabelingV1beta1CreateAnnotationSpecSetRequest extends \Google\Model
{
  /**
   * @var GoogleCloudDatalabelingV1beta1AnnotationSpecSet
   */
  public $annotationSpecSet;
  protected $annotationSpecSetType = GoogleCloudDatalabelingV1beta1AnnotationSpecSet::class;
  protected $annotationSpecSetDataType = '';

  /**
   * @param GoogleCloudDatalabelingV1beta1AnnotationSpecSet
   */
  public function setAnnotationSpecSet(GoogleCloudDatalabelingV1beta1AnnotationSpecSet $annotationSpecSet)
  {
    $this->annotationSpecSet = $annotationSpecSet;
  }
  /**
   * @return GoogleCloudDatalabelingV1beta1AnnotationSpecSet
   */
  public function getAnnotationSpecSet()
  {
    return $this->annotationSpecSet;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatalabelingV1beta1CreateAnnotationSpecSetRequest::class, 'Google_Service_DataLabeling_GoogleCloudDatalabelingV1beta1CreateAnnotationSpecSetRequest');
