<?php
/**
 * Framwork main
 * @package jp.coocan.la.estructura.pframe
 * @access public
 * @author Seiji OHIRA
 * @since 2009-12-27
 * <pre>
 * Freely distributable under MIT-style license.
 * </pre>
 */
// Action dispatcher
require_once '../lib/jp/coocan/la/estructura/pframe/RequestProcessor.php';
// Base ActionForm Class
require_once '../lib/jp/coocan/la/estructura/pframe/ActionForm.php';
// Base Action Class
require_once '../lib/jp/coocan/la/estructura/pframe/Action.php';
// View Handling Class
require_once '../lib/jp/coocan/la/estructura/pframe/ActionMapping.php';
// ActionForm to validate Class
require_once '../lib/jp/coocan/la/estructura/pframe/ValidationForm.php';
// Delegate Class
require_once '../lib/jp/coocan/la/estructura/pframe/Delegate.php';
// Base Logic Class
require_once '../lib/jp/coocan/la/estructura/pframe/Logic.php';
// Dao Class
require_once '../lib/jp/coocan/la/estructura/pframe/Dao.php';
// Utility Library Class
require_once '../lib/jp/coocan/la/estructura/pframe/common/CommonUtils.php';
// HTTP Request Object
require_once '../lib/jp/coocan/la/estructura/pframe/HttpRequest.php';


// PHP HTML::Template
require_once '../lib/ext/template.php';
// PurePHP YAML Parser
require_once '../lib/ext/spyc.php';
// PAER MDB2 Module
require_once 'MDB2.php';

// execute
$request  = new HttpRequest();
$requestProcessor = new RequestProcessor();
$requestProcessor->process( $request );
