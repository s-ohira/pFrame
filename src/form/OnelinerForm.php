<?php
/**
 * 一行掲示板ActionFormクラス
 * @author Seiji OHIRA
 * @since 2010-10-17
 */
class OnelinerForm extends ValidationForm {
    /** 名前 */
    private $name;
    /** 本文 */
    private $body;
    /** メールアドレス */
    private $mail;
    /** 書き込みのID */
    private $id;
    /** ページ */
    private $page;

    /**
     * 名前を取得する
     * @return String $name
     */
    public function getName () {
        return $this->name;
    }

    /**
     * 名前を設定する
     * @param String $name
     */
    public function setName ($name) {
        $this->name = $name;
    }

    /**
     * 本文を取得する
     * @return String $body
     */
    public function getBody () {
        return $this->body;
    }

    /**
     * 本文を設定する
     * @param String $body
     */
    public function setBody ($body) {
        $this->body = $body;
    }

    /**
     * メールアドレスを取得する
     * @return String $mail
     */
    public function getMail () {
        return $this->mail;
    }

    /**
     * メールアドレスを設定する
     * @param String $mail
    */
    public function setMail ($mail) {
        $this->mail = $mail;
    }

    /**
     * エントリーのIDを取得する
     * @return String $id
     */
    public function getId () {
        return $this->id;
    }

    /**
     * エントリーのIDを設定する
     * @param String $id
     */
    public function setId ($id) {
        $this->id = $id;
    }

    /**
     * ページNoを取得する
     * @return String $page
     */
    public function getPage () {
        return $this->page;
    }

    /**
     * ページNoを設定する
     * @param String $page
     */
    public function setPage ($page) {
        $this->page = $page;
    }

    public function toString () {
        return '['                             + "\n"
            + '  name:'      + $this->name     + "\n"
            + '  body: '     + $this->body     + "\n"
            + '  mail: '     + $this->mail     + "\n"
            + '  entry_id: ' + $this->id       + "\n"
            + '  page: '     + $this->page     + "\n"
            + ']';
    }
}