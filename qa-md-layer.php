<?php
/*
	Question2Answer Markdown editor plugin
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_html_theme_layer extends qa_html_theme_base
{
	private $impuplopt = 'md_uploadimage';
	private $hljsopt = 'md_highlightjs';

	public function head_custom()
	{
		parent::head_custom();

		$tmpl = array('ask', 'question');
		if (!in_array($this->template, $tmpl))
			return;

		$imageUploadEnabled = qa_opt($this->impuplopt) === '1';
		$usehljs = qa_opt($this->hljsopt) === '1';

		$this->output_raw(
			"<style>\n"
		);

		// display CSS for Markdown Editor
		if (!$imageUploadEnabled) {
			$cssMD = file_get_contents(QA_HTML_THEME_LAYER_DIRECTORY.'pagedown/markdown.css');
			$this->output_raw($cssMD);
		}

		$this->output_raw("</style>\n\n");

		// set up HighlightJS
		if ($usehljs) {
			$this->output_raw('<script>hljs.initHighlightingOnLoad();</script>');
		}
	}
}
