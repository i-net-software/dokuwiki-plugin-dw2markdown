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


if ( file_exists( DOKU_PLUGIN.'dw2markdown/lib/converter/scripts/DokuWikiToMarkdown.php' ) ) {
    
    require_once DOKU_PLUGIN.'dw2markdown/lib/converter/scripts/DokuWikiToMarkdown.php';
    
    class renderer_plugin_dw2markdown extends Doku_Renderer_xhtml {

        function document_start() {
            global $ID;

            $this->doc = '';
            $toc = array();
            $footnotes = array();
            $store = '';
            $nSpan = 0;
            $separator = '';

            $metaheader = array();
            $metaheader['Content-Type'] = 'plain/text; charset=iso-8859-1';
            $metaheader['Content-Disposition'] = 'attachment; filename="' . noNS($ID) . '.md"';
            $meta = array();
            $meta['format']['dw2markdown'] = $metaheader;
            p_set_metadata($ID,$meta);
        }
        
        // FIXME implement all methods of Doku_Renderer here
        public function document_end(){
            
            $converter = new DokuWikiToMarkdown();
            $this->doc = $converter->convert( $this->doc );
        }
    
        /**
         * Render a heading
         *
         * @param string $text  the text to display
         * @param int    $level header level
         * @param int    $pos   byte position in the original source
         */
        function header($text, $level, $pos) {
            $this->doc .= str_repeat("#", $level) . ' ' . $text . ' ' . str_repeat("#", $level) . DOKU_LF;
        }
    
        /**
         * Open a new section
         *
         * @param int $level section level (as determined by the previous header)
         */
        function section_open($level) {
            $this->doc .= DOKU_LF;
        }
    
        /**
         * Close the current section
         */
        function section_close() {
            $this->doc .= DOKU_LF;
        }
    
        /**
         * Render plain text data
         *
         * @param string $text
         */
        function cdata($text) {
            $this->doc .= $text;
        }
    
        /**
         * Open a paragraph
         */
        function p_open() {
            $this->doc .= DOKU_LF;
        }
    
        /**
         * Close a paragraph
         */
        function p_close() {
            $this->doc .= DOKU_LF;
        }
    
        /**
         * Create a line break
         */
        function linebreak() {
            $this->doc .= DOKU_LF . DOKU_LF;
        }
    
        /**
         * Create a horizontal line
         */
        function hr() {
            $this->doc .= '----';
        }
    
        /**
         * Start strong (bold) formatting
         */
        function strong_open() {
            $this->doc .= '**';
        }
    
        /**
         * Stop strong (bold) formatting
         */
        function strong_close() {
            $this->doc .= '**';
        }
    
        /**
         * Start emphasis (italics) formatting
         */
        function emphasis_open() {
            $this->doc .= '//';
        }
    
        /**
         * Stop emphasis (italics) formatting
         */
        function emphasis_close() {
            $this->doc .= '//';
        }
    
        /**
         * Start underline formatting
         */
        function underline_open() {
            $this->doc .= '__';
        }
    
        /**
         * Stop underline formatting
         */
        function underline_close() {
            $this->doc .= '__';
        }
    
        /**
         * Start monospace formatting
         */
        function monospace_open() {
            $this->doc .= "''";
        }
    
        /**
         * Stop monospace formatting
         */
        function monospace_close() {
            $this->doc .= "''";
        }
    
        /**
         * Start a subscript
         */
        function subscript_open() {
            $this->doc .= '<sub>';
        }
    
        /**
         * Stop a subscript
         */
        function subscript_close() {
            $this->doc .= '</sub>';
        }
    
        /**
         * Start a superscript
         */
        function superscript_open() {
            $this->doc .= '<sup>';
        }
    
        /**
         * Stop a superscript
         */
        function superscript_close() {
            $this->doc .= '</sup>';
        }
    
        /**
         * Start deleted (strike-through) formatting
         */
        function deleted_open() {
            $this->doc .= '<del>';
        }
    
        /**
         * Stop deleted (strike-through) formatting
         */
        function deleted_close() {
            $this->doc .= '</del>';
        }
    
        /**
         * Start a footnote
         */
        function footnote_open() {
            $this->doc .= '((';
        }
    
        /**
         * Stop a footnote
         */
        function footnote_close() {
            $this->doc .= '))';
        }
    
        /**
         * Open an unordered list
         */
        function listu_open($classes = null) {
        }
    
        /**
         * Close an unordered list
         */
        function listu_close() {
        }
    
        /**
         * Open an ordered list
         */
        function listo_open($classes = null) {
        }
    
        /**
         * Close an ordered list
         */
        function listo_close() {
        }
    
        /**
         * Open a list item
         *
         * @param int $level the nesting level
         * @param bool $node true when a node; false when a leaf
         */
        function listitem_open($level,$node=false) {
            $this->doc .= str_repeat(' ', $level*2) . '*';
        }
    
        /**
         * Close a list item
         */
        function listitem_close() {
            $this->doc .= DOKU_LF;
        }
    
        /**
         * Start the content of a list item
         */
        function listcontent_open() {
        }
    
        /**
         * Stop the content of a list item
         */
        function listcontent_close() {
            $this->doc .= DOKU_LF;
        }
    
        /**
         * Output unformatted $text
         *
         * Defaults to $this->cdata()
         *
         * @param string $text
         */
        function unformatted($text) {
            $this->cdata($text);
        }
    
        /**
         * Output inline PHP code
         *
         * If $conf['phpok'] is true this should evaluate the given code and append the result
         * to $doc
         *
         * @param string $text The PHP code
         */
        function php($text, $wrapper = 'code') {
        }
    
        /**
         * Output block level PHP code
         *
         * If $conf['phpok'] is true this should evaluate the given code and append the result
         * to $doc
         *
         * @param string $text The PHP code
         */
        function phpblock($text) {
        }
    
        /**
         * Output raw inline HTML
         *
         * If $conf['htmlok'] is true this should add the code as is to $doc
         *
         * @param string $text The HTML
         */
        function html($text, $wrapper = 'code') {
        }
    
        /**
         * Output raw block-level HTML
         *
         * If $conf['htmlok'] is true this should add the code as is to $doc
         *
         * @param string $text The HTML
         */
        function htmlblock($text) {
        }
    
        /**
         * Output preformatted text
         *
         * @param string $text
         */
        function preformatted($text) {
            $this->doc .= DOKU_LF . "\t" . $text;
        }
    
        /**
         * Start a block quote
         */
        function quote_open() {
        }
    
        /**
         * Stop a block quote
         */
        function quote_close() {
        }
    
        /**
         * Display text as file content, optionally syntax highlighted
         *
         * @param string $text text to show
         * @param string $lang programming language to use for syntax highlighting
         * @param string $file file path label
         */
        function file($text, $lang = null, $file = null) {
        }
    
        /**
         * Display text as code content, optionally syntax highlighted
         *
         * @param string $text text to show
         * @param string $lang programming language to use for syntax highlighting
         * @param string $file file path label
         */
        function code($text, $lang = null, $file = null) {
            $this->doc .= '<code ' . $lang . '>' . DOKU_LF . $text . DOKU_LF . '</code>';
        }
    
        /**
         * Format an acronym
         *
         * Uses $this->acronyms
         *
         * @param string $acronym
         */
        function acronym($acronym) {
            $this->doc .= $acronym;
        }
    
        /**
         * Format a smiley
         *
         * Uses $this->smiley
         *
         * @param string $smiley
         */
        function smiley($smiley) {
            $this->doc .= $smiley;
        }
    
        /**
         * Format an entity
         *
         * Entities are basically small text replacements
         *
         * Uses $this->entities
         *
         * @param string $entity
         */
        function entity($entity) {
            $this->doc .= $entity;
        }
    
        /**
         * Typographically format a multiply sign
         *
         * Example: ($x=640, $y=480) should result in "640×480"
         *
         * @param string|int $x first value
         * @param string|int $y second value
         */
        function multiplyentity($x, $y) {
            $this->doc .= "$x×$y";
        }
    
        /**
         * Render an opening single quote char (language specific)
         */
        function singlequoteopening() {
            $this->doc .= "'";
        }
    
        /**
         * Render a closing single quote char (language specific)
         */
        function singlequoteclosing() {
            $this->doc .= "'";
        }
    
        /**
         * Render an apostrophe char (language specific)
         */
        function apostrophe() {
        }
    
        /**
         * Render an opening double quote char (language specific)
         */
        function doublequoteopening() {
            $this->doc .= '"';
        }
    
        /**
         * Render an closinging double quote char (language specific)
         */
        function doublequoteclosing() {
            $this->doc .= '"';
        }
    
        /**
         * Render a CamelCase link
         *
         * @param string $link The link name
         * @see http://en.wikipedia.org/wiki/CamelCase
         */
        function camelcaselink($link, $returnonly = false) {
            $this->doc .= '[[' . $link . ']]';
        }
    
        /**
         * Render a page local link
         *
         * @param string $hash hash link identifier
         * @param string $name name for the link
         */
        function locallink($hash, $name = null, $returnonly = false) {
            $this->doc .= '[[' . $name . ']]';
        }
    
        /**
         * Render a wiki internal link
         *
         * @param string       $link  page ID to link to. eg. 'wiki:syntax'
         * @param string|array $title name for the link, array for media file
         */
        function internallink($id, $name = null, $search = null, $returnonly = false, $linktype = 'content') {
            $this->doc .= '[[' . $link . '|' . $title . ']]';
        }
    
        /**
         * Render an external link
         *
         * @param string       $link  full URL with scheme
         * @param string|array $title name for the link, array for media file
         */
        function externallink($link, $title = null, $returnonly = false) {
            $this->doc .= '[[' . $link . '|' . $title . ']]';
        }
    
        /**
         * Render the output of an RSS feed
         *
         * @param string $url    URL of the feed
         * @param array  $params Finetuning of the output
         */
        function rss($url, $params) {
            $this->doc .= '[[rss>' . link . '|' . $title . ']]';
        }
    
        /**
         * Render an interwiki link
         *
         * You may want to use $this->_resolveInterWiki() here
         *
         * @param string       $link     original link - probably not much use
         * @param string|array $title    name for the link, array for media file
         * @param string       $wikiName indentifier (shortcut) for the remote wiki
         * @param string       $wikiUri  the fragment parsed from the original link
         */
        function interwikilink($match, $name, $wikiName, $wikiUri, $returnonly = false) {
        }
    
        /**
         * Link to file on users OS
         *
         * @param string       $link  the link
         * @param string|array $title name for the link, array for media file
         */
        function filelink($link, $title = null) {
        }
    
        /**
         * Link to windows share
         *
         * @param string       $link  the link
         * @param string|array $title name for the link, array for media file
         */
        function windowssharelink($link, $title = null, $returnonly = false) {
        }
    
        /**
         * Render a linked E-Mail Address
         *
         * Should honor $conf['mailguard'] setting
         *
         * @param string $address Email-Address
         * @param string|array $name name for the link, array for media file
         */
        function emaillink($address, $name = null, $returnonly = false) {
        }
    
        /**
         * Render an internal media file
         *
         * @param string $src     media ID
         * @param string $title   descriptive text
         * @param string $align   left|center|right
         * @param int    $width   width of media in pixel
         * @param int    $height  height of media in pixel
         * @param string $cache   cache|recache|nocache
         * @param string $linking linkonly|detail|nolink
         */
        function internalmedia($src, $title = null, $align = null, $width = null,
                               $height = null, $cache = null, $linking = null, $return = false) {
        }
    
        /**
         * Render an external media file
         *
         * @param string $src     full media URL
         * @param string $title   descriptive text
         * @param string $align   left|center|right
         * @param int    $width   width of media in pixel
         * @param int    $height  height of media in pixel
         * @param string $cache   cache|recache|nocache
         * @param string $linking linkonly|detail|nolink
         */
        function externalmedia($src, $title = null, $align = null, $width = null,
                               $height = null, $cache = null, $linking = null, $return = false) {
        }
    
        /**
         * Render a link to an internal media file
         *
         * @param string $src     media ID
         * @param string $title   descriptive text
         * @param string $align   left|center|right
         * @param int    $width   width of media in pixel
         * @param int    $height  height of media in pixel
         * @param string $cache   cache|recache|nocache
         */
        function internalmedialink($src, $title = null, $align = null,
                                   $width = null, $height = null, $cache = null) {
        }
    
        /**
         * Render a link to an external media file
         *
         * @param string $src     media ID
         * @param string $title   descriptive text
         * @param string $align   left|center|right
         * @param int    $width   width of media in pixel
         * @param int    $height  height of media in pixel
         * @param string $cache   cache|recache|nocache
         */
        function externalmedialink($src, $title = null, $align = null,
                                   $width = null, $height = null, $cache = null) {
        }
    
        /**
         * Start a table
         *
         * @param int $maxcols maximum number of columns
         * @param int $numrows NOT IMPLEMENTED
         * @param int $pos     byte position in the original source
         */
        function table_open($maxcols = null, $numrows = null, $pos = null, $classes = null) {
        }
    
        /**
         * Close a table
         *
         * @param int $pos byte position in the original source
         */
        function table_close($pos = null) {
            $this->doc .= DOKU_LF;
        }
    
        /**
         * Open a table header
         */
        function tablethead_open() {
        }
    
        /**
         * Close a table header
         */
        function tablethead_close() {
            $this->doc .= '^';
        }
    
        /**
         * Open a table body
         */
        function tabletbody_open() {
        }
    
        /**
         * Close a table body
         */
        function tabletbody_close() {
        }
    
        /**
         * Open a table row
         */
        function tablerow_open($classes = null) {
            $this->doc .= DOKU_LF;
        }
    
        /**
         * Close a table row
         */
        function tablerow_close() {
        }
    
        /**
         * Open a table header cell
         *
         * @param int    $colspan
         * @param string $align left|center|right
         * @param int    $rowspan
         */
        function tableheader_open($colspan = 1, $align = null, $rowspan = 1, $classes = null) {
            $this->doc .= str_repeat( '^', $colspan );
        }
    
        /**
         * Close a table header cell
         */
        function tableheader_close() {
        }
    
        /**
         * Open a table cell
         *
         * @param int    $colspan
         * @param string $align left|center|right
         * @param int    $rowspan
         */
        function tablecell_open($colspan = 1, $align = null, $rowspan = 1, $classes = null) {
            
            if ( $this->doc[strlen($this->doc)-1] == '|' ) {
                $colspan--;    
            }
            
            $this->doc .= str_repeat( '|', $colspan );
        }
    
        /**
         * Close a table cell
         */
        function tablecell_close() {
            $this->doc .= '|';
        }
    
        #endregion
    }
}
