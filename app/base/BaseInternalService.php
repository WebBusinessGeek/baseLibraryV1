<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 3/23/15
 * Time: 1:09 PM
 */

namespace Base;



abstract class BaseInternalService extends ModelManager {

    use AttributeValidationHooks;

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
     * Returns an instance of the Model on success && storeEloquentModel method's ReturnInstance para set to True.
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


    /**Removes specified MODEL from database if it exists.
     * Returns ERROR message if MODEL does not exists.
     * Returns TRUE on success.
     * @param $id
     * @return bool|mixed|null
     */
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











}