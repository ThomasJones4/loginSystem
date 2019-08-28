<?php
include_once('header.php');

class Template {
	
	
	/*
	*	Gets template file and replaces values with passed in variables
	*
	*	@param string $tempalteName
	*	@param Array $variables
	*	@param int $debug
	*
	*
	*/
	
	static function toScreen($pageName, $args = [], $js = null, $html = null, $debug = 0) {
		
		//recursivly call function below untill 
		
		
		$rendered = Template::itteration($pageName, $args);
		
		$rendered = (isset($js)) ? $rendered . '<script>' . $js . '</script>' . ((isset($html)) ? $html : "") : $rendered;
		
		echo $rendered;
		// // fill template to be called when all sub arrays have been dealt with
		
		print_r(($debug == 1)? $args:"");
		print_r(($debug == 2)? "<!--<!--<!--".$rendered."-->-->-->":"");
	}
	
	public function addJSToPage($js) {
		echo '<script>' . $js . '</script>';
	}
	
	function itteration($templateName, &$templateParameters) {
		foreach ($templateParameters as $parameterName => &$parameterValue) {
			if (is_array($parameterValue)) {
				if (is_numeric($parameterName)) {
					if (!array_key_exists($templateName, $templateParameters)) {
						$templateParameters[$templateName] = '';
					}
					unset($templateParameters[$parameterName]);
					$templateParameters[$templateName] .= Template::itteration($templateName, $parameterValue);
				} else {
					$templateParameters[$parameterName] = Template::itteration($parameterName, $parameterValue);
				}
			}
		}
		
		reset($templateParameters);
		if (sizeof($templateParameters) == 1 && $templateName == key($templateParameters)) {
			return $templateParameters[$templateName];
		} else {
			return Template::fillTemplate($templateName, $templateParameters);
		}
	}
	
	
	static function fillTemplate($pageName, $args = []) {
		$templateURL = __DIR__."/templates/".$pageName.".html";
		$template = file_get_contents($templateURL);
		if (!empty($args)) {
			$renderedTemplate = str_replace(array_map(function($value) { return '{{'.$value.'}}'; }, array_keys($args)), array_values($args), $template);
		} else {
			$renderedTemplate = $template;
		}
		preg_match_all("/{{plural::(.*)}}/", $renderedTemplate, $pluralVars);

		foreach ($pluralVars[1] as $var) {
			if ($args[$var] == 1) {
				$renderedTemplate = str_replace('{{plural::'.$var.'}}', '', $renderedTemplate);
			} else {
				$renderedTemplate = str_replace('{{plural::'.$var.'}}', 's', $renderedTemplate);
			}
		}		
		return $renderedTemplate;
		
	}
}
?>