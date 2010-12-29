<?php
/**
 * HTTP Session
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-06-28
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
class HttpRequest {
    private $_attribute;
    /**
     * constructer
     * @return array attributes
     */
    public function __construct () {
        $this->_attribute = array();
    }

    /**
     * 
     * @param String $key
     * @return mixed session
     */
    public function getAttribute ( $key = null ) {
        if ( null === $key ) {
            return $this->_attribute;
        }
        return $this->_attribute[$key];
    }

    /**
     * 
     * @param String $key
     * @param String $value
     */
    public function setAttribute ( $key, $value ) {
        $this->_attribute[$key] = $value;
    }
}
