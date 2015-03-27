<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 3/24/15
 * Time: 11:00 AM
 */

namespace Base;


class MockBaseInternalService extends BaseInternalService {



    public function __construct(BaseModel $mockBaseModel = null)
    {
        if($mockBaseModel)
        {
            $this->model = $mockBaseModel;
        }
        parent::__construct();
    }


    public function runValidationLogicHook($credentialsOrAttributes = [])
    {
        $stringValidationCheck = $this->model->checkIfStringAttributesAreValid($credentialsOrAttributes);
        return $stringValidationCheck;
    }



}