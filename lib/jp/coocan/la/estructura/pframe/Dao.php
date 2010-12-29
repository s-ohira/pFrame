<?php
/**
 * Dao Base Class
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-12-27
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
class Dao {
    /** DB connection */
    private $connection;

    /** error exists */
    private $error = false;

    /** error message */
    private $errorMessage;

    /**
     * mutator DB Connection
     * @return mixed $connection
     * @param mixed $connection
     */
    public function setConnection ( $connection ) {
        $this->connection = $connection;
    }

    /**
     * accessor DB Connection
     * @return mixed $connection
     */
    public function getConnection () {
        return $this->connection;
    }

    /**
     * mutator DB Connection
     * @return mixed $connection
     * @param mixed $connection
     */
    public function setError ( $error ) {
        $this->error = $error;
    }

    /**
     * accessor DB Connection
     * @return mixed $connection
     */
    public function isError () {
        return $this->error;
    }

    /**
     * mutator DB Error Message
     * @param String $errorMessage
     */
    public function setErrorMessage ( $errorMessage ) {
        $this->errorMessage = $errorMessage;
    }

    /**
     * accessor DB Connection
     * @return String $connection
     */
    public function getErrorMessage () {
        return $this->errorMessage;
    }

    /**
     * execute SQL
     * @return array resultset
     * @param String query
     * @param array parameter
     */
    public function execute ( String $query, array $paramList = array() ) {
        $sth = $dbh->prepare( $query );
        $rs  = $sth->execute( $paramList );

        $resultList = array();
        while ($row = $rs->fetchRow() ){
            array_push( $resultList, $row );
        }
        return $resultList;
    }

    /**
     * exucute an update SQL
     * @param String query
     * @param array parameter
     */
    public function update ( String $query, array $paramList = array() ) {
        $sth = $dbh->prepare( $query );
        return $sth->execute( $paramList );
    }

    /**
     * exucute one or more update SQL
     * @param String query
     * @param array parameter
     */
    public function batchExecute ( String $query, array $paramList = array() ) {
        $cnt = 0;
        $sth = $dbh->prepare( $query );
        foreach ( $registerList AS $item ) {
            $cnt += $sth->execute( $paramList );
        }
        return $cnt;
    }
}