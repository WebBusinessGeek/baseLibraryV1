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

    protected $invalidCharactersForStringValidation = "/[$%^&*()\-_+={}|\\[\]:;\"'<>?,.\/]/";

    protected $primaryOwnerClassName;

    protected $multiOwnerClassNames = [];

    protected $validAttributeOptions = [
        'name','format','nullable','unique','exists','identifier','key','enumValues'
    ];

    protected $validAttributeValues = [
        'format' => [
            'email', 'phoneNumber','url','password','string','exists','enum',
            'text','id','token','ipAddress','date'
        ],
        'nullable' => [
            true, false
        ],
        'unique' => [
            true, false
        ],
        'identifier' => [
            true, false
        ],
        'key' => [
            true, false
        ]
    ];
    protected $modelAttributes = [
        //		START AT ZERO (0)!!! => [
        //
        //			'name' => 'nameOfAttribute',
        //
        //			'format' => '(choose 1: email, phoneNumber, url, password,
        //							 string, exists, enum, text, id, token, ipAddress, date)',
        //
        //			'nullable' => false,
        //
        //			'unique' => true,
        //
        //			'exists' => null,
        //
        //          'identifier' => false,
        //
        //          'key' => false,
        //
        //
        //			'enumValues' => [
        //				'item1',
        //				'item2',
        //				'item3'
        //			]
        //		],

    ];


    /**Allows BaseModel instance to check if it accepts attributes passed.
     * Returns true if it does. False if not.
     * @param array $attributesToCheck
     * @return bool
     */
    public function checkSelfAcceptsAttributes($attributesToCheck = [])
    {
        $attributeNamesToMatch = $this->getSelfAttributesByName();
        $falseCounter = 0;

        foreach($attributesToCheck as $attributeNameToCheck => $attributeValue)
        {
            if(!in_array($attributeNameToCheck, $attributeNamesToMatch))
            {
                $falseCounter++;
            }
        }
        return ($falseCounter <= 0) ? : false;
    }


    /**Returns the model's attribute names only.
     * Returns as an array.
     * @return array
     */
    public function getSelfAttributesByName()
    {
        $modelAttributes = $this->getSelfAttributes();

        $attributeNameContainer = [];
        foreach($modelAttributes as $attribute)
        {
            array_push($attributeNameContainer, $attribute['name']);
        }
        return $attributeNameContainer;
    }


    /**Returns the entire modelAttributes array as a multiDimensional array.
     * @return array
     */
    public function getSelfAttributes()
    {
        return $this->modelAttributes;
    }


    /**Returns the class name of the BaseModel instance as a string.
     * @return string
     */
    public function getSelfClassName()
    {
        return '\\'. get_class($this);
    }


    /**Updates the model instance with the attributes passed.
     * Returns the model instance after the update.
     * @param array $newAttributes
     * @return $this
     */
    public function updateSelfAttributes($newAttributes = [])
    {
        foreach($newAttributes as $attributeName => $attributeValue)
        {
            $this->$attributeName = $attributeValue;
        }
        return $this;
    }


    /**Checks if all string attribute values are valid.
     * Returns TRUE if all string attributes are valid.
     * Returns FALSE if any string attribute values are invalid.
     * Returns TRUE if no string attribute values are passed at all.
     * @param $attributesToCheck
     * @return bool
     * @throws \Exception
     */
    public function checkIfStringAttributesAreValid($attributesToCheck)
    {
        $attributeNamesThatRequireStringFormatting = $this->getNamesOfSelfAttributesWhereOptionAndValueMatchThis('format', 'string');

        $valuesToBeCheckedForStringValidation = $this->getValuesFromAssociativeArrayWhereKeysMatch($attributesToCheck,$attributeNamesThatRequireStringFormatting);

        if(!count($valuesToBeCheckedForStringValidation > 0))
        {
            return $noStringsToCheck = true;
        }

        $invalidCounter = 0;
        foreach($valuesToBeCheckedForStringValidation as $attributeValue)
        {
            if(!$this->stringIsValid($attributeValue))
            {
                $invalidCounter++;
            }
        }
       return ($invalidCounter === 0)? : false;
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


    /** Returns the names of attributes on Model that have the passed $option set to the passed $value.
     * Returns names as array.
     * @param $option
     * @param $value
     * @return array
     * @throws \Exception
     */
    public function getNamesOfSelfAttributesWhereOptionAndValueMatchThis($option, $value)
    {
        if(!$this->isValidOptionForSelfAttributes($option))
        {
            throw new \Exception($option .' - is an invalid option for Model Attributes');
        }
        elseif(!$this->isValidValueForSelfAttributeOption($option, $value) == true)
        {
            throw new \Exception($value .' - is an invalid value for Model Attribute Option: '. $option);
        }

        $namesOfAttributesThatMatch = [];
        $modelAttributes = $this->getSelfAttributes();

        foreach($modelAttributes as $attribute)
        {
            if($attribute[$option] == $value)
            {
                array_push($namesOfAttributesThatMatch, $attribute['name']);
            }
        }
        if(!count($namesOfAttributesThatMatch) > 0)
        {
            throw new \Exception('No attributes on the Model have '. $value . ' as value for Model Attribute Option: ' .$option);
        }
        return $namesOfAttributesThatMatch;
    }

    /**Determines if passed in argument is a valid attribute option for model.
     * Returns TRUE on pass, FALSE on fail.
     * @param $option
     * @return bool
     */
    public function isValidOptionForSelfAttributes($option)
    {
        return in_array($option, $this->validAttributeOptions);
    }

    /**Determines if passed $value is a valid option for passed $option.
     * Returns TRUE on pass, FALSE on fail, and THROWS EXCEPTION if $option is invalid.
     * @param $option
     * @param $value
     * @return bool
     * @throws \Exception
     */
    public function isValidValueForSelfAttributeOption($option, $value)
    {
        if(!isset($this->validAttributeValues[$option]))
        {
            throw new \Exception('Option: '. $option .' may not exist or not have configurable options.');
        }
        return in_array($value, $this->validAttributeValues[$option]);
    }


    public function getSelfPrimaryOwnerClassName()
    {
        return $this->primaryOwnerClassName;
    }




    public function getSelfAttributeWithSetting()
    {

    }


}