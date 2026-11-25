<?php
header('Content-Type: application/json; charset=utf-8');
$base = __DIR__ . '/storage/narrations';
function failj($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $message], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}
if (!is_dir($base) && !@mkdir($base, 0775, true)) failj('Δεν δημιουργήθηκε ο φάκελος storage/narrations. Έλεγξε τα δικαιώματα του φακέλου.', 500);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = preg_replace('/[^a-f0-9]/', '', $_GET['id'] ?? '');
    $file = $base . '/' . $id . '.wav';
    if (!$id || !is_file($file)) { http_response_code(404); exit('Narration not found'); }
    header('Content-Type: audio/wav');
    header('Content-Length: ' . filesize($file));
    header('Cache-Control: no-store, no-cache, must-revalidate');
    readfile($file);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') failj('Μη έγκυρο αίτημα.');
$text = trim($_POST['text'] ?? '');
if ($text === '') failj('Γράψε πρώτα το κείμενο αφήγησης.');
$length = function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
if ($length > 80000) failj('Το κείμενο είναι υπερβολικά μεγάλο (όριο 80.000 χαρακτήρες).');
if (!function_exists('exec')) failj('Η PHP του Laragon έχει απενεργοποιημένη την exec(). Πρέπει να αφαιρεθεί το exec από το disable_functions στο ενεργό php.ini και μετά να γίνει Restart All.', 500);
if (!function_exists('iconv')) failj('Η επέκταση iconv της PHP δεν είναι ενεργή. Ενεργοποίησέ την στο php.ini και κάνε Restart All.', 500);
if (!is_writable($base)) failj('Ο φάκελος storage/narrations δεν είναι εγγράψιμος από τον Apache/Laragon.', 500);
$windir = getenv('WINDIR') ?: 'C:/Windows';
$candidates = [$windir . '/System32/cscript.exe', $windir . '/SysWOW64/cscript.exe'];
$cscript = '';
foreach ($candidates as $candidate) if (is_file($candidate)) { $cscript = $candidate; break; }
if ($cscript === '') failj('Δεν βρέθηκε το ενσωματωμένο Windows Script Host (cscript.exe).', 500);
try { $id = bin2hex(random_bytes(12)); } catch (Throwable $e) { failj('Δεν ήταν δυνατή η δημιουργία ασφαλούς ονόματος αρχείου: ' . $e->getMessage(), 500); }
$txt = $base . '/' . $id . '.txt';
$vbs = $base . '/' . $id . '.vbs';
$wav = $base . '/' . $id . '.wav';
$utf16 = iconv('UTF-8', 'UTF-16LE//IGNORE', $text);
if ($utf16 === false || @file_put_contents($txt, "\xFF\xFE" . $utf16) === false) failj('Δεν μπόρεσε να γραφτεί το προσωρινό αρχείο κειμένου στον φάκελο narrations.', 500);
// Explicit PCM format, then write the actual Windows SAPI speech stream to WAV.
$code = "On Error Resume Next\r\n" .
"Set voice = CreateObject(\"SAPI.SpVoice\")\r\n" .
"If Err.Number <> 0 Then WScript.Echo \"SAPI.SpVoice: \" & Err.Description: WScript.Quit 11\r\n" .
"requested = LCase(Trim(WScript.Arguments(2)))\r\n" .
"If requested <> \"male\" And requested <> \"female\" Then WScript.Echo \"Invalid voice selection.\": WScript.Quit 17\r\n" .
"Set tokens = voice.GetVoices()\r\n" .
"foundVoice = False\r\n" .
"For i = 0 To tokens.Count - 1\r\n" .
"  Set token = tokens.Item(i)\r\n" .
"  description = token.GetDescription\r\n" .
"  If requested = \"male\" And InStr(1, description, \"Microsoft David\", vbTextCompare) > 0 Then Set selectedToken = token: foundVoice = True: Exit For\r\n" .
"  If requested = \"female\" And InStr(1, description, \"Microsoft Zira\", vbTextCompare) > 0 Then Set selectedToken = token: foundVoice = True: Exit For\r\n" .
"Next\r\n" .
"If Not foundVoice Then WScript.Echo \"Requested Microsoft voice not found: \" & requested: WScript.Quit 17\r\n" .
"Set voice.Voice = selectedToken\r\n" .
"If Err.Number <> 0 Then WScript.Echo \"Voice selection failed: \" & Err.Description: WScript.Quit 18\r\n" .
"Err.Clear\r\n" .
"Set stream = CreateObject(\"SAPI.SpFileStream\")\r\n" .
"If Err.Number <> 0 Then WScript.Echo \"SAPI.SpFileStream: \" & Err.Description: WScript.Quit 12\r\n" .
"Set fmt = CreateObject(\"SAPI.SpAudioFormat\")\r\n" .
"If Err.Number = 0 Then fmt.Type = 22: Set stream.Format = fmt\r\n" .
"Err.Clear\r\nstream.Open WScript.Arguments(0), 3, False\r\n" .
"If Err.Number <> 0 Then WScript.Echo \"WAV open: \" & Err.Description: WScript.Quit 13\r\n" .
"Set voice.AudioOutputStream = stream\r\n" .
"If Err.Number <> 0 Then WScript.Echo \"AudioOutputStream: \" & Err.Description: WScript.Quit 14\r\n" .
"Set fso = CreateObject(\"Scripting.FileSystemObject\")\r\n" .
"Set f = fso.OpenTextFile(WScript.Arguments(1), 1, False, -1)\r\n" .
"If Err.Number <> 0 Then WScript.Echo \"Read text: \" & Err.Description: stream.Close: WScript.Quit 15\r\n" .
"speech = f.ReadAll\r\nf.Close\r\n" .
"voice.Rate = 0\r\nvoice.Volume = 100\r\nvoice.Speak speech, 0\r\n" .
"If Err.Number <> 0 Then WScript.Echo \"Speak: \" & Err.Description: stream.Close: WScript.Quit 16\r\n" .
"stream.Close\r\nWScript.Quit 0\r\n";
if (@file_put_contents($vbs, $code) === false) { @unlink($txt); failj('Δεν μπόρεσε να γραφτεί το προσωρινό αρχείο Windows Script.', 500); }
$voiceChoice = strtolower(trim($_POST['voice'] ?? 'female'));
if (!in_array($voiceChoice, ['female', 'male'], true)) $voiceChoice = 'female';
$cmd = escapeshellarg($cscript) . ' //NoLogo //T:120 ' . escapeshellarg($vbs) . ' ' . escapeshellarg($wav) . ' ' . escapeshellarg($txt) . ' ' . escapeshellarg($voiceChoice) . ' 2>&1';
$output = [];
$rc = 1;
exec($cmd, $output, $rc);
@unlink($txt);
@unlink($vbs);
$valid = is_file($wav) && filesize($wav) > 1000;
if ($valid) {
    $fh = @fopen($wav, 'rb');
    $head = $fh ? fread($fh, 12) : '';
    if ($fh) fclose($fh);
    $valid = strlen($head) >= 12 && substr($head, 0, 4) === 'RIFF' && substr($head, 8, 4) === 'WAVE';
}
if ($rc !== 0 || !$valid) {
    @unlink($wav);
    $detail = trim(implode("\n", $output));
    if ($detail === '') $detail = 'Το cscript.exe δεν επέστρεψε λεπτομέρειες. Έλεγξε ότι είναι ενεργό το Windows Script Host και ότι υπάρχει εγκατεστημένη φωνή Windows SAPI.';
    failj('Η δημιουργία WAV από Windows SAPI απέτυχε (κωδικός ' . (int)$rc . '). ' . $detail, 500);
}
echo json_encode(['ok' => true, 'id' => $id, 'url' => 'narration.php?id=' . $id, 'bytes' => filesize($wav)], JSON_UNESCAPED_UNICODE);
