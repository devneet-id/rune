# @federation — The Federation of Fides

Hello, and welcome to the federation of Rune.

This folder opened because your runite calls `Rune\Ethereal::federation();`.
Every reliquary stands on its own, yet they are connected: forks of forks,
each keeping the relics of its owner. *Fides* is trust — the seals (sha256) keep it between them.

## ✦ What lives here
- `<vendor>/<repo>@<tag>.rune` — relics taken with `php cast reliquary --retrieve=<vendor>/<repo>`.
- `federation.json` — your reliquary (`main`) and every relic found with `php cast reliquary --discover`.

## ✦ First steps
1. `php cast reliquary --initiate --repo=<you>/reliquary` — use your own reliquary (a fork of `devneet-id/reliquary`).
2. `php cast reliquary --discover` — map the relics of every reliquary on the chain.
3. `php cast reliquary --relic-runite=<vendor>/<repo>` — open a relic into your runite.

Without federation, Rune only reads `devneet-id/reliquary`.

*This file is only a greeting — Rune does not read it.*
