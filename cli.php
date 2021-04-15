<?php
/**
 * DokuWiki Plugin networkviz (CLI Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  James <jamesguerra2008@gmail.com>
 */

use splitbrain\phpcli\Options;

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}


class cli_plugin_networkviz extends DokuWiki_CLI_Plugin
{
    private $isRendered = false;
    /**
     * Register options and arguments on the given $options object
     *
     * @param Options $options
     *
     * @return void
     */
    protected function setup(Options $options)
    {
        $options->setHelp('This is Network_Viz, a graph visualisation tool for Dokuwiki');

        // options
        $options->registerOption(
            'render', 
            'renders graph', 
            'r'
        );
    }

    /**
     * Your main program
     *
     * Arguments and options have been parsed when this is run
     *
     * @param Options $options
     *
     * @return void
     */
    protected function main(Options $options)
    {
        $this->isRendered = $options->getOpt('render');
        if($this->isRendered) $this->renderGraph();
    }

    protected function renderGraph() {
        
    }

}
