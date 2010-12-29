<?php
/**
 * ActionForm Base Class
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-12-27
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
class ActionForm {
    /**
     * create form object
     * @param  $conf
     * @param  $name
     * @return $form
     */
    public function init () {
        // retrieve properties
        $reflect = new ReflectionClass( get_class($this) );
        $properties = $reflect->getProperties();

        // mutate
        foreach ( $properties AS $property ) {
            if ( !$property->isPrivate() ) {
                continue;
            }
            $propName = preg_replace( '/^_/', '' ,$property->getName() );
            if ( !$reflect->hasMethod(CONST_SETTER_SUFFIX . ucwords($propName)) ) {
                continue;
            }
            $setter = new ReflectionMethod(
                    $reflect->getName(), CONST_SETTER_SUFFIX . ucwords($propName) );
            if ( 1 == count($setter->getParameters()) && array_key_exists($propName, $_POST) ) {
                $setter->invoke( $this, $_POST[$propName] );
            } elseif ( 1 == count($setter->getParameters()) && array_key_exists($propName, $_GET) ) {
                $setter->invoke( $this, $_GET[$propName] );
            }
        }
    }
}
