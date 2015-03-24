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
        /*Hook - force child implementation by returning false*/
        $validationLogicResponse = $this->runValidationLogic($credentialsOrAttributes);


        /*Implementation needed on parent*/
        /*Not Done*/
        $attributesAcceptedResponse = $this->checkModelAcceptsAttributes($credentialsOrAttributes);


        if($validationLogicResponse == false)
        {
            /*should be available for both internal and external services*/
            /*not put in proper class yet*/
            return $this->sendMessage('Attributes failed validation.');
        }
        elseif($attributesAcceptedResponse == false)
        {
            /*should be available for both internal and external services*/
            /*not put in proper class yet*/
            return $this->sendMessage('Attributes are not accepted by model.');
        }

        /*Hook - child implementation is not necessary*/
        $this->runPREAttributeManipulationLogic();

        /*Hook - child implementation is not necessary, should only return attributes at this level*/
        $manipulatedAttributes = $this->runAttributeManipulationLogic($credentialsOrAttributes);

        /*Hook - child implementation is not necessary*/
        $this->runPOSTAttributeManipulationLogic();

        /*Implementation needed on parent*/
        $newModel = $this->addAttributesToNewModel($manipulatedAttributes);

        /*Implementation needed on parent*/
        $storeResponse = $this->storeEloquentModel($newModel);

        /*should store response be checked?*/
        return $storeResponse;
    }


    /**Checks if the model accepts the attributes or credentials being passed.
     * Returns True if it does. False if not.
     * @param array $credentialsOrAttributes
     * @return mixed
     */
    public function checkModelAcceptsAttributes($credentialsOrAttributes = [])
    {
        return $this->model->checkSelfAcceptsAttributes($credentialsOrAttributes);
    }




    public function getModelAttributes()
    {
        return $this->model->getSelfAttributes();
    }



    public function runValidationLogic($credentialsOrAttributes = [])
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