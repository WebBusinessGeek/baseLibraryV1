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
        $attributeNames = $model->getSelfAttributesByName();
        $this->assertEquals('attribute1', $attributeNames[0]);
    }


    /**
     * @group baseModelTests
     */
    public function test_checkSelfAcceptsAttributes_method_returns_false_if_attributes_not_accepted()
    {
        $badAttributes = [
            'badAttributeName' => 'someValue'
        ];

        $model = new MockBaseModel();
        $response = $model->checkSelfAcceptsAttributes($badAttributes);

        $this->assertFalse($response);
    }


    /**
     * @group baseModelTests
     */
    public function test_checkSelfAcceptsAttributes_method_returns_true_if_attributes_are_accepted()
    {
        $goodAttributes = [
            'attribute1' => 'someValue'
        ];

        $model = new MockBaseModel();
        $response = $model->checkSelfAcceptsAttributes($goodAttributes);

        $this->assertTrue($response);
    }


    /**
     *@group baseModelTests
     */
    public function test_getSelfClassName_method_returns_name_of_class()
    {
        $mockModel = new MockBaseModel();

        $className = $mockModel->getSelfClassName();

        $this->assertEquals('\Base\MockBaseModel', $className);
    }


    /**
     *@group baseModelTests
     */
    public function test_updateSelfAttributes_method_adds_attributes_to_model()
    {
        $attributeName = 'attribute';
        $valueName = 'value';

        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockModel = new MockBaseModel();
        $updatedMockModel = $mockModel->updateSelfAttributes($attributes);

        for($count = 1; $count <= 3; $count++)
        {
            $attributeNameAndCount = $attributeName.$count;
            $this->assertEquals($valueName.$count, $updatedMockModel->$attributeNameAndCount);
        }

    }




    public function test_isValidOptionForSelfAttributes_returns_true_if_valid_option_for_self_attributes_is_submitted()
    {

    }

    public function test_isValidOptionForSelfAttributes_returns_false_if_invalid_option_for_self_attributes_is_submitted()
    {

    }

    public function test_isValidValueForSelfAttributeOption_returns_true_if_valid_value_for_self_attributes_option_was_submitted()
    {

    }

    public function test_isValidValueForSelfAttributeOption_returns_false_if_invalid_value_for_self_attributes_option_was_submitted()
    {

    }

    public function test_stringAttributesAreValid_method_returns_false_if_invalid_strings_are_submitted_as_attributes()
    {

    }

    public function test_stringsAttributesAreValid_method_returns_true_if_valid_strings_are_submitted_as_attributes()
    {

    }
}
