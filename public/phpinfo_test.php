<?php
echo '<pre style="font-size:14px;padding:20px;">';
echo 'PHP version: '         . PHP_VERSION . PHP_EOL;
echo 'php.ini loaded: '      . php_ini_loaded_file() . PHP_EOL;
echo 'upload_tmp_dir: '      . ini_get('upload_tmp_dir') . PHP_EOL;
echo 'tmp dir exists: '      . (is_dir(ini_get('upload_tmp_dir')) ? 'YES ✓' : 'NO ✗') . PHP_EOL;
echo 'tmp dir writable: '    . (is_writable(ini_get('upload_tmp_dir')) ? 'YES ✓' : 'NO ✗') . PHP_EOL;
echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL;
echo 'post_max_size: '       . ini_get('post_max_size') . PHP_EOL;
echo 'file_uploads: '        . ini_get('file_uploads') . PHP_EOL;
echo 'sys_get_temp_dir: '    . sys_get_temp_dir() . PHP_EOL;
echo 'sys tmp writable: '    . (is_writable(sys_get_temp_dir()) ? 'YES ✓' : 'NO ✗') . PHP_EOL;
echo '</pre>';
