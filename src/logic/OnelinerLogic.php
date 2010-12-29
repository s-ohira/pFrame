<?php
/**
 * 一行掲示板のLogicクラス
 * @author Seiji OHIRA
 *
 */
require_once('../src/dao/OnelinerDao.php');

Class OnelinerLogic extends Logic {
    function retrieveEntryCount () {
        $dao   = $this->daoFactory('OnelinerDao');
        return $dao->selectEntryCount();
        
    }

    function retrieveEntryList ( $page, $count ) {
        if ( !$page ) {
            $st = $count - 10;
            $ed = $count;
        } else {
            $st = $count - ( 10 * ($page - 1) ) > 0 ? $count - ( 10 * $page ) : 1;
            $ed = $st + 10;
        }
        $dao   = $this->daoFactory('OnelinerDao');
        return $dao->selectEntryList( $st, $ed );
    }

    function registEntry ( $registMap ) {
        $dao = $this->daoFactory('OnelinerDao');;
        $dao->insertEntry( $registMap );
        if ( $dao->isError() ) {
            return $dao->getErrorMessage();
        }
        return FALSE;
    }
}