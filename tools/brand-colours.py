"""Snap every colour in the site CSS to the Harrison brand palette.

Allowed values (see DESIGN-SYSTEM.md, Colour):
  - the six brand colours and white
  - tints of a brand colour: mix(white, colour, t) for t in 5% steps
  - any of the above with transparency (rgba); translucent values use the base colour only
Neutral source colours (low chroma) map only to white / grey / ink and their tints, so greys
never turn gold or navy. Run:  python tools/brand-colours.py [--check]
"""
import math
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
FILES = ["assets/css/styles.css", "assets/css/skeleton.css", "assets/css/pages.css", "assets/css/brand.css"]

BRAND = {
    "navy": (0x22, 0x2D, 0x65),
    "gold": (0xA0, 0x83, 0x59),
    "gold-light": (0xD7, 0xBB, 0x72),
    "grey": (0xE5, 0xE5, 0xE5),
    "ink": (0x24, 0x2E, 0x3D),
    "indigo": (0x07, 0x00, 0x49),
}
WHITE = (255, 255, 255)
NEUTRAL = {"grey", "ink"}
STEPS = [i / 20 for i in range(1, 20)]  # 5% .. 95%


def tint(rgb, t):
    return tuple(round(255 + (c - 255) * t) for c in rgb)


def allowed():
    out = {WHITE: ("white", 1.0)}
    for name, rgb in BRAND.items():
        out[rgb] = (name, 1.0)
        for t in STEPS:
            out.setdefault(tint(rgb, t), (name, t))
    return out


ALLOWED = allowed()


def to_lab(rgb):
    def lin(c):
        c /= 255
        return c / 12.92 if c <= 0.04045 else ((c + 0.055) / 1.055) ** 2.4
    r, g, b = (lin(c) for c in rgb)
    x = (0.4124 * r + 0.3576 * g + 0.1805 * b) / 0.95047
    y = 0.2126 * r + 0.7152 * g + 0.0722 * b
    z = (0.0193 * r + 0.1192 * g + 0.9505 * b) / 1.08883
    f = lambda v: v ** (1 / 3) if v > 0.008856 else 7.787 * v + 16 / 116
    fx, fy, fz = f(x), f(y), f(z)
    return 116 * fy - 16, 500 * (fx - fy), 200 * (fy - fz)


def chroma(rgb):
    _, a, b = to_lab(rgb)
    return math.hypot(a, b)


def nearest(rgb, translucent):
    neutral = chroma(rgb) < 10
    src = to_lab(rgb)
    best, best_d = None, 1e9
    for cand, (name, t) in ALLOWED.items():
        if translucent and t != 1.0:
            continue
        family_neutral = name in NEUTRAL or name == "white"
        if neutral != family_neutral:
            continue
        d = math.dist(src, to_lab(cand))
        if d < best_d:
            best, best_d = cand, d
    return best


HEX = re.compile(r"#([0-9a-fA-F]{8}|[0-9a-fA-F]{6}|[0-9a-fA-F]{3})\b")
RGB = re.compile(r"rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*([\d.]+%?)\s*)?\)")


def fmt(rgb, alpha=None):
    if alpha is None:
        return "#%02x%02x%02x" % rgb
    return "rgba(%d,%d,%d,%s)" % (*rgb, alpha)


def convert(text):
    def hex_sub(m):
        h = m.group(1)
        if len(h) == 3:
            h = "".join(c * 2 for c in h)
        rgb = tuple(int(h[i:i + 2], 16) for i in (0, 2, 4))
        if len(h) == 8:
            a = round(int(h[6:8], 16) / 255, 3)
            return fmt(nearest(rgb, True), a)
        return fmt(rgb if rgb in ALLOWED else nearest(rgb, False))

    def rgb_sub(m):
        rgb = tuple(int(m.group(i)) for i in (1, 2, 3))
        a = m.group(4)
        if a is None or a in ("1", "1.0", "100%"):
            return fmt(rgb if rgb in ALLOWED else nearest(rgb, False))
        base = rgb if (rgb in BRAND.values() or rgb == WHITE) else nearest(rgb, True)
        return fmt(base, a)

    text = HEX.sub(hex_sub, text)
    return RGB.sub(rgb_sub, text)


def violations(text):
    bad = []
    for m in HEX.finditer(text):
        h = m.group(1)
        h = "".join(c * 2 for c in h) if len(h) == 3 else h
        if tuple(int(h[i:i + 2], 16) for i in (0, 2, 4)) not in ALLOWED:
            bad.append(m.group(0))
    for m in RGB.finditer(text):
        if tuple(int(m.group(i)) for i in (1, 2, 3)) not in ALLOWED:
            bad.append(m.group(0))
    return bad


if __name__ == "__main__":
    check = "--check" in sys.argv
    total = 0
    for rel in FILES:
        p = ROOT / rel
        src = p.read_text(encoding="utf8")
        if check:
            bad = violations(src)
            total += len(bad)
            print(rel, "off-palette:", len(bad), sorted(set(bad))[:8])
        else:
            out = convert(src)
            p.write_text(out, encoding="utf8")
            print(rel, "remaining off-palette:", len(violations(out)))
    sys.exit(1 if check and total else 0)
