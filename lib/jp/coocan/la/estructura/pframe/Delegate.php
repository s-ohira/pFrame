<?php
/**
 * Dao Base Class
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-12-26
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
class Delegate {
    /** Logic instance */
    private $_logic;

    /**
     * @param String $logicClass
     */
    public function __construct( $logicName ) {
        $reflect  = new ReflectionClass( $logicName );
        $logic    = $reflect->newInstance();

        if ( $logic instanceof Logic ) {
            $this->_logic = $logic;
        }
        else {
            $this->_logic = NULL;
        }
    }

    /**
     * @return $_logic
     */
    public function getLogic () {
        return $_logic;
    }

    /**
     * call Logic method
     * @param string $methodName
     * @param mixed  $args
     */
    public function __call( $methodName, $args ) {
        $spyc = new Spyc();
        $conf = $spyc->YAMLLoad( CONST_MDB2_CONF );
        $exec = new ReflectionMethod( get_class($this->_logic), $methodName );

        $dsn = array(
                'phptype'  => $conf['phptype' ]
               ,'database' => $conf['database']
               ,'mode'     => '0644'
               ,'username' => ''
               ,'password' => ''
            );
        $connection = MDB2::factory( $dsn, array('use_transactions' => true) );
        $connection->beginTransaction();
        $this->_logic->setConnection( $connection );
//        array_unshift( $args );

        $ret = call_user_func_array(
                 array($this->_logic, $methodName), $args ); 
        if ( PEAR::isError($result) ) {
            $connection->rollback();
        }
        else {
            $connection->commit();
            
        }
        $connection->disconnect();

        return $ret;
    }
}
