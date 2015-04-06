<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 3/23/15
 * Time: 1:01 PM
 */

namespace Base;


use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model{

    use ModelAttributeManagement;
    
    protected $invalidCharactersForStringValidation = "/[$%^&*()\-_+={}|\\[\]:;\"'<>?,.\/]/";

    protected $primaryOwnerClassName;

    protected $multiOwnerClassNames = [];

    /**Returns the class name of the BaseModel instance as a string.
     * @return string
     */
    public function getSelfClassName()
    {
        return '\\'. get_class($this);
    }

    /**Returns ALL values from an associative array where its key matches one of the $keysToMatch.
     * Values returned as an array.
     * If no values are returned the array will be empty.
     * @param array $associativeArrayToBeChecked
     * @param array $keysToMatch
     * @return array
     */
    public function getValuesFromAssociativeArrayWhereKeysMatch($associativeArrayToBeChecked = [], $keysToMatch = [])
    {
        $valuesWhereKeysWereMatched = [];

        foreach($associativeArrayToBeChecked as $keyToCheck => $valueToPushIfKeyMatches)
        {
            if(in_array($keyToCheck, $keysToMatch))
            {
                array_push($valuesWhereKeysWereMatched, $valueToPushIfKeyMatches);
            }
        }
        return $valuesWhereKeysWereMatched;
    }

    /**Checks if string is valid.
     * Returns TRUE if it is a valid string.
     * Returns FALSE if its not.
     * THROWS an EXCEPTION if the value passed is not a string.
     * @param $stringToCheck
     * @return bool
     * @throws \Exception
     */
    public function stringIsValid($stringToCheck)
    {
        if(!is_string($stringToCheck))
        {
            throw new \Exception('Argument passed is not a string.');
        }
        return(!$this->isInvalidCharactersPresentInString($stringToCheck))? :false;
    }

    /**Checks if invalid Characters are present in string.
     * Returns TRUE if invalid characters are detected.
     * Returns FALSE if NO invalid characters are detected.
     * Returns TRUE if MODEL has NULL set on the $invalidCharactersForStringValidation property.
     * @param $stringToCheck
     * @return bool
     */
    public function isInvalidCharactersPresentInString($stringToCheck)
    {
        $invalidCharacters = $this->getInvalidCharactersForStringValidation();
        if(is_null($invalidCharacters))
        {
            return true;
        }
        return (preg_match_all($invalidCharacters, $stringToCheck) > 0) ? :false;
    }

    /**Returns invalidCharactersForStringValidation property value.
     * @return string
     */
    public function getInvalidCharactersForStringValidation()
    {
        return $this->invalidCharactersForStringValidation;
    }

    public function getSelfPrimaryOwnerClassName()
    {
        return $this->primaryOwnerClassName;
    }




}