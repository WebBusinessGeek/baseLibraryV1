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

    protected $primaryOwnerClassName;

    protected $multiOwnerClassNames = [];

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


    public function stringAttributesAreValid($attributesToCheck)
    {
        $attributeNamesThatRequireStringFormatting = $this->getNamesOfSelfAttributesWhereOptionAndValueMatchThis('format', 'string');

        //determine if all values are valid strings

        //return true if all are valid

        //return false if not
    }



    public function getNamesOfSelfAttributesWhereOptionAndValueMatchThis($option, $value)
    {
        if(!$this->isValidOptionForSelfAttributes($option))
        {
            throw new \Exception($option .' Is invalid option for Model Attributes');
        }
        elseif(!$this->isValidValueForSelfAttributeOption($option, $value))
        {
            throw new \Exception($value .' Is invalid value for Model Attribute Option: '. $option);
        }

        $namesOfAttributesThatMatch = [];
        $modelAttributes = $this->getSelfAttributes();

        foreach($modelAttributes as $attribute)
        {
            if($attribute[$option] == $value)
            {
                array_push($attributesThatMatch, $attribute['name']);
            }
        }
        return $namesOfAttributesThatMatch;
    }

    public function isValidOptionForSelfAttributes($option)
    {

    }
    public function isValidValueForSelfAttributeOption($option, $value)
    {

    }


    public function getSelfPrimaryOwnerClassName()
    {
        return $this->primaryOwnerClassName;
    }




    public function getSelfAttributeWithSetting()
    {

    }


}