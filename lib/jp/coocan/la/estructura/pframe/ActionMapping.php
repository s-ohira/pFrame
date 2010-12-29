<?php
/**
 * View Class
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-05-31
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
class ActionMapping {
    /**
     * return response
     * @param String $forward
     * @param String $mapping
     * @param HttpRequest $request
     */
    public function forward ( $mapping, $forward, $request ) {
        // HtmlTemplateオプション
        $options = array(
                'filename'          => $mapping[$forward]
               ,'debug'             => 0
               ,'die_on_bad_params' => 0
            );

        // create HTML::Template instance and output response
        $tmpl = new Template( $options );
        $attribute = $request->getAttribute();
        foreach ( $attribute AS $key => $value ) {
            $tmpl->AddParam( $key, $value );
        }
        $tmpl->EchoOutput();
    }
}
