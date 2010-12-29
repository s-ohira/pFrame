<?php
/**
 * Logic Base Class
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-12-27
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
class Logic {
    /** DB connection */
    private $_connection;

    /**
     * proxy method
     * @param String $name
     * @param array $args
     * @return mixed result
     */
    public function __call ( $name, $args) {
        call_user_func_array( array($this, $name . $name), $args ); 
    } 

    /**
     * create Dao instance
     * @param String $daoName
     * @return Dao Dao instance
     */
    public function daoFactory ( $daoName ) {
        $reflect = new ReflectionClass( $daoName );
        $dao     = $reflect->newInstance();

        if ( $dao instanceof Dao ) {
            $dao->setConnection( $this->_connection );
            return $dao;
        }
        return NULL;
    }

    /**
     * mutator DB connection
     * @param mixed $connection
     */
    public function setConnection ( $connection ) {
        $this->_connection = $connection;
    }
}
