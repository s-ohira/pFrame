<?php
/**
 * Action Base Class
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-06-28
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
class Action {
    /**
     * default function
     */
    public function execute () {
        return null;
    }

    /**
     * return Logic instance
     * @param  String $logicName
     * @return Deligate $delegate
     */
    public function getLogic ( $logicName ) {
        $delegate = new Delegate( $logicName );
        return $delegate;
    }
}
