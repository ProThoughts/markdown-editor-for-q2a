<?php
/*
	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

	More about this license: http://www.gnu.org/licenses/gpl.html
*/

if (!defined('QA_VERSION')) exit;

qa_register_plugin_module('editor', 'qa-md-editor.php', 'qa_md_editor', 'Markdown Editor For Q2A');
qa_register_plugin_module('viewer', 'qa-md-viewer.php', 'qa_md_viewer', 'Markdown Viewer For Q2A');
qa_register_plugin_module('event', 'qa-md-events.php', 'qa_md_events', 'Markdown Events For Q2A');
qa_register_plugin_module('page', 'qa-md-upload.php', 'qa_md_upload', 'Markdown Upload For Q2A');
qa_register_plugin_layer('qa-md-layer.php', 'Markdown Layer For Q2A');
qa_register_plugin_phrases('qa-md-lang-*.php', 'Markdown Lang For Q2A');