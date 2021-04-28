<?php
/**
* DokuWiki Plugin networkviz (Syntax Component)
*
* @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
* @author  James Guerra <jamesguerra2008@gmail.com>
*/

if (!defined('DOKU_INC')) {
    die();
}

class syntax_plugin_networkviz extends DokuWiki_Syntax_Plugin {
    function getType(){
        return 'substition';
    }

   function getPType(){
       return 'block';
   }
 
    function getSort(){
        return 200;
    }
 
 
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<networkviz.+?</networkviz>',$mode,'plugin_networkviz');
    }
 
//    function postConnect() {
//      $this->Lexer->addExitPattern('</TEST>','plugin_test');
//    }
 
    function handle($match, $state, $pos, Doku_Handler $handler){
        if ($state == DOKU_LEXER_SPECIAL) {
            $match = substr(trim($match), 11);
            list($opts, $adata) = explode('>', $match, 2);
            preg_match_all('/(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?/', $opts, $matches, PREG_SET_ORDER);
            
            return array($matches);
        }
        return true;
    }
 
    function render($mode, $renderer, $data) {
        $file = $data[0][0][2];

        if($mode == 'xhtml'){
            if(!empty($file)) {
                if($file !== '' && !preg_match('/^https?:\/\//i', $file)) {
                    // $renderer->doc .= $file;
                } elseif(preg_match('/^https?:\/\//i', $file)) {
                    $http = new DokuHTTPClient();
                    $content = $http->get($file);
                    try {
                        if($content === false) {
                            throw new \Exception('Chart cannot be displayed ! Failed to fetch remote CSV file');
                        }
                    } catch (\Exception $e) {
                        msg(hsc($e->getMessage()), -1);
                        return false;
                    }
                } else {
                    $file = mediaFN($file);
                    try {
                        if(auth_quickaclcheck($file . ':*') < AUTH_READ) {
						    throw new \Exception('Chart cannot be displayed ! Access denied to CSV file');
                        }
					    if(!file_exists($file)) {
						    throw new \Exception('Chart cannot be displayed ! Requested local CSV file does not exist');
                        }
                    } catch (\Exception $e) {
                        msg(hsc($e->getMessage()), -1);
                        return false;
                    }
                }
                $url = $_SERVER["REQUEST_URI"];
                
                //render preloader spinner
                $renderer->doc .= '<div class="spinner">
                                     <div class="rect1"></div>
                                     <div class="rect2"></div>
                                     <div class="rect3"></div>
                                     <div class="rect4"></div>
                                     <div class="rect5"></div>
                                   </div>';
                
                //render network
                $renderer->doc .= '<div id="interactive-graph" style="height:800px" data-graph="' . $file . '"' . '></div>';
                //render dokuwiki logo anchor
                $renderer-> doc .= '<a id="dokuwiki-logo" href="doku.php?id=start">
                                        <img src="./lib/plugins/networkviz/logo.png" width="64" height="64" alt="">
                                        <span>DokuWiki</span>
                                    </a>';
                $renderer->doc .= '<div class="network-searcher">
                                    <input placeholder="Press &#9166 to start searching this network"></input>
                                    <div class="results-container"></div>
                                   </div>';
                //render overlay/redirect prompt
                $renderer->doc .= '<div id="overlay">
                                      <div class="redirect-container">
                                        <p class="redirect-prompt"></p>
                                        <form action="doku.php" method="get" id="confirm-redirect">
                                            <button type="submit" value="" name="id" id="confirm-button">Confirm</button>
                                        </form>
                                        <button class="button" id="cancel-button">Cancel</button>
                                      </div>
                                   </div>';
            }
            return true;
        }
        return false;
    }
}