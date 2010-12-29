<?php
/**
 * 一行掲示板ActionDaoクラス
 * @author Seiji OHIRA
 * @since 2010-10-17
 */
Class OnelinerDao extends Dao {
    /**
     * 
     * @return unknown_type
     */
    public function selectEntryCount () {
        /** エントリーの一覧を取得する */
        $SELECT_ENTRY_LIST = <<<__QUERY__
            SELECT count(*)
              FROM bbs_dt
__QUERY__;

        // 検索を実行する。
        $sth = $this->getConnection()->prepare( $SELECT_ENTRY_LIST );
        $rs = $sth->execute();

        // 結果セットがあれば件数を取得する
        if ( $row = $rs->fetchRow() ) {
            return $row[0];
        }
        return 0;
    }

    /**
     * 
     * @return unknown_type
     */
    public function selectEntryList ( $st, $ed ) {
        // 検索結果のリスト
        $resultList = array();

        /** エントリーの一覧を取得する */
        $SELECT_ENTRY_LIST = <<<__QUERY__
            SELECT
                id
               ,name
               ,body
               ,mail
               ,reg_time
              FROM
                bbs_dt
             WHERE del_flg = 0
              AND id BETWEEN ? AND ?
             ORDER BY id DESC
__QUERY__;

        // 検索を実行する。
        $sth = $this->getConnection()->prepare( $SELECT_ENTRY_LIST );
        $param_list = array();
        array_push( $param_list, $st );
        array_push( $param_list, $ed );
        $rs = $sth->execute( $param_list );

        // 結果をループして配列に格納する。
        while ( $row = $rs->fetchRow() ) {
            $entry = array();
            $entry['id'      ] = $row[0];
            $entry['name'    ] = $row[1];
            $entry['body'    ] = $row[2];
            $entry['mail'    ] = $row[3];
            $entry['reg_time'] = $row[4];

            // 配列に追加する。
            array_push( $resultList, $entry );
        }

        return $resultList;
    }

    /**
     * 書き込みを登録する。
     * @param  $registerList 登録する書き込み内容
     * @return $cnt 登録した件数
     */
    public function insertEntry( $registMap ) {
        $INSERT_ENTRY = <<<__QUERY__
            INSERT INTO bbs_dt
                (name, mail, body, reg_time, update_time)
            VALUES
                (?, ?, ?, datetime('now', 'localtime'), datetime('now', 'localtime'));
__QUERY__;
        
        // SQLクエリのプリコンパイル
        $sth = $this->getConnection()->prepare( $INSERT_ENTRY );

        // プレースホルダセット
        $args = array();
        array_push( $args, $registMap['name'] );
        array_push( $args, $registMap['mail'] );
        array_push( $args, $registMap['body'] );
        $rs = $sth->execute( $args );

        if( MDB2::isError( $rs ) ) {
            $this->setErrorMessage( $rs->getMessage() );die($rs->getMessage());
        }
        return $rs;
    }
}
