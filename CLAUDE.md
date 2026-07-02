# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Static single-page marketing site for Cortes Cleanouts (junk removal, Knoxville, TN), implemented from a Claude Design project (`claude.ai/design` project ee776247-41e0-4cc3-a6b2-02f0a7cdcbd9, source file `Cortes Cleanouts.dc.html`). The entire site lives in `index.html` — inline styles matching the design file, with a small `<style>` block for hover/focus states and a short script for the quote-form success state. There is no build step, framework, or test suite.

## Commands

- `npm install` then `npm run dev` — serve the site at http://localhost:3000 (uses `serve`)
- Or just open `index.html` directly in a browser

## Notes

- Image placeholders (hero truck, before/after, crew photo) are dashed-border divs with class `image-slot` — replace with `<img>` when real photos exist.
- The quote form is front-end only; submissions go nowhere until wired to a backend/form service.
- A second design variant exists in the design project (`Cortes Cleanouts Charcoal.dc.html`) but is not implemented.
