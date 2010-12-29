<?php
/**
 * validator
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-06-28
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
require_once '../lib/jp/coocan/la/estructura/pframe/ActionForm.php';
require_once '../lib/ext/spyc.php';

class ValidationForm extends ActionForm {
    /** エラーの有無 */
    private $error = false;
    // エラーメッセージ
    private $messages = array();

    public function hasError () {
        return $this->error;
    }
    public function setError ( $bool ) {
        $this->error = $bool;
    }

    public function addMessage ( $message, $args ) {
        array_push( $this->messages, array('message' => CommonUtils::getMessage($message, $args)) );
    }
    public function getMessages () {
        return $this->messages;
    }

    public function init () {
        parent::init();
        $this->validate();
    }

    private function validate () {
        // retrieve YAML Object
        $spyc = new Spyc();
        $conf = $spyc->YAMLLoad( VALIDATION_FILE );
        $properties = $this->getProperties();
        foreach ( $properties AS $property ) {
            if ( array_key_exists($property, $_POST) ) {
                $this->exec( $conf[$property], $_POST[$property] );
            }
            elseif ( array_key_exists($property, $_GET) ) {
                $this->exec( $conf[$property], $_GET[$property] );
            }
        }
    }

    private function getProperties () {
        $propList = array();

        // generate Reflection
        $reflect = new ReflectionClass( get_class($this) );
        $properties = $reflect->getProperties();

        foreach ( $properties AS $property ) {
            if ( $property->isPrivate() ) {
                $propName = preg_replace( '/^_/', '', $property->getName() );
                if ( $reflect->hasMethod(CONST_GETTER_SUFFIX . ucwords($propName)) ) {
                    array_push( $propList, $propName );
                }
            }
        }
        return $propList;
    }

    public function exec ( $rules, $param ) {
        foreach ( $rules AS $rule ) {
            if ( method_exists($this, $rule['name']) ) {
                $exec = new ReflectionMethod( get_class($this), $rule['name'] );
                $flg = $exec->invoke( $this, $param, $rule['args'] );
                if ( !array_key_exists('args', $rule) ) $rule['args'] = array();

                $spyc = new Spyc();
                $conf = $spyc->YAMLLoad( MESSAGES_FILE );
                if ( $flg ) { $this->addMessage( $conf[$rule['name']], $rule['args'] ); }
            }
        }
    }

    public function required ( $param, $args ) {
        if ( !$this->mb_trim($param) ) {
            $this->setError(TRUE);
            return TRUE;
        };
    }

    public function min ( $param, $args ) {
        if ( $args[1] > strlen(mb_ereg_match('^[\0[:space:]]+$', $param)) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function max ( $param, $args ) {
        if ( $args[1] < strlen(mb_ereg_match('^[\0[:space:]]+$', $param)) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function range ( $param, $args ) {
        if ( $args[1] > strlen(mb_ereg_match('^[\0[:space:]]+$', $param))
        || $args[2] < strlen(mb_ereg_match('^[\0[:space:]]+$', $param)) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function numeric ( $param, $args ) {
        if ( !preg_match('/^[0-9]+$/u', $param) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function alpha ( $param, $args ) {
        if ( !preg_match('/^[a-zA-Z]+$/u', $param) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function alphaNumeric ( $param, $args ) {
        if ( !preg_match('/^[0-9a-zA-Z]+$/u', $param) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function mail ( $param, $args ) {
        if ( $param && !preg_match('/^(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|"[^\\\\\x80-\xff\n\015"]*(?:\\\\[^\x80-\xff][^\\\\\x80-\xff\n\015"]*)*")(?:\.(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|"[^\\\\\x80-\xff\n\015"]*(?:\\\\[^\x80-\xff][^\\\\\x80-\xff\n\015"]*)*"))*@(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|\[(?:[^\\\\\x80-\xff\n\015\[\]]|\\\\[^\x80-\xff])*\])(?:\.(?:[^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\\\[\]\000-\037\x80-\xff])|\[(?:[^\\\\\x80-\xff\n\015\[\]]|\\\\[^\x80-\xff])*\]))*$/', $param) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function url ( $param, $args ) {
        if ( !preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $param) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function zip ( $param, $args ) {
        if ( !preg_match('/^[0-9]{7}$/u', $param) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function singleByte ( $param, $args ) {
        if ( preg_match('/(?:\xEF\xBD[\xA1-\xBF]|\xEF\xBE[\x80-\x9F])|[\x20-\x7E]/', $param) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function doubleByte ( $param, $args ) {
        if ( !preg_match('/(?:\xEF\xBD[\xA1-\xBF]|\xEF\xBE[\x80-\x9F])|[\x20-\x7E]/', $param) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    public function kana ( $param, $args ) {
        if ( !preg_match('/^(\xe3(\x82[\xa1-\xbf]|\x83[\x80-\xb6]|\x83\xbc))*$/', $param) ) {
            $this->setError(TRUE);
            return TRUE;
        }
    }

    /**
     * マルチバイト対応のtrim
     * 文字列先頭および最後の空白文字を取り除く
     * @param  string  空白文字を取り除く文字列
     * @return  string
     * @see http://d.hatena.ne.jp/hnw/20090223
     */
    function mb_trim ( $string ) {
        $whitespace = '[\s\0\x0b\p{Zs}\p{Zl}\p{Zp}]';
        $ret = preg_replace(sprintf('/(^%s+|%s+$)/u', $whitespace, $whitespace),
                        '', $string);
        return $ret;
    }
}
