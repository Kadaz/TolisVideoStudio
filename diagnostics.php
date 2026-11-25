<?php
header('Content-Type: application/json; charset=utf-8');
$disabled = array_filter(array_map('trim', explode(',', (string)ini_get('disable_functions'))));
$windir = getenv('WINDIR') ?: 'C:/Windows';
$cscript = '';
foreach ([$windir.'/System32/cscript.exe', $windir.'/SysWOW64/cscript.exe'] as $candidate) {
    if (is_file($candidate)) { $cscript = $candidate; break; }
}
$base = __DIR__.'/storage';
foreach (['narrations','projects','uploads','tmp','exports'] as $dir) {
    if (!is_dir($base.'/'.$dir)) @mkdir($base.'/'.$dir, 0775, true);
}
echo json_encode([
  'php' => PHP_VERSION,
  'php_ini' => php_ini_loaded_file() ?: '(no php.ini loaded)',
  'os' => PHP_OS_FAMILY,
  'exec' => function_exists('exec') && !in_array('exec', $disabled, true),
  'disable_functions' => implode(', ', $disabled),
  'iconv' => function_exists('iconv'),
  'cscript' => $cscript,
  'narration_writable' => is_dir($base.'/narrations') && is_writable($base.'/narrations'),
  'projects_writable' => is_dir($base.'/projects') && is_writable($base.'/projects'),
  'uploads_writable' => is_dir($base.'/uploads') && is_writable($base.'/uploads'),
  'tmp_writable' => is_dir($base.'/tmp') && is_writable($base.'/tmp')
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
