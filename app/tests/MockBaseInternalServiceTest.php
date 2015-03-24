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


    public function test_checkModelAcceptsAttributes_method_returns_false_if_model_does_not_accept_attribute_names()
    {

    }


}
