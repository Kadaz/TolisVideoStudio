# Tolis Video Studio

<img width="1920" height="858" alt="image" src="https://github.com/user-attachments/assets/a2a13926-ab28-4ebc-9e1e-bc5ce9fcbf28" />

A lightweight, locally hosted video editing studio designed for creating YouTube Shorts, TikTok videos, Instagram Reels, and other video content.

Tolis Video Studio combines video editing, image overlays, text, subtitles, audio, and Windows text-to-speech narration in a single interface. It runs locally on your PC using Laragon and your web browser, without requiring cloud-based editing services.

## Features

- **Video & Image Import** — Import videos, images, music, and sound effects from your computer.
- **Timeline Editing** — Arrange and edit clips using separate video, overlay, audio, and subtitle tracks.
- **Text & Titles** — Add customizable text overlays with adjustable font size, color, and placement.
- **Quick Title Presets** — Create title cards for facts and quiz questions.
- **Audio Editing** — Add background music and sound effects, adjust volume, and configure audio fade-in and fade-out.
- **Text-to-Speech Narration** — Generate voice narration using Windows SAPI voices.
  - Female voice: Microsoft Zira Desktop (English - United States).
  - Male voice: Microsoft David Desktop (English - United States).
- **Timed Subtitles** — Add subtitles with custom start and end times.
- **Shorts Script Builder** — Create structured video scripts featuring five facts and a final quiz question.
- **Video Preview** — Preview your project directly inside the editor.
- **Project Save & Load** — Save your editing projects locally as JSON files and reopen them later.
- **MP4 Export** — Export finished videos in MP4 format (H.264).
- **Multiple Aspect Ratios** — Support for vertical, landscape, square, and portrait formats.
- **Resolution Settings** — Choose between 720p and 1080p.
- **Frame Rate Settings** — Export at 30 or 60 FPS.
- **Local Processing** — Your project files and media remain on your computer.

## Supported Formats

### Media Import
- Video
- Images
- Audio files

### Video Export
- MP4 (H.264)

### Project Files
- JSON

## Video Formats

Available aspect ratios:

| Format | Aspect Ratio | Intended Use |
|---|---|---|
| Vertical | 9:16 | YouTube Shorts, TikTok, Instagram Reels |
| Landscape | 16:9 | YouTube videos |
| Square | 1:1 | Square social media content |
| Portrait | 4:5 | Portrait social media posts |

## Requirements

- Windows
- [Laragon](https://laragon.org/)
- A modern web browser
- Windows SAPI voices for narration

## Installation

1. Install Laragon on your Windows computer.
2. Place the Tolis Video Studio project folder inside Laragon's `www` directory.
3. Start Laragon's web server.
4. Open the project in your browser using your local server address.

Example:

`http://localhost/TolisVideoStudio/`

## How It Works

Tolis Video Studio uses a browser-based editing interface with a locally hosted backend for Windows text-to-speech narration.

Videos, images, text overlays, audio clips, and subtitles can be arranged and edited independently on the timeline.

The project can be saved locally and exported as a finished MP4 video.

## Project Goals

The goal of Tolis Video Studio is to provide a simple and accessible video editing environment for content creators, particularly those producing short-form gaming content, facts, quizzes, commentary, and narrated videos.

The project prioritizes:

- Simplicity
- Local operation
- No mandatory cloud accounts
- No subscription-based editing services
- Easy-to-use timeline editing
- Fast creation of short-form videos

## Developer

Created by **Tolis (Tolis_TMG)**.

---

*An independent personal project developed for local video creation and editing.*
