<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 3/23/15
 * Time: 1:09 PM
 */

namespace Base;


use Illuminate\Database\Eloquent\Model;

abstract class BaseInternalService {

    public $model;


    public function __construct()
    {
        if($this->model == null)
        {
            throw new \Exception('Model is not set on Internal Service');
        }
        elseif($this->getModelAttributes() == null)
        {
            throw new \Exception('Attributes not set on Model');
        }
    }



    public function store($credentialsOrAttributes = [])
    {
        $validationLogicResponse = $this->runValidationLogic($credentialsOrAttributes);
        $attributesAcceptedResponse = $this->checkModelAcceptsAttributes($credentialsOrAttributes);

        if($validationLogicResponse == false)
        {
            return $this->sendMessage('Attributes failed validation.');
        }
        elseif($attributesAcceptedResponse == false)
        {
            return $this->sendMessage('Attributes are not accepted by model.');
        }

        $this->runPREAttributeManipulationLogic();
        $manipulatedAttributes = $this->runAttributeManipulationLogic($credentialsOrAttributes);
        $this->runPOSTAttributeManipulationLogic();
        $newModel = $this->addAttributesToNewModel($manipulatedAttributes);
        $storeResponse = $this->storeEloquentModel($newModel);
        return $storeResponse;
    }


    public function getModelAttributes()
    {
        return $this->model->getSelfAttributes();
    }

    public function runValidationLogic($credentialsOrAttributes = [])
    {
        return false;
    }
    public function checkModelAcceptsAttributes()
    {
        return false;
    }
    public function runPREAttributeManipulationLogic()
    {
        return '';
    }
    public function runAttributeManipulationLogic($credentialsOrAttributes = [])
    {
        return $credentialsOrAttributes;
    }
    public function runPOSTAttributeManipulationLogic()
    {
        return '';
    }
    public function addAttributesToNewModel($credentialsOrAttributes = [])
    {

    }
    public function storeEloquentModel(Model $model)
    {

    }
    public function sendMessage($message)
    {
        return $message;
    }


    public function show()
    {

    }

    public function index()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }






}