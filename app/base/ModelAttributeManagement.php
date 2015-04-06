<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 4/6/15
 * Time: 2:19 PM
 */

namespace Base;


trait ModelAttributeManagement {

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

    /**Returns the entire modelAttributes array as a multiDimensional array.
     * @return array
     */
    public function getSelfAttributes()
    {
        return $this->modelAttributes;
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
            $this->throwInvalidOptionForModelAttributesException($option);
        }
        elseif(!$this->isValidValueForSelfAttributeOption($option, $value) == true)
        {
            $this->throwInvalidValueForModelAttributeOptionException($option, $value);
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
            $this->throwNoAttributesHaveValueSetForModelAttributeOptionException($option, $value);
        }
        return $namesOfAttributesThatMatch;
    }


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
            $this->throwUnconfigurableOptionException($option);
        }
        return in_array($value, $this->validAttributeValues[$option]);
    }

    /**
     * @param $option
     * @throws \Exception
     */
    public function throwInvalidOptionForModelAttributesException($option)
    {
        throw new \Exception($option . ' - is an invalid option for Model Attributes');
    }

    /**
     * @param $option
     * @param $value
     * @throws \Exception
     */
    public function throwInvalidValueForModelAttributeOptionException($option, $value)
    {
        throw new \Exception($value . ' - is an invalid value for Model Attribute Option: ' . $option);
    }

    /**
     * @param $option
     * @param $value
     * @throws \Exception
     */
    public function throwNoAttributesHaveValueSetForModelAttributeOptionException($option, $value)
    {
        throw new \Exception('No attributes on the Model have ' . $value . ' as value for Model Attribute Option: ' . $option);
    }

    /**
     * @param $option
     * @throws \Exception
     */
    public function throwUnconfigurableOptionException($option)
    {
        throw new \Exception('Option: ' . $option . ' may not exist or not have configurable options.');
    }

}