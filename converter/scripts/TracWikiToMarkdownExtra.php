<?php

require_once 'MarkdownCleanup.php';

/**
 * Very rudimentary script to convert *some* trac wiki format
 * from open.silverstripe.org. Mainly used for changelogs with relatively
 * simple formatting so far.
 * 
 * @todo Code blocks
 * @todo Italics, bold
 * 
 * @author Ingo Schommer
 */
class TracWikiToMarkdownExtra extends MarkdownCleanup {
	
	protected $replacements = array(
		'/^=([^=]*) [=\s]*/'			=>	array("rewrite" => '# $1'),
		'/^==([^=]*) [=\s]*/'			=>	array("rewrite" => '## $1'),
		'/^===([^=]*) [=\s]*/'			=>	array("rewrite" => '### $1'),
		'/^====([^=]*) [=\s]*/'			=>	array("rewrite" => '#### $1'),
		'/^=====([^=]*) [=\s]*/'		=>	array("rewrite" => '##### $1'),
		'/^======([^=]*) [=\s]*/'		=>	array("rewrite" => '###### $1'),
		// Remove bangs in front of non trac wiki auto links
		'/!([A-Z][a-z][A-Za-z0-9]*[A-Z][a-z][A-Za-z0-9]*)/' => array('rewrite' => '$1'),
		// ensure every list entry has at least one space
		'/^\*/' => array('rewrite' => ' *'),
		// rev: pseudolinks
		'/\[([\d]*)\]/' => array('rewrite' => '[rev:$1]'),
	);
	
	// Convert a docuwiki file in the input directory and called
	// $filename, and re-created it in the output directory, translated
	// to markdown extra.
	function convertFile($inputFile, $outputFile) {
		$this->fileName = $inputFile;
		$s = file_get_contents($inputFile);
		$s = $this->convert($s);
		var_dump($outputFile);
		if (file_put_contents($outputFile, $s) === FALSE) echo "Could not write file {$outputFile}\n";
	}
	
	function convert($content) {
		$out = array();
		
		$lines = $this->getLines($content);
		foreach($lines as $i => $line) {
			foreach($this->replacements as $rule => $replace) {
				$lines[$i] = preg_replace($rule, $replace['rewrite'], $lines[$i]);
			}
			$out[] = $lines[$i];
		}
		
		$content = implode("\n", $out);
		
		$content = $this->newlinesAfterHeadlines($content);
		$content = $this->newlinesBeforeLists($content);		
		
		return $content;
	}

	
}

$inputDirectory = "../input/changelogs/";
$outputDirectory = "../master/cms/docs/changelogs/";

$converter = new TracWikiToMarkdownExtra();

$inputPath = realpath($inputDirectory);

$objects = new RecursiveIteratorIterator(
               new RecursiveDirectoryIterator($inputPath), 
               RecursiveIteratorIterator::SELF_FIRST);

foreach($objects as $name => $object) {
	$filename = $object->getFilename();
	if ($filename == "." || $filename == "..") continue;
	
	$inputDir = $object->getPath();
	$outputPathRelative = str_replace($inputPath, '', $inputDir);
	
	if (is_dir($object->getPathname())) continue;

	$outputDir = realpath($outputDirectory);
	if (!file_exists($outputDir)) mkdir($outputDir, 0777, true);
	
	$outFilename = preg_replace('/\.txt$/', '.md', $filename);
	$converter->convertFile(
		"{$inputDir}/{$filename}",
		"{$outputDir}/{$outputPathRelative}/{$outFilename}"
	);
}