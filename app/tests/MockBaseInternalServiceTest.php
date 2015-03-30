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
use Illuminate\Support\Facades\DB;

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


    /**
     *@group mockInternalServiceTests
     */
    public function test_getModelClassName_method_returns_className_of_the_model_property_object()
    {
        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $classNameToTestAgainst = '\\' . get_class($mockBaseModel);
        $className = $internalService->getModelClassName();

        $this->assertEquals($classNameToTestAgainst, $className);
    }

    /**
     *@group mockInternalServiceTests
     */
    public function test_createNewModelInstance_method_returns_a_instance_of_the_model_property_object()
    {
        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $modelInstance = $internalService->createNewModelInstance();

        $classNameToTestAgainst = '\\' . get_class($mockBaseModel);
        $classNameToTest = $modelInstance->getSelfClassName();

        $this->assertTrue(is_object($modelInstance) && $classNameToTestAgainst == $classNameToTest);
    }


    /**
     *@group mockInternalServiceTests
     */
    public function test_updateAttributesOnExistingModel_method_returns_model()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $modelInstance = $internalService->createNewModelInstance();

        $updatedModelInstance = $internalService->updateAttributesOnExistingModel($modelInstance, $attributes);

        $this->assertTrue(is_object($updatedModelInstance));
    }

    /**
     *@group mockInternalServiceTests
     */
    public function test_updateAttributesOnExistingModel_method_returns_model_with_correct_attributes()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $modelInstance = $internalService->createNewModelInstance();

        $updatedModelInstance = $internalService->updateAttributesOnExistingModel($modelInstance, $attributes);

        foreach($attributes as $attributeName => $attributeValue)
        {
            $this->assertEquals($attributeValue, $updatedModelInstance->$attributeName);
        }
    }

    /**
     *@group mockInternalServiceTests
     */
    public function test_addAttributesToNewModel_method_returns_a_model()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $newModelWithAttributes = $internalService->addAttributesToNewModel($attributes);

        $this->assertTrue(is_object($newModelWithAttributes));
    }

    /**
     *@group mockInternalServiceTests
     */
    public function test_addAttributesToNewModel_method_returns_a_model_with_correct_attributes()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $newModelWithAttributes = $internalService->addAttributesToNewModel($attributes);

        foreach($attributes as $attributeName => $attributeValue)
        {
            $this->assertEquals($attributeValue, $newModelWithAttributes->$attributeName);
        }
    }


    /**
     * @group mockInternalServiceTests
     */
    public function test_storeEloquentModel_method_returns_model_if_returnInstance_true()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $newModelWithAttributes = $internalService->addAttributesToNewModel($attributes);

        $storeResponse = $internalService->storeEloquentModel($newModelWithAttributes);

        $this->assertTrue(is_object($storeResponse));

        $storeResponse->delete();
    }


    /**
     * @group mockInternalServiceTests
     */
    public function test_storeEloquentModel_method_returns_boolean_if_returnInstance_set_to_false()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $newModelWithAttributes = $internalService->addAttributesToNewModel($attributes);

        $storeResponse = $internalService->storeEloquentModel($newModelWithAttributes, false);

        $this->assertTrue($storeResponse);

        DB::table('mockBaseModels')->truncate();
    }


    /**
     * @group mockInternalServiceTests
     */
    public function test_isInstanceOfModel_method_returns_false_if_model_passed_is_not_an_object()
    {
        $notAnObject = 'string';

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $response = $internalService->isInstanceOfModel($notAnObject);

       $this->assertFalse($response);
    }

    /**
     * @group mockInternalServiceTests
     */
    public function test_isInstanceOfModel_method_returns_false_if_model_passed_is_not_instance_of_propertyModel()
    {
        $notPropertyModel = new MockBaseModelWithoutAttributes();

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $response = $internalService->isInstanceOfModel($notPropertyModel);

        $this->assertFalse($response);
    }

    /**
     * @group mockInternalServiceTests
     */
    public function test_isInstanceOfModel_method_returns_true_if_model_passed_is_instance_of_propertyModel()
    {
        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $response = $internalService->isInstanceOfModel($mockBaseModel);

        $this->assertTrue($response);
    }




    public function test_store_method_sends_error_message_if_attributes_are_not_accepted_by_model()
    {
        $badAttributes = [
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
            'badAttribute' => 'someValue'
        ];

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);
        $response = $mockInternalService->store($badAttributes);

        $this->assertEquals('Attributes are not accepted by model.', $response);
    }

    public function test_store_method_sends_error_message_if_attributes_fail_child_implemented_validation_methods()
    {

    }

    public function test_store_method_returns_new_model_on_success()
    {

    }


}
