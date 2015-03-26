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
        $validationLogicResponse = $this->runValidationLogicHook($credentialsOrAttributes);
        $attributesAcceptedResponse = $this->checkModelAcceptsAttributes($credentialsOrAttributes);

        if($validationLogicResponse === false)
        {
            return $this->sendMessage('Attributes failed validation.');
        }
        elseif($attributesAcceptedResponse === false)
        {
            return $this->sendMessage('Attributes are not accepted by model.');
        }

        $manipulatedAttributes = $this->runPREandPOSTHooksAndReturnManipulatedAttributes($credentialsOrAttributes);

        if(!is_array($manipulatedAttributes))
        {
            throw new \Exception('Array not returned from the runAttributeManipulationLogic method.');
        }

        $newModel = $this->addAttributesToNewModel($manipulatedAttributes);
        return $this->storeEloquentModel($newModel);
    }


    /**Allows child descendant to HOOK into a script to run custom validation logic.
     * Returns false at this level to enforce a valid implementation on the child.
     * @param array $credentialsOrAttributes
     * @return bool
     */
    public function runValidationLogicHook($credentialsOrAttributes = [])
    {
        return false;
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


    /**HOOK group to all child descendant to HOOK into the script.
     * All methods are HOOK method that allow the child to override functionality at various stages.
     * Please review each method's documentation separately for more information.
     * @param array $credentialsOrAttributes
     * @return array
     */
    public function runPREandPOSTHooksAndReturnManipulatedAttributes($credentialsOrAttributes = [])
    {
        $this->runPREAttributeManipulationLogic();
        $manipulatedAttributes = $this->runAttributeManipulationLogic($credentialsOrAttributes);
        $this->runPOSTAttributeManipulationLogic($credentialsOrAttributes, $manipulatedAttributes);
        return $manipulatedAttributes;
    }


    /**Allows child descendant to HOOK into script.
     * The purpose is to allow the child to run any logic BEFORE attributes are added to the model.
     * Will return NULL at this level.
     * @return string
     */
    public function runPREAttributeManipulationLogic()
    {
        return;
    }


    /**Allows child descendant to HOOK into script.
     * The purpose is to allow the child to perform any logic that will MANIPULATE the attributes.
     * This MANIPULATION will happen before the attributes are added to the model.
     * Will return the attributes as is at this level.
     * @param array $credentialsOrAttributes
     * @return array
     */
    public function runAttributeManipulationLogic($credentialsOrAttributes = [])
    {
        return $credentialsOrAttributes;
    }


    /**Allows child descendant to HOOK into script.
     * This allows the child to perform any logic that should run AFTER the attributes are manipulated.
     * The function will have both the MANIPULATED and ORIGINAL attribute groups available for use in any logic.
     * Method should NOT return any value at any level (parent or child).
     */
    public function runPOSTAttributeManipulationLogic($originalAttributes = [], $manipulatedAttributes = [])
    {
        return;
    }



    public function stringAttributesAreValid($attributesToCheck)
    {
        //get all attributes that have 'string' as value for 'format'
            //should be returned as: 'attributeName' => 'attributeValue'

        //determine if all values are valid strings

        //return true if all are valid

        //return false if not
    }

    public function getModelAttributesWithThisFormat($formatValue)
    {
        //determine if $formatValue is a valid format option

        //if not throw error

        //if so get the 'attributeName' => 'attributeValue' pairs that have the $formatValue

        //return them
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