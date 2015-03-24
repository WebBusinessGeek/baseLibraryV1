<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 3/24/15
 * Time: 1:32 PM
 */

namespace tests;


use Base\MockBaseModel;

class MockBaseModelTest extends \TestCase {


    /**
     * @group baseModelTests
     */
    public function test_getAttributesByName_method_gets_all_attribute_names_available_on_model()
    {
        $model = new MockBaseModel();
        $attributeNames = $model->getAttributesByName();
        $this->assertEquals('attribute1', $attributeNames[0]);
    }

   /* public function test_checkSelfAcceptsAttributes_method_returns_false_if_attributes_not_accepted()
    {
        $badAttributes = [
            'badAttributeName' => 'someValue'
        ];

        $model = new MockBaseModel();
        $response = $model->checkSelfAcceptsAttributes($badAttributes);

        $this->assertFalse($response);
    }

    public function test_checkSelfAcceptsAttributes_method_returns_true_if_attributes_are_accepted()
    {
        $goodAttributes = [
            'attribute1' => 'someValue'
        ];

        $model = new MockBaseModel();
        $response = $model->checkSelfAcceptsAttributes($goodAttributes);

        $this->assertTrue($response);
    }*/
}
