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
 
 
   /**
    * Handler to prepare matched data for the rendering process.
    *
    * <p>
    * The <tt>$aState</tt> parameter gives the type of pattern
    * which triggered the call to this method:
    * </p>
    * <dl>
    * <dt>DOKU_LEXER_ENTER</dt>
    * <dd>a pattern set by <tt>addEntryPattern()</tt></dd>
    * <dt>DOKU_LEXER_MATCHED</dt>
    * <dd>a pattern set by <tt>addPattern()</tt></dd>
    * <dt>DOKU_LEXER_EXIT</dt>
    * <dd> a pattern set by <tt>addExitPattern()</tt></dd>
    * <dt>DOKU_LEXER_SPECIAL</dt>
    * <dd>a pattern set by <tt>addSpecialPattern()</tt></dd>
    * <dt>DOKU_LEXER_UNMATCHED</dt>
    * <dd>ordinary text encountered within the plugin's syntax mode
    * which doesn't match any pattern.</dd>
    * </dl>
    * @param $aMatch String The text matched by the patterns.
    * @param $aState Integer The lexer state for the match.
    * @param $aPos Integer The character position of the matched text.
    * @param $aHandler Object Reference to the Doku_Handler object.
    * @return Integer The current lexer state for the match.
    * @public
    * @see render()
    * @static
    */
    function handle($match, $state, $pos, Doku_Handler $handler){
        if ($state == DOKU_LEXER_SPECIAL) {
            $match = substr(trim($match), 12, -10);
            return array($match);
        }
        return true;
        // switch ($state) {
        //   case DOKU_LEXER_ENTER : 
        //     break;
        //   case DOKU_LEXER_MATCHED :
        //     $match = "Lexer Matched";
        //     break;
        //   case DOKU_LEXER_UNMATCHED :
        //     break;
        //   case DOKU_LEXER_EXIT :
        //     break;
        //   case DOKU_LEXER_SPECIAL :
        //     break;
        // }
    }
 
   /**
    * Handle the actual output creation.
    *
    * <p>
    * The method checks for the given <tt>$aFormat</tt> and returns
    * <tt>FALSE</tt> when a format isn't supported. <tt>$aRenderer</tt>
    * contains a reference to the renderer object which is currently
    * handling the rendering. The contents of <tt>$aData</tt> is the
    * return value of the <tt>handle()</tt> method.
    * </p>
    * @param $mode String The output format to generate.
    * @param $aRenderer Object A reference to the renderer object.
    * @param $aData Array The data created by the <tt>handle()</tt>
    * method.
    * @return Boolean <tt>TRUE</tt> if rendered successfully, or
    * <tt>FALSE</tt> otherwise.
    * @public
    * @see handle()
    */
    function render($mode, $renderer, $data) {
        $test = $data;
        if($mode == 'xhtml'){
            $renderer->doc .= $test[0];     // ptype = 'block'
            return true;
        }
        return false;
    }
}