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

    use ModelAttributeManagement, StringValidator;

    protected $primaryOwnerClassName;

    protected $multiOwnerClassNames = [];

    /**Returns the class name of the BaseModel instance as a string.
     * @return string
     */
    public function getSelfClassName()
    {
        return '\\'. get_class($this);
    }

    public function getSelfPrimaryOwnerClassName()
    {
        return $this->primaryOwnerClassName;
    }





}