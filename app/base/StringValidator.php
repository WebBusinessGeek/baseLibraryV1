<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 4/6/15
 * Time: 2:25 PM
 */

namespace Base;


trait StringValidator {

    protected $invalidCharactersForStringValidation = "/[$%^&*()\-_+={}|\\[\]:;\"'<>?,.\/]/";
    protected $argumentNotAStringErrorMessage = 'Argument passed is not a string.';

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
            throw new \Exception($this->argumentNotAStringErrorMessage);
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

}