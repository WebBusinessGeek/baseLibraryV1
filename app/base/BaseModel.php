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


    public function updateSelfAttributes($newAttributes = [])
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