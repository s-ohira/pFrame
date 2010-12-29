<?php
/**
 * HTTP Session Object
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-12-27
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
class HttpSession {
    /**
     * accessor HTTP Session
     * @param String $key
     */
    public function getAttribute ( $key ) {
        return unserialize( $_SESSION[$key] );
    }

    /**
     * mutator HTTP Session
     * String $key
     * mixed $obj
     */
    public function setAttribute ( $key, $obj ) {
        $_SESSION[$key] = serialize( $obj );
    }
}
