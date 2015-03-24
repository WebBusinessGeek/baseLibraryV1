<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 3/24/15
 * Time: 11:04 AM
 */

namespace tests;


use Base\MockBaseInternalService;
use Base\MockBaseModelWithoutAttributes;

class MockBaseInternalServiceTest extends \TestCase {


    /**
     * @group mockInternalServiceTests
     * @group internalServiceConstructorTests
     * @group internalServiceFrameworkTests
     */
    public function test_internalServices_must_have_a_model()
    {
        return new MockBaseInternalService();
    }


    /**
     * @group mockInternalServiceTests
     * @group internalServiceConstructorTests
     * @group internalServiceFrameworkTests
     */
    public function test_models_on_internalServices_must_have_attributes()
    {
        $mockBaseModelWithoutAttributes = new MockBaseModelWithoutAttributes();

        return new MockBaseInternalService($mockBaseModelWithoutAttributes);
    }


    
}
