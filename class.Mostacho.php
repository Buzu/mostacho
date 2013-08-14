<?php
/*
 * Mostacho is a simple keyword replacement system.
 * You can register keywords, and their replacement.
 * Then you initialize Mostacho at the begining of your document
 * And flush the contents at the end.
 * The keywords will be replaced by their values in your document.
*/
class Mostacho {
	private $keywords = array();
	
	public function start() {
		ob_start();
	}

	public function flush() {
		$content = ob_get_contents();
		ob_end_clean();
		$new_content = $this->performReplace($content);
		echo $new_content;
	}

	public function addKeyword($word, $replacement) {
		if (array_key_exists($word, $this->keywords)) {
			throw new Exception("Keyword $word is alredy registered");
		}

		$this->replaceKeyword($word, $replacement);
	}

	public function replaceKeyword($word, $replacement) {
		$this->keywords[$word] = $replacement;
	}

	public function performReplace($content) {
		// Get around php 5.3 that doesn't keep $this
		// in the callback of preg_replace
		global $keywords;
		$keywords = $this->keywords;
		// Using arrays on the pattern and replacement
		// Can act weird in the off chance that the
		// keyword is defined in different order
		// than the replacement. Should never happen
		// but just in case, I used preg_replace_callback
		// and defined keyword and definition as an indexed array
		// see http://us3.php.net/manual/en/function.preg-replace.php
		return preg_replace_callback("/\{([a-zA-Z_]+)\}/", function($matches) {
			global $keywords;
			if (isset($keywords[$matches[1]])) {
				return $keywords[$matches[1]];
			}
			return $matches[1];
		}, $content);
		unset($GLOBALS['keywords']); // TODO: unset is never run... function returns first!
	}
}
?>
