<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 4/1/15
 * Time: 11:26 AM
 */

namespace Base;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;


abstract class ModelManager {

    public $model;

    /**Removes passed in MODEL from database.
     * Returns true on success.
     * @param Model $model
     * @return bool|null
     */
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


}