TOLIS VIDEO STUDIO — LOCAL / OFFLINE LARAGON PROJECT
===================================================

WHAT THIS BUILD DOES
- Imports local images, video files and audio files into the local Laragon project.
- Provides a sequential video timeline with split, delete, reorder and trim controls.
- Provides text overlays and timed subtitles that are burned into the exported video.
- Creates a real WAV narration file using the Windows Speech API already included with Windows.
- Shows the WAV in an audio player so you can listen before exporting.
- Exports a portrait 1080 x 1920 or 720 x 1280 video, landscape 1920 x 1080 or 1280 x 720, square or 4:5.
- Includes narration, imported audio and the original audio track of imported videos when the browser can decode it.
- Saves a project JSON on the local Laragon server and downloads a backup JSON that can be opened later.
- Does not use a screen-sharing prompt, cloud service, external CDN, or FFmpeg.exe.

INSTALL
1. Extract the folder named TolisVideoStudio into C:\laragon\www\
   The resulting path must be C:\laragon\www\TolisVideoStudio\index.php
2. Open Laragon and click Start All.
3. Open this address in Chrome or Edge:
   http://localhost/TolisVideoStudio/
4. Import your image/video/audio files from the Media tab.
5. Click Add beside each file to put it on the timeline.
6. Write the narration in Audio > Narration and click Create narration.
7. Wait for the WAV audio player to appear and press Play to preview it.
8. Add subtitles in the Captions tab or create text/caption cards in Shorts.
9. Click Export MP4. The browser creates the video locally and downloads it.

OFFLINE
After the project folder is on the PC, the editor works locally at localhost and does not need an internet connection. Do not open index.php directly as a file; use the localhost address through Laragon.

REQUIREMENTS / HONEST LIMITS
- Windows 10/11, Laragon with PHP 8 or newer, and Chrome or Edge.
- PHP must be allowed to execute the built-in Windows cscript.exe for WAV narration. No separate EXE is included or required. If narration reports that exec is disabled, enable exec in the active Laragon php.ini and restart Laragon.
- MP4 export requires the installed browser to report support for MediaRecorder H.264 + AAC. The editor checks this before export and stops with an error if unavailable; it will not rename a WebM file to .mp4.
- Browser MP4 support can vary by Windows codec availability and browser build. The MP4 export path must therefore be checked on the actual PC before relying on it for a finished upload.
- PHP upload limits may be lower than the editor's 2 GB application limit. If a large file fails, raise upload_max_filesize and post_max_size in Laragon's active php.ini, then restart Laragon.

PROJECT FILES / STORAGE
- Uploaded media: storage/uploads/
- Saved project JSON files: storage/projects/
- Narration WAV files: storage/narrations/
- Temporary files: storage/tmp/
- Keep these folders in place. A saved project references the media and narration stored there on this PC.
- Use Save to download a .tolisproject.json backup. Use Open to load that backup.

This is a local editing project, not a full commercial NLE. Save a backup before major edits.
