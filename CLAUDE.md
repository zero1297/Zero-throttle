# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Static single-page marketing site for Cortes Cleanouts (junk removal, Knoxville, TN), implemented from a Claude Design project (`claude.ai/design` project ee776247-41e0-4cc3-a6b2-02f0a7cdcbd9, source file `Cortes Cleanouts.dc.html`). The entire site lives in `index.html` — inline styles matching the design file, with a small `<style>` block for hover/focus states and a short script for the quote-form success state and the before/after drag-to-compare sliders. There is no build step, framework, or test suite.

## Commands

- `npm install` then `npm run dev` — serve the site at http://localhost:3000 (uses `serve`)
- Or just open `index.html` directly in a browser

## Notes

- The before/after section uses real photos (`images/bedroom-before.jpg`, `images/bedroom-after.jpg`, `images/yard-before.png`, `images/yard-after.png`, pulled from the design project) in a draggable compare slider (`.compare` elements, wired up in the trailing `<script>`).
- The nav logo (`images/logo-mark.png`) and hero logo lockup (`images/logo-lockup.png`) come from the design project's asset uploads. Image placeholder for the crew photo is still a dashed-border div with class `image-slot` — replace with `<img>` when a real photo exists.
- The quote form POSTs to `quote-handler.php`, which emails the submission to CortesCleanouts@outlook.com using bundled PHPMailer 6.12 (`phpmailer/`) over authenticated SMTP (port 465, `noreply@cortescleanouts.com` mailbox), keeping a BCC copy in that same on-domain mailbox as a fallback. Do not switch back to PHP `mail()`: SiteGround's outbound spam gateway rejects the form's unauthenticated sends with `550 High probability of spam` (bounces land in the noreply@ mailbox, found 2026-07-13). SMTP credentials live in `quote-config.php` on the server — gitignored; copy `quote-config.sample.php` and fill in the mailbox password from Site Tools → Email → Accounts. Requires PHP hosting (SiteGround) — won't work when opening `index.html` directly from the filesystem or via `npm run dev` (no PHP there). SMS notifications were considered but skipped (would require a paid Twilio account).
- A second design variant exists in the design project (`Cortes Cleanouts Charcoal.dc.html`) but is not implemented.
