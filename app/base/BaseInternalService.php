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


        $attributesAcceptedResponse = $this->checkModelAcceptsAttributes($credentialsOrAttributes);


        if($validationLogicResponse == false)
        {
            /*not put in proper class yet*/
            return $this->sendMessage('Attributes failed validation.');
        }
        elseif($attributesAcceptedResponse == false)
        {
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


    /**Returns the model's modelAttributes property as a multiDimensional array.
     * @return mixed
     */
    public function getModelAttributes()
    {
        return $this->model->getSelfAttributes();
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


    /**Returns the message passed in if its a string.
     * @param $message
     * @return mixed
     */
    public function sendMessage($message)
    {
        if(is_string($message))
        {
            return $message;
        }
        throw new \Exception('Parameter must be of type - string');
    }


    /**Creates a new model instance and adds passed in attributes to it.
     * Returns the new model instance.
     * @param array $credentialsOrAttributes
     * @return mixed
     */
    public function addAttributesToNewModel($credentialsOrAttributes = [])
    {
        $newModel = $this->createNewModelInstance();
        $newModelWithAttributes = $this->updateAttributesOnExistingModel($newModel, $credentialsOrAttributes);
        return $newModelWithAttributes;
    }

    /**Creates a new instance of $model - property object's class.
     * @return mixed
     */
    public function createNewModelInstance()
    {
        $modelClassName = $this->getModelClassName();
        $model = new $modelClassName();
        return $model;
    }


    /**Returns class name of the $model - property object.
     * @return mixed
     */
    public function getModelClassName()
    {
        return $this->model->getSelfClassName();
    }


    /**Updates the passed in model with the new attributes.
     * Returns the updated model.
     * @param Model $model
     * @param array $newAttributes
     * @return mixed
     */
    public function updateAttributesOnExistingModel(Model $model, $newAttributes = [])
    {
        return $model->updateSelfAttributes($newAttributes);
    }


    /**Stores model in database.
     * @param Model $model
     * @param bool $returnInstance
     * @return bool|Model
     * @throws \Exception
     */
    public function storeEloquentModel(Model $model, $returnInstance = true)
    {
        if($model->save())
        {
           return ($returnInstance) ? $model : true;
        }
        throw new \Exception('Model not stored in database');
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