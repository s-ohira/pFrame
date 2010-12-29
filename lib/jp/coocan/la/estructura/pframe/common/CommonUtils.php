<?php
/**
 * Utility
 * @package jp.coocan.la.estructura.pframe.common
 * @access public
 * @author Seiji OHIRA
 * @since 2009-05-31
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
class CommonUtils {
    /**
     * replace template token
     * @returnn String error message
     * @param String error template
     * @param array $args
     */
    public static function getMessage ( $message, $args ) {
        foreach ( $args AS $key => $value ) {
            $message = preg_replace( '/\$\{' . $key . '\}/', $value, $message );
        }
        return $message;
    }
}
