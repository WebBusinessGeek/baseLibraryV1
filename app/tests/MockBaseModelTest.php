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



    /**
     *@group baseModelTests
     */
    public function test_isValidOptionForSelfAttributes_returns_true_if_valid_option_for_self_attributes_is_submitted()
    {
        $validOption = 'name';

        $mockBaseModel = new MockBaseModel();
        $response = $mockBaseModel->isValidOptionForSelfAttributes($validOption);

        $this->assertTrue($response);
    }

    /**
     *@group baseModelTests
     */
    public function test_isValidOptionForSelfAttributes_returns_false_if_invalid_option_for_self_attributes_is_submitted()
    {
        $invalidOption = 'badOption';

        $mockBaseModel = new MockBaseModel();
        $response = $mockBaseModel->isValidOptionForSelfAttributes($invalidOption);

        $this->assertFalse($response);
    }

    /**
     *@group baseModelTests
     */
    public function test_isValidValueForSelfAttributeOption_returns_true_if_valid_value_for_self_attributes_option_was_submitted()
    {
        $validValue = 'email';
        $validOption = 'format';

        $mockBaseModel = new MockBaseModel();
        $response = $mockBaseModel->isValidValueForSelfAttributeOption($validOption, $validValue);

        $this->assertTrue($response);
    }

    /**
     *@group baseModelTests
     */
    public function test_isValidValueForSelfAttributeOption_returns_false_if_invalid_value_for_self_attributes_option_was_submitted()
    {
        $invalidValue = 'badValue';
        $validOption = 'format';

        $mockBaseModel = new MockBaseModel();
        $response = $mockBaseModel->isValidValueForSelfAttributeOption($validOption, $invalidValue);

        $this->assertfalse($response);
    }


    /**
     *@group baseModelTests
     */
    public function test_isValidValueForSelfAttributeOption_throws_an_exception_if_option_does_not_have_configurable_values()
    {
        $validValue = 'email';
        $invalidOption = 'badOption';

        $mockBaseModel = new MockBaseModel();

        $this->setExpectedException('Exception', 'Option: '.$invalidOption.' may not exist or not have configurable options.');
        $mockBaseModel->isValidValueForSelfAttributeOption($invalidOption, $validValue);
    }

    /**
     *@group baseModelTests
     */
    public function test_getNamesOfSelfAttributesWhereOptionAndValueMatchThis_returns_array_of_names_where_Option_and_value_match()
    {
        $validOption = 'format';

        $validValue = 'email';

        $mockBaseModel = new MockBaseModel();
        $response = $mockBaseModel->getNamesOfSelfAttributesWhereOptionAndValueMatchThis($validOption, $validValue);

        $this->assertTrue(is_array($response));
    }

    /**
     *@group baseModelTests
     */
    public function test_getNamesOfSelfAttributesWhereOptionAndValueMatchThis_throws_exception_if_option_is_invalid()
    {
        $invalidOption = 'badOption';

        $validValue = 'email';

        $this->setExpectedException('Exception', $invalidOption . ' - is an invalid option for Model Attributes');

        $mockBaseModel = new MockBaseModel();
        $mockBaseModel->getNamesOfSelfAttributesWhereOptionAndValueMatchThis($invalidOption, $validValue);
    }

    /**
     *@group baseModelTests
     */
    public function test_getNamesOfSelfAttributesWhereOptionAndValueMatchThis_throws_exception_if_value_is_invalid()
    {
        $validOption = 'format';

        $invalidValue = 'badValue';

        $this->setExpectedException('Exception', $invalidValue. ' - is an invalid value for Model Attribute Option: ' . $validOption);

        $mockBaseModel = new MockBaseModel();
        $mockBaseModel->getNamesOfSelfAttributesWhereOptionAndValueMatchThis($validOption, $invalidValue);
    }

    
    public function test_getNamesOfSelfAttributesWhereOptionAndValueMatchThis_throws_exception_if_no_results()
    {
        //valid option
        //valid value - where you know NO chance of attribute being place on model with this value

        //expect exception

        //call method
    }

    public function test_stringAttributesAreValid_method_returns_false_if_invalid_strings_are_submitted_as_attributes()
    {

    }

    public function test_stringsAttributesAreValid_method_returns_true_if_valid_strings_are_submitted_as_attributes()
    {

    }


}
