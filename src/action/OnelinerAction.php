<?php
/**
 * 一行掲示板のActionクラス
 * @author Seiji OHIRA
 *
 */
require_once('../src/logic/OnelinerLogic.php');

Class OnelinerAction extends Action {
    /**
     * 一覧表示を行う
     * @param ActionForm $form
     * @param HttpRequest $request
     * @return String mapping
     */
    public function doList ( ActionForm $form = null, HttpRequest $request ) {
        $page = $form->getPage();
        $logic = $this->getLogic( 'OnelinerLogic' );
        $count = $logic->retrieveEntryCount();
        $list = $logic->retrieveEntryList( $page, $count );
        // 失敗時
        if ( NULL === $list ) {
            $list = array();
        }
        $request->setAttribute('list', $list);
        $request->setAttribute('page', $this->calcPage($count));
        // 成功時
        return 'success';
    }

    /**
     * 書き込みを行う
     * @param ActionForm $form
     * @param HttpRequest $request
     * @return String mapping
     */
    public function doRegist ( ActionForm $form, HttpRequest $request ) {
        $logic = $this->getLogic( 'OnelinerLogic' );

        $entry = array(
            'name' => strip_tags( $form->getName() )
           ,'body' => strip_tags( $form->getBody() )
           ,'mail' => strip_tags( $form->getMail() ? $form->getMail() : '' )
        );

        $bool = $logic->registEntry( $entry );
        // 失敗時
        if ($bool) {
            return 'error';
        }
        # 最新のリストを取得
        if ( !isset($page) ) {
            $page = 1;
        }
        $count = $logic->retrieveEntryCount();
        $list = $logic->retrieveEntryList( $page, $count );
        // 失敗時
        if ( null === $list ) {
            $list = array();
        }
        $request->setAttribute('list', $list);
        $request->setAttribute('page', $this->calcPage($count));
        // 成功時
        return 'success';
    }

    private function calcPage ($count) {
        $page = array();
        $num = $count / 10;
        for ( $i = 0; $i < $num; $i++ ) {
            array_push($page, array('num' => $i + 1));
        }
        return $page;
    }
}