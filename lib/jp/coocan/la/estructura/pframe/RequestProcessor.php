<?php
/**
 * Dispatch HTTPRequest
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-06-28
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
class RequestProcessor {
    /**
     * determinec function requested
     * @param  HttpRequest $request
     */
    public function process ( $request ) {
        try {
            // create YAML Object
            $spyc = new Spyc();
            $conf = $spyc->YAMLLoad( CONST_CONF_FILE );

            // determine function
            foreach ( $conf AS $key => $value ) {
                if ( 0 === strpos($_SERVER['PATH_INFO'], $key) ) {
                    $pathInfo = $key;
                    break;
                }
            }

            if ( array_key_exists($pathInfo, $conf) ) {
                // create ActionForm
                if ( array_key_exists('actionForm', $conf[$pathInfo])
                        && array_key_exists('formClass', $conf[$pathInfo]) ) {
                    $form = $this->initForm( $conf[$pathInfo] );

                    // Validation
                    if ( $form instanceof ValidationForm && $form->hasError() ) {
                        $request->setAttribute( 'errormes', $form->getMessages() );
                        $forward = 'error';
                    }
                }
                // execute function found
                if ( !$form->hasError() && array_key_exists($pathInfo, $conf) ) {
                    $forward = $this->processActionCreate(
                    $conf[$pathInfo], $request, $form );
                }
                // 
                if ( array_key_exists('mapping', $conf[$pathInfo]) ) {
                    $mapping = new ActionMapping();
                    $mapping->forward(
                    $conf[$pathInfo]['mapping'], $forward, $request );
                    exit(1);
                }
            }
            // when no function found
            else {
                header( 'HTTP/1.0 404 File Not Found' );
                print( '要求された機能は見つかりませんでした。' );
            }
        }
        catch ( Exception $e ) {die($e);
            header( 'HTTP/1.0 500 Internal Server Error' );
            print( '致命的なエラーが発生しました。' );
            return null;
        }
    }

    /**
     * Actionクラスの実行
     * @param  array        $conf
     * @param  HtttpRequest $request
     * @param  ActionForm   $form
     * @return String       $forward
     */
    private function processActionCreate ( $conf, &$request, $form ) {
        if ( array_key_exists('class', $conf)
               && array_key_exists('action', $conf) ) {
            // create Action instance
            require_once $conf['class'];
            $reflect = new ReflectionClass( $conf['action'] );
            $action  = $reflect->newInstance();

            // Action instance must be inherited features from Action.php
            if ( $action instanceof Action ) {
                // create requestt object
                $request = new HttpRequest();

                // execute function requested
                if ( array_key_exists( 'method', $conf)
                        && $reflect->hasMethod($conf['method']) ) {
                    $exec = new ReflectionMethod( $conf['action'], $conf['method'] );
                    $forward = $exec->invoke( $action, $form, $request );
                    return $forward;
                }
                // execute defult function
                elseif ( $reflect->hasMethod(CONST_EXE_METHOD) ) {
                    $exec = new ReflectionMethod( $conf['action'], CONST_EXE_METHOD );
                    $forward = $exec->invoke( $action, $form, $request );
                    return $forward;
                }
                else {
                    header( 'HTTP/1.0 500 Internal Server Error' );
                    print( '致命的なエラーが発生しました。' );
                    return null;
                }
            }
        }
    }

    /**
     * create ActionForm
     * @param Array $conf
     * @return ActionForm form
     */
    private function initForm ( $conf ) {
        require_once $conf['formClass'];
        $formName = new ReflectionClass( $conf['actionForm'] );
        $form = $formName->newInstance();

        // Form instance must be inherited features from ActionForm.php
        if ( $form instanceof ActionForm ) {
            $form->init();
            return $form;
        }
    }
}
