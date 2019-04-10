<?php

/**
 * A subclass of MarkdownExtra parser, with specific handling of
 * features required by SilverStripe.
 *
 * author: mark@silverstripe.com
 * license: BSD
 */

class MarkdownUltra_Parser extends MarkdownExtra_Parser {
	// array of languages used
	protected $languages;

	function MarkdownUltra_Parser() {
		parent::MarkdownExtra_Parser();

		$this->languages = array();
		$parser = new MarkdownExtra_Parser();
	}

	/**
	 * After a parse, the container can get the languages so it can
	 * include the right js and css includes.
	 */
	function getLanguages() {
		return $this->languages;
	}

	/**
	 * Override of fenced code block logic, which handles
	 * code blocks starting with ~~~ or ~~~ {lang}.
	 * In either case, it emits a <pre> block with class info
	 * compatible with syntaxhighlighter javascript module. If no
	 * language is specified, php is assumed.
	 */
	function doFencedCodeBlocks($text) {
	#
	# Adding the fenced code block syntax to regular Markdown:
	#
	# ~~~
	# Code block
	# ~~~
	#
		$less_than_tab = $this->tab_width;
		
		$text = preg_replace_callback('{
				(?:\n|\A)
				# 1: Opening marker
				(
					~{3,} # Marker: three tilde or more.
				)
				[ ]*(\{[a-zA-Z]*\})? # lang
				[ ]* \n # Whitespace and newline following marker.
				
				# 2: Content
				(
					(?>
						(?!\1 [ ]* \n)	# Not a closing marker.
						.*\n+
					)+
				)
				
				# Closing marker.
				\1 [ ]* \n
			}xm',
			array(&$this, '_doFencedCodeBlocks_callback'), $text);

		return $text;
	}
	function _doFencedCodeBlocks_callback($matches) {
		$lang = $matches[2];
		if (preg_match("/^\{.*\}$/", $lang)) $lang = substr($lang, 1, -1);
		$codeblock = $matches[3];
		$codeblock = htmlspecialchars($codeblock, ENT_NOQUOTES);
		$codeblock = preg_replace_callback('/^\n+/',
			array(&$this, '_doFencedCodeBlocks_newlines'), $codeblock);
		if (!$lang) $lang = "php";
		$this->languages[] = $lang;
		$codeblock = "<pre class=\"brush: $lang\">$codeblock</pre>";
		return "\n\n".$this->hashBlock($codeblock)."\n\n";
	}
	function _doFencedCodeBlocks_newlines($matches) {
		return str_repeat("<br$this->empty_element_suffix", 
			strlen($matches[0]));
	}

}
