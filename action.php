<?php
/**
 * DokuWiki Plugin networkviz (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  James <jamesguerra2008@gmail.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}


class action_plugin_networkviz extends DokuWiki_Action_Plugin
{
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, '_addJavascript');
    }

    /**
     * Add JavaScripts, depending on chosen abc library
     *
     * Called for event: TPL_METAHEADER_OUTPUT
     *
     * @param Doku_Event $event  event object by reference
     *
     * @return void
     */

    public function _addJavascript(Doku_Event $event)
    {
        $event->data['script'][] = array(
            'type'    => 'text/javascript',
            'charset' => 'utf-8',
            '_data'   => '',
            'src'     => 'http://code.jquery.com/jquery-latest.min.js'
        );

        $event->data['script'][] = array(
            'type'    => 'text/javascript',
            'charset' => 'utf-8',
            '_data'   => '',
            'src'     => 'https://unpkg.com/vis-network/standalone/umd/vis-network.min.js'
        );

        $event->data['script'][] = array(
            'type'    => 'text/javascript',
            'charset' => 'utf-8',
            '_data'   => '',
            'src'     => '/lib/plugins/networkviz/script.js'
        );
        
    }

}
?>