<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 3/23/15
 * Time: 1:09 PM
 */

namespace Base;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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


    /**Creates and Stores a new Model instance in the database table.
     * Returns an instance of the Model on succes && storeEloquentModel method's ReturnInstance para set to True.
     * If ReturnInstance parameter set to false the Return value will be TRUE (bool) on success.
     * Throws descriptive error messages or  EXCEPTIONS on failure.
     * @param array $credentialsOrAttributes
     * @return bool|Model|mixed
     * @throws \Exception
     */
    public function store($credentialsOrAttributes = [])
    {
        $attributesAcceptedResponse = $this->checkModelAcceptsAttributes($credentialsOrAttributes);
        if($attributesAcceptedResponse === false)
        {
            return $this->sendMessage('Attributes are not accepted by model.');
        }

        $validationLogicResponse = $this->runValidationLogicHook($credentialsOrAttributes);
        if($validationLogicResponse === false)
        {
            return $this->sendMessage('Attributes failed validation.');
        }

        $manipulatedAttributes = $this->runPREandPOSTHooksAndReturnManipulatedAttributes($credentialsOrAttributes);
        if(!is_array($manipulatedAttributes))
        {
            throw new \Exception('Array not returned from the runAttributeManipulationLogic method.');
        }

        $newModel = $this->addAttributesToNewModel($manipulatedAttributes);
        if(!$this->isInstanceOfModel($newModel))
        {
            throw new \Exception('New model was not created.');
        }

        $storeModelResponse =  $this->storeEloquentModel($newModel);
        if((is_object($storeModelResponse) && $this->isInstanceOfModel($storeModelResponse) == false) ||
            (!is_object($storeModelResponse) && $storeModelResponse == false))
        {
            throw new \Exception('Model was not stored in database.');
        }
        return $storeModelResponse;
    }


    /**Returns MODEL instance from database if it exists.
     * If it does not exists method will return an ERROR message.
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function show($id)
    {
        $modelExistsCheck = $this->checkIfModelExists($id);
        if(!$modelExistsCheck)
        {
            return $this->sendMessage('No model by id: ' . $id);
        }
        return $this->getEloquentModelFromDatabaseById($id);
    }


    /**Returns an updated model based on attributes passed in if model exists and attributes are correct.
     * Returns an ERROR message if attributes are not accepted.
     * Returns an ERROR message if attributes fail validation.
     * Returns an ERROR message if model does not exists.
     * Otherwise returns the MODEL with updated attributes.
     * @param $id
     * @param array $attributes
     * @return mixed
     * @throws \Exception
     */
    public function update($id, $attributes = [])
    {
        $attributeAcceptedByModel = $this->checkModelAcceptsAttributes($attributes);
        if(!$attributeAcceptedByModel)
        {
            return $this->sendMessage('Attributes are not accepted by model.');
        }

        $attributesAreValid = $this->runValidationLogicHook($attributes);
        if($attributesAreValid === false)
        {
            return $this->sendMessage('Attributes failed validation.');
        }

        $showMethodCallResponse = $this->show($id);

        $checkIfShowResponseIsAModel = $this->isInstanceOfModel($showMethodCallResponse);
        if(!$checkIfShowResponseIsAModel)
        {
            return $errorMessage = $showMethodCallResponse;
        }

        $updatedModel = $this->updateAttributesOnExistingModel($existingModel = $showMethodCallResponse, $attributes);
        return $updatedModel;
    }


    public function destroy($id)
    {
        $showMethodCallResponse = $this->show($id);

        $checkIfShowResponseIsAModel = $this->isInstanceOfModel($showMethodCallResponse);
        if(!$checkIfShowResponseIsAModel)
        {
            return $errorMessage = $showMethodCallResponse;
        }
        return $this->deleteEloquentModel($showMethodCallResponse);
    }

    public function deleteEloquentModel(Model $model)
    {
        return $model->delete();
    }




    /**Attempts to retrieve a model from the database by its id.
     * Returns the MODEL if it exists.
     * Throws an Exception if it does not exist.
     * @param $modelId
     * @return mixed
     */
    public function attemptToRetrieveEloquentModelFromDatabase($modelId)
    {
        $modelClassName = $this->getModelClassName();
        $model = $modelClassName::findOrFail($modelId);
        return $model;
    }


    public function checkIfModelExists($modelId)
    {
        try
        {
            $this->attemptToRetrieveEloquentModelFromDatabase($modelId);
        }
        catch(ModelNotFoundException $e)
        {
            return false;
        }
        return true;
    }

    public function getEloquentModelFromDatabaseById($modelId)
    {
        return $this->attemptToRetrieveEloquentModelFromDatabase($modelId);
    }
    /**Check if passed in $modelToCheck is instance of the property Model.
     * Returns TRUE if $modelToCheck is an instance.
     * Returns FALSE if not.
     * @param $modelToCheck
     * @return bool
     */
    public function isInstanceOfModel($modelToCheck)
    {
        if(!is_object($modelToCheck))
        {
            return false;
        }
        $classOfModelToCheck = '\\'. get_class($modelToCheck);
        $classOfPropertyModel = $this->getModelClassName();
        return ($classOfModelToCheck == $classOfPropertyModel);
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
        $updatedModel = $model->updateSelfAttributes($newAttributes);
        return $this->storeEloquentModel($updatedModel);
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


    public function index()
    {

    }








}