<?php

/*
	Question2Answer Markdown editor plugin
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_md_editor
{
	private $pluginurl;
	private $cssopt = 'md_editor_css';
	private $convopt = 'md_comment';
	private $hljsopt = 'md_highlightjs';
	private $impuplopt = 'md_uploadimage';

	public function load_module($directory, $urltoroot)
	{
		$this->pluginurl = $urltoroot;
	}

	public function calc_quality($content, $format)
	{
		return $format == 'markdown' ? 1.0 : 0.8;
	}

	public function option_default($opt)
	{
		$defaults = [
			$this->cssopt => 0,
			$this->convopt => 1,
			$this->hljsopt => 0,
			$this->impuplopt => 0,
		];

		if (isset($defaults[$opt])) {
			return $defaults[$opt];
		}
	}

	public function get_field(&$qa_content, $content, $format, $fieldname, $rows, $autofocus)
	{

		$scriptsrc = $this->pluginurl.'pagedown/markdown.js?'.QA_VERSION;
		$alreadyadded = false;

		if (isset($qa_content['script_src'])) {
			foreach ($qa_content['script_src'] as $testscriptsrc) {
				if ($testscriptsrc == $scriptsrc)
					$alreadyadded = true;
			}
		}

		if (!$alreadyadded) {
			$imageUploadUrl = qa_js(qa_path('qa-md-upload'));
			$uploadimg = (int)qa_opt($this->impuplopt);

			$qa_content['script_src'][] = $scriptsrc;
			$qa_content['script_lines'][] = array(
				'var image_upload_path =' . $imageUploadUrl . ';',
				'var image_upload_enabled = ' . $uploadimg . ';',
				'var pluginurl = ' . qa_js($this->pluginurl) . ';',
			);
		}

		$html = ' <div id="md-section-' . $fieldname . '"></div>
				  <textarea name="' . $fieldname . '" id="md-textarea-' . $fieldname . '" style="display:none">'
			      . $content .
			      '</textarea>';

		return array('type' => 'custom', 'html' => $html);
	}

	public function read_post($fieldname)
	{
		$html = $this->_my_qa_post_text($fieldname);

		return array(
			'format' => 'markdown',
			'content' => $html
		);
	}

	public function load_script($fieldname)
	{
		return 'var markdownEditor = new tui.Editor({
					el:$("#md-section-' . $fieldname . '")[0],
					initialEditType: "markdown",
					previewStyle: "vertical",
					height: "500px",
					initialValue:$("#md-textarea-' . $fieldname . '").val(),
					events: {
						change: changeEditor
					},
					hooks:{
						addImageBlobHook:function (blob, callback) {
                            var requestInProgress;
                            var formData = new FormData();
							formData.append("file", blob);
							if (!requestInProgress) {
								requestInProgress = true;
								$.ajax({
									url: image_upload_path,
									type: "POST",
									data: formData,
									contentType: false,
									cache: false,
									processData: false,
									success: function(data) {
										requestInProgress = false;
										if (!data.error){
											callback(data.url);                                   
										}else{
											alert(data.error);
										}
									},
									error: function(data) {
										requestInProgress = false;
									}
								});
							}
                            return false;
                        }
					}
				});
				function changeEditor(){
					$("#md-textarea-' . $fieldname . '").val( markdownEditor.getValue());
				}';
	}


	// set admin options
	public function admin_form(&$qa_content)
	{
		$saved_msg = null;

		if (qa_clicked('markdown_save')) {
			// save options
			$hidecss = qa_post_text('md_hidecss') ? '1' : '0';
			qa_opt($this->cssopt, $hidecss);
			$convert = qa_post_text('md_comments') ? '1' : '0';
			qa_opt($this->convopt, $convert);
			$convert = qa_post_text('md_highlightjs') ? '1' : '0';
			qa_opt($this->hljsopt, $convert);
			$convert = qa_post_text('md_uploadimage') ? '1' : '0';
			qa_opt($this->impuplopt, $convert);

			$saved_msg = qa_lang_html('admin/options_saved');
		}


		return array(
			'ok' => $saved_msg,
			'style' => 'wide',

			'fields' => array(
				'css' => array(
					'type' => 'checkbox',
					'label' => qa_lang_html('markdown/admin_hidecss'),
					'tags' => 'NAME="md_hidecss"',
					'value' => qa_opt($this->cssopt) === '1',
					'note' => qa_lang_html('markdown/admin_hidecss_note'),
				),
				'comments' => array(
					'type' => 'checkbox',
					'label' => qa_lang_html('markdown/admin_comments'),
					'tags' => 'NAME="md_comments"',
					'value' => qa_opt($this->convopt) === '1',
					'note' => qa_lang_html('markdown/admin_comments_note'),
				),
				'highlightjs' => array(
					'type' => 'checkbox',
					'label' => qa_lang_html('markdown/admin_syntax'),
					'tags' => 'NAME="md_highlightjs"',
					'value' => qa_opt($this->hljsopt) === '1',
					'note' => qa_lang_html('markdown/admin_syntax_note'),
				),
				'uploadimage' => array(
					'type' => 'checkbox',
					'label' => qa_lang_html('markdown/admin_image'),
					'tags' => 'NAME="md_uploadimage"',
					'value' => qa_opt($this->impuplopt) === '1',
					'note' => qa_lang_html('markdown/admin_image_note'),
				)
			),

			'buttons' => array(
				'save' => array(
					'tags' => 'NAME="markdown_save"',
					'label' => qa_lang_html('admin/save_options_button'),
					'value' => '1',
				),
			),
		);
	}


	// copy of qa-base.php > qa_post_text, with trim() function removed.
	private function _my_qa_post_text($field)
	{
		return isset($_POST[$field]) ? preg_replace('/\r\n?/', "\n", qa_gpc_to_string($_POST[$field])) : null;
	}
}
