# exploration/

**The beyond:** advanced development of Rune's modes. Each mode unlocks a folder next to the runite, so they are explored here, apart from the scenarios. See [Modes](https://github.com/devneet-id/lore/blob/main/realms/rune/reference/modes.md).

| Runite | Mode | Caption | Unlocks |
|---|---|---|---|
| `awaken` | none | *The Awakening of Void* | the blank runite from Awakening: the baseline |
| `cast` | none | *The Awakening of Void* | free trials of single features (e.g. `Whisper::flash`) |
| `evolute` | `Rune\Ethereal::evolution()` | *The Evolution of Eidos* | runes of your own in `@evolution/`; the `--runic-*` and `--phantasm-*` spells of Sentinel |
| `federate` | `Rune\Ethereal::federation()` | *The Federation of Fides* | your own reliquary in `@federation/`; `reliquary --initiate`, `--discover`, `--retrieve` |
| `liberate` | `Rune\Ethereal::liberation()` | *The Liberation of Forte* | your own PHP runtime in `@liberation/`; `sentinel --runtime-*` |

## Run
```bash
cd exploration
php evolute grimoire --runic=Weaver              # read a rune's contract
php evolute sentinel --phantasm-repair=Weaver    # node + link + note (+ version, only if changed)
php evolute sentinel --phantasm-upgrade=Weaver   # only raise the version
php federate reliquary --discover
php liberate sentinel --runtime-list
```

`evolute` is also the bench for raising Phantasm versions after a change to `rune` (step 3 of the cycle in the [main README](../README.md)).

The mode folders (`@evolution/`, `@federation/`, `@liberation/`) and `relics/` are created here at run time and stay out of git.
