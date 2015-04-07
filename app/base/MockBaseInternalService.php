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

   /* public function runPREAttributeManipulationLogic($credentialsOrAttributes = [])
    {
        //add the relationship
        if($this->detectModelRelationships($credentialsOrAttributes))
        {
            $this->addModelRelationships($credentialsOrAttributes);
        }
    }

    public function runAttributeManipulationLogic($credentialsOrAttributes = [])
    {
        //remove the relationship from the attributes
        if($this->detectModelRelationships($credentialsOrAttributes))
        {
            $attributesWithoutRelationships = $this->removeRelationshipRelatedAttributes($credentialsOrAttributes);
        }
        return $attributesWithoutRelationships;
    }

    public function runPOSTAttributeManipulationLogic($credentialsOrAttributes = [], $manipulatedAttributes = [])
    {
        //nothing
    }*/





}