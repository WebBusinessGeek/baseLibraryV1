<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 3/24/15
 * Time: 11:04 AM
 */

namespace tests;


use Base\MockBaseInternalService as MockBaseInternalService;
use Base\MockBaseModel;
use Base\MockBaseModelWithoutAttributes;

class MockBaseInternalServiceTest extends \TestCase {


    /**
     * @group mockInternalServiceTests
     * @group internalServiceConstructorTests
     * @group internalServiceFrameworkTests
     */
    public function test_internalServices_must_have_a_model()
    {
        $this->setExpectedException('Exception', 'Model is not set on Internal Service');
        $mockBaseInternalService = new MockBaseInternalService();
    }


    /**
     * @group mockInternalServiceTests
     * @group internalServiceConstructorTests
     * @group internalServiceFrameworkTests
     */
    public function test_models_on_internalServices_must_have_attributes()
    {
        $this->setExpectedException('Exception', 'Attributes not set on Model');
        $mockBaseModelWithoutAttributes = new MockBaseModelWithoutAttributes();
        return new MockBaseInternalService($mockBaseModelWithoutAttributes);
    }


    /**
     * @group mockInternalServiceTests
     * @group internalServiceConstructorTests
     * @group internalServiceFrameworkTests
     */
    public function test_internalService_containing_a_model_with_attributes_does_not_throw_exception()
    {
        $exceptionCatcher = null;
        try{
            $mockBaseModelWithAttributes = new MockBaseModel();
            $mockBaseInternalService = new MockBaseInternalService($mockBaseModelWithAttributes);
        }
        catch(\Exception $e)
        {
            $exceptionCatcher = $e;
        }
        $this->assertNull($exceptionCatcher);
    }


    /**
     *@group mockInternalServiceTests
     */
    public function test_checkModelAcceptsAttributes_method_returns_false_if_model_does_not_accept_attribute_names()
    {
        $badAttributes = [
            'badAttribute' => 'someValue',
            'badAttribute2' => 'someValue'
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $response = $internalService->checkModelAcceptsAttributes($badAttributes);
        $this->assertFalse($response);
    }

    /**
     *@group mockInternalServiceTests
     */
    public function test_checkModelAcceptsAttributes_method_returns_true_if_model_does_accept_attribute_names()
    {
        $goodAttributes = [
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $response = $internalService->checkModelAcceptsAttributes($goodAttributes);
        $this->assertTrue($response);
    }



    public function test_getModelClassName_method_returns_className_of_the_model_property_object()
    {
        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $classNameToTestAgainst = '\\' . get_class($mockBaseModel);
        $className = $internalService->getModelClassName();

        $this->assertEquals($classNameToTestAgainst, $className);
    }

    public function test_createNewModelInstance_method_returns_a_instance_of_the_model_property_object()
    {
        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $modelInstance = $internalService->createNewModelInstance();

        $classNameToTestAgainst = '\\' . get_class($mockBaseModel);
        $classNameToTest = $modelInstance->getSelfClassName();

        $this->assertTrue(is_object($modelInstance) && $classNameToTestAgainst == $classNameToTest);
    }


    public function test_updateAttributesOnExistingModel_method_returns_model()
    {

    }

    public function test_updateAttributesOnExistingModel_method_returns_model_with_correct_attributes()
    {

    }

    public function test_addAttributesToNewModel_method_returns_a_model()
    {

    }

    public function test_addAttributesToNewModel_method_returns_a_model_with_correct_attributes()
    {

    }

}
