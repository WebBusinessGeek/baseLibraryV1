<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 4/1/15
 * Time: 11:33 AM
 */

namespace Base;


trait AttributeValidationHooks {


    /**Allows child descendant to HOOK into a script to run custom validation logic.
     * Returns false at this level to enforce a valid implementation on the child.
     * @param array $credentialsOrAttributes
     * @return bool
     */
    public function runValidationLogicHook($credentialsOrAttributes = [])
    {
        return false;
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


}