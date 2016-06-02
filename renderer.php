<?php
/**
 * DokuWiki Plugin dw2markdown (Renderer Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  i-net software <tools@inetsoftware.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

require_once DOKU_INC.'inc/parser/renderer.php';
require_once DOKU_PLUGIN.'dw2markdown/lib/converter/DokuWikiToMarkdown.php';

class renderer_plugin_dw2markdown extends Doku_Renderer {

    /**
     * The format this renderer produces
     */
    public function getFormat(){
        return 'dw2markdown';
    }

    // FIXME implement all methods of Doku_Renderer here
    public function document_end(){
        
        $document = rawWiki( getID() );
        
        $converter = new DokuWikiToMarkdown();
        $this->doc = $converter->convert( $document );
    }
}

