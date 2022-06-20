<?php

/**
 *  * Semplice template engine per poter gestire le view come template.
 * Un template engine è utile per tenere il codice separato dai template visuali.
 *
 *
 * la funzione view della classe TemplateCore prende un file .html in ingresso e dei parametri.
 * Nel file html passato vengono sostituiti (tramite preg_replace_all) tutti i placeholder del template engine
 * ({{, {%, {block, {extends, ...) con i rispettivi codici PHP, e li salva nella cartella 'cache'.
 *
 *
 * Questa classe permette di renderizzare un template senza la necessità di fare require all'interno del codice dei controller.
 * La classe "compila" il codice del template sostituendo alla sintassi di template engine il corrispettivo codice php.
 * Il codice così compilato viene salvato in una cartella "cache" e sarà quello realmente eseguito dall'interprete php.
 *
 *
 * Alberatura del file:
 *
 * view
    cache
        includeFiles
        compileCode
            compileBlock
            compileYield
            compileEscapedEchos
            compileEchos
            compilePHP
 *
 *
 * Permette di utilizzare tutte le sintassi classiche dei template engine come:*
 * - {% extends base.html %} utile per annidare più template uno dentro l'altro
 * - {% yield content %} e {% block title %}Home Page{% endblock %} Per poter sovrascrivere blocchi di template
 * - {% foreach($colors as $color): %} o altre sintassi php per poter lavorare gli oggetti nei template
 *
 * Viene richiamata semplicemente come da esempio:
 *
 * TemplateCore::view('homepage.html', array("blogs" => $result, "n_blogs" => $n_blogs));
 *
 * specificando il template da renderizzare e l'array dei valori da mandare ai template.
 * Tali oggetti possono poi essere recuperati come:
 *
 * {{ $blogs }}
 *
 * o lavorati come per esempio:
 *
 * {% foreach($blogs as $blog): %}
 *
 * Una più dettagliata descrizione può essere trovata al seguente indirizzo
 * # https://codeshack.io/lightweight-template-engine-php/
 */
class TemplateCore {

	static $blocks = array();
	static $cache_path = 'cache/'; # path della cache
	static $cache_enabled = FALSE;

    static $base_views_path = "App/Views/"; # path dei file dove trova gli html


	static function view($file, $data = array()) {
		$cached_file = self::cache($file); # crea file di cache se non esiste o se troppo vecchio (if line 73)
	    extract($data, EXTR_SKIP); # estrae i dati dall'array di parametri passato alla funzione, senza sovrascrivere eventuali duplicazioni
	   	require $cached_file;
	}

	static function cache($file) {
		if (!file_exists(self::$cache_path)) { # crea cartella cache se non esiste
		  	mkdir(self::$cache_path, 0744);
		}

	    $cached_file = self::$cache_path . str_replace(array('/', '.html'), array('_', ''), $file . '.php'); # crea il nome del file
	    if (!self::$cache_enabled || !file_exists($cached_file) || filemtime($cached_file) < filemtime($file)) {
			$code = self::includeFiles($file); # unisce i file con extends e include
			$code = self::compileCode($code); # sostituisce il codice php
	        file_put_contents($cached_file, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code); # scrive il file di cache
	    }
		return $cached_file;
	}

	static function clearCache() {
		foreach(glob(self::$cache_path . '*') as $file) {
			unlink($file);
		}
	}

	static function compileCode($code) {
		$code = self::compileBlock($code); # sostituisce il codice dei {% block content %}
		$code = self::compileYield($code); # sostituisce il codice dei {% yield content %}
		$code = self::compileEscapedEchos($code);
		$code = self::compileEchos($code); # sostituissce le variabili
		$code = self::compilePHP($code); # sostituisce i comandi PHP (for, if)
		return $code;
	}

	static function includeFiles($file) {
		$code = file_get_contents(self::$base_views_path.$file);
		preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
		foreach ($matches as $value) {
			$code = str_replace($value[0], self::includeFiles($value[2]), $code);
		}
		$code = preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
		return $code; # ritorna il codice avendo sostituito, extends e include
	}

	static function compilePHP($code) {
		return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $code);
	}

	static function compileEchos($code) {
		return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo $1 ?>', $code);
	}

	static function compileEscapedEchos($code) {
		return preg_replace('~\{{{\s*(.+?)\s*\}}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $code);
	}

	static function compileBlock($code) {
		preg_match_all('/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $code, $matches, PREG_SET_ORDER);
		foreach ($matches as $value) {
			if (!array_key_exists($value[1], self::$blocks)) self::$blocks[$value[1]] = '';
			if (strpos($value[2], '@parent') === false) {
				self::$blocks[$value[1]] = $value[2];
			} else {
				self::$blocks[$value[1]] = str_replace('@parent', self::$blocks[$value[1]], $value[2]);
			}
			$code = str_replace($value[0], '', $code);
		}
		return $code;
	}

	static function compileYield($code) {
		foreach(self::$blocks as $block => $value) {
			$code = preg_replace('/{% ?yield ?' . $block . ' ?%}/', $value, $code);
		}
		$code = preg_replace('/{% ?yield ?(.*?) ?%}/i', '', $code);
		return $code;
	}

}