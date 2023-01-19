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


class Renderer_Plugin_dw2markdown extends Doku_Renderer_xhtml {

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
        //$converter = new DokuWikiToMarkdown();
        //$this->doc = $converter->convert( $this->doc );
        
        $this->doc = preg_replace("/(\r?\n){3,}/", "\n\n", $this->doc);
        $this->doc = preg_replace("/^\s+/", "", $this->doc); // remove leading space and empty lines
    }

    /**
     * Render a heading
     *
     * @param string $text  the text to display
     * @param int    $level header level
     * @param int    $pos   byte position in the original source
     */
    function header($text, $level, $pos, $returnonly = false) {
        $this->doc .= str_repeat("#", $level) . ' ' . $text . DOKU_LF;
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
        $this->doc .= '*';
    }

    /**
     * Stop emphasis (italics) formatting
     */
    function emphasis_close() {
        $this->doc .= '*';
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
        $this->doc .= "`";
    }

    /**
     * Stop monospace formatting
     */
    function monospace_close() {
        $this->doc .= "`";
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
        $this->doc .= '~~';
    }

    /**
     * Stop deleted (strike-through) formatting
     */
    function deleted_close() {
        $this->doc .= '~~';
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

    private $listMode = [];

    /**
     * Open an unordered list
     */
    function listu_open($classes = null) {
        array_push( $this->listMode, '*' );
    }

    /**
     * Close an unordered list
     */
    function listu_close() {
        array_pop( $this->listMode );
        if ( empty($this->listMode) ) {
            $this->doc .= DOKU_LF;
        }
    }

    /**
     * Open an ordered list
     */
    function listo_open($classes = null) {
        array_push( $this->listMode, '1.' );
    }

    /**
     * Close an ordered list
     */
    function listo_close() {
        array_pop( $this->listMode );
        if ( empty($this->listMode) ) {
            $this->doc .= DOKU_LF;
        }
    }

    /**
     * Open a list item
     *
     * @param int $level the nesting level
     * @param bool $node true when a node; false when a leaf
     */
    function listitem_open($level,$node=false) {
        $this->doc .= DOKU_LF;
        $this->doc .= str_repeat(' ', $level*2) . $this->listMode[count($this->listMode)-1];
    }

    /**
     * Close a list item
     */
    function listitem_close() {
//        $this->doc .= DOKU_LF;
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
//        $this->doc .= DOKU_LF;
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
        $this->code($text, 'php');
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
        $this->php($text);
    }

    /**
     * Output raw inline HTML
     *
     * If $conf['htmlok'] is true this should add the code as is to $doc
     *
     * @param string $text The HTML
     */
    function html($text, $wrapper = 'code') {
        $this->code($text, 'html');
    }

    /**
     * Output raw block-level HTML
     *
     * If $conf['htmlok'] is true this should add the code as is to $doc
     *
     * @param string $text The HTML
     */
    function htmlblock($text) {
        $this->html($text);
    }

    /**
     * Output preformatted text
     *
     * @param string $text
     */
    function preformatted($text) {
        $this->doc .= DOKU_LF . "\t" . implode( "\n\t", explode("\n", $text) );
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
    function file($text, $language = NULL, $filename = NULL, $options = NULL) {
    }

    /**
     * Display text as code content, optionally syntax highlighted
     *
     * @param string $text text to show
     * @param string $lang programming language to use for syntax highlighting
     * @param string $file file path label
     */
    function code($text, $language = NULL, $filename = NULL, $options = NULL) {
        $this->doc .= DOKU_LF . '```' . $language . DOKU_LF . trim($text) . DOKU_LF . '```' . DOKU_LF;
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
    /*function entity($entity) {
        $this->doc .= $entity;
    }*/

    /**
     * Typographically format a multiply sign
     *
     * Example: ($x=640, $y=480) should result in "640×480"
     *
     * @param string|int $x first value
     * @param string|int $y second value
     */
    /*function multiplyentity($x, $y) {
        $this->doc .= "$x×$y";
    }*/

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
     * Render the output of an RSS feed
     *
     * @param string $url    URL of the feed
     * @param array  $params Finetuning of the output
     */
    function rss($url, $params) {
        $this->externallink( $url );
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

    private $tableColumns = 0;

    /**
     * Open a table header
     */
    function tablethead_open() {
        $this->tableColumns = 0;
        $this->doc .= DOKU_LF; // . '|';
    }

    /**
     * Close a table header
     */
    function tablethead_close() {
        $this->doc .= '|' . str_repeat('---|', $this->tableColumns) . DOKU_LF;
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
    }

    /**
     * Close a table row
     */
    function tablerow_close() {
        $this->doc .= '|' . DOKU_LF;
    }

    /**
     * Open a table header cell
     *
     * @param int    $colspan
     * @param string $align left|center|right
     * @param int    $rowspan
     */
    function tableheader_open($colspan = 1, $align = null, $rowspan = 1, $classes = null) {
        $this->doc .= str_repeat( '|', $colspan );
        $this->tableColumns += $colspan; 
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
        $this->doc .= str_repeat( '|', $colspan );
    }

    /**
     * Close a table cell
     */
    function tablecell_close() {
    }
    
    function getFormat() {
        return 'markdown';
    }

    /**
     * Render a page local link
     *
     * @param string $hash       hash link identifier
     * @param string $name       name for the link
     * @param bool   $returnonly whether to return html or write to doc attribute
     * @return void|string writes to doc attribute or returns html depends on $returnonly
     */
    public function locallink($hash, $name = null, $returnonly = false) {
        global $ID;
        $name  = $this->_getLinkTitle($name, $hash, $isImage);
        $hash  = $this->_headerToLink($hash);

        $doc = '['.$name.'](#'.$hash.')';

        if($returnonly) {
          return $doc;
        } else {
          $this->doc .= $doc;
        }
    }

    #endregion
    /**
     * Build a link
     *
     * Assembles all parts defined in $link returns HTML for the link
     *
     * @param array $link attributes of a link
     * @return string
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    public function _formatLink($link) {
        //make sure the url is XHTML compliant (skip mailto)
        if(substr($link['url'], 0, 7) != 'mailto:') {
            $link['url'] = str_replace('&', '&amp;', $link['url']);
            $link['url'] = str_replace('&amp;amp;', '&amp;', $link['url']);
        }
        //remove double encodings in titles
        $link['title'] = str_replace('&amp;amp;', '&amp;', $link['title']);

        // be sure there are no bad chars in url or title
        // (we can't do this for name because it can contain an img tag)
        $link['url']   = strtr($link['url'], array('>' => '%3E', '<' => '%3C', '"' => '%22'));
        $link['title'] = strtr($link['title'], array('>' => '&gt;', '<' => '&lt;', '"' => '&quot;'));
  
        $res = $link['pre'] . '[' . $link['name'] . '](' . $link['url'] . ')' . $link['suf'];
        return $res;
    }
}
