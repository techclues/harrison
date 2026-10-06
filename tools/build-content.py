"""Build Harrison page content from the Red Accountants pages (same firm, rebranded).

Input : JSON from tools/extract-red.php, existing data/services.php and data/audiences.php.
Output: data/services.php and data/audiences.php with `lead`, `body`, `image` and `review`
        added from the Red copy, cleaned and updated. Usage:
        php tools/extract-red.php <pages...> > red.json && python tools/build-content.py red.json
Every edit to Red's wording is listed in EDITS below so it can be reviewed.
"""
import json
import re
import subprocess
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent

# ---- Red page -> Harrison slug ------------------------------------------------
SERVICES = {
    "business-tax-advice": "Business-Tax-Advice", "business-rates": "Business-Rates",
    "accounting-services": "Accounting-Services", "corporation-tax": "Corporation-Tax",
    "bookkeeping-services": "Bookkeeping", "company-secretarial-services": "Company-Secretarial-Services",
    "confirmation-statements": "Confirmation-Statements", "vat": "VAT", "payroll": "Payroll",
    "pension-auto-enrolment": "Pension", "personal-tax": "Personal-Tax",
    "construction-industry-scheme": "Construction-Industry-Scheme", "hmrc-correspondence": "HMRC",
}
AUDIENCES = {
    "start-ups": "Start-Ups", "small-businesses": "Small-Businesses",
    "established-businesses": "Established-Businesses", "directors": "Directors",
    "sole-traders": "Sole-Traders", "contractors": "Contractors", "freelancers": "Freelancers",
    "landlords": "Landlords", "health-workers": "Health-Workers", "employed-individuals": "Individuals",
}
# Clean photographs only. Red images with baked-in text, third-party logos (The Pensions Regulator,
# TfL) or cut-out stock are left out; those pages fall back to Harrison's own imagery.
IMAGES = {
    "bookkeeping-services": "Bookkeeping.jpg", "business-rates": "business-rates.jpg",
    "construction-industry-scheme": "CIS.jpg", "vat": "VAT.jpg", "hmrc-correspondence": "HMRCCorrespondace.jpg",
    "directors": "Directors.jpg", "established-businesses": "Established-Business.jpg",
    "freelancers": "FREELANCERS.jpg", "employed-individuals": "../service/10.jpg", "landlords": "landlords.jpg",
    "small-businesses": "small-business.png", "start-ups": "start-up.jpg",
}

# ---- Global wording ------------------------------------------------------------
GLOBAL = [
    ("Red Accountants team are", "The Harrison Accountants team is"),
    ("Red Accountant’s", "Harrison Accountants’"),
    ("Red Accountancy team", "Harrison Accountants team"),
    ("Red Accountancy knows", "We know"),
    ("Red Accountancy", "Harrison Accountants"),
    ("Red Accountants", "Harrison Accountants"),
    ("Red accountants", "Harrison Accountants"),
    ("Cube Accountants", "Harrison Accountants"),
    ("FREE initial", "free initial"),
]

# ---- Page edits: (find, replace) on text; replace None drops the block ----------
EDITS = {
    "business-tax-advice": [
        ("sole employer", "sole trader"),
        ("That’s while you’ll", "That’s why you’ll"),
    ],
    "business-rates": [
        ("Overpaying business rates has become a convention, and thousands of pounds are lost every year by businesses that have been overcharged.",
         "Overpaying business rates is common, and businesses lose thousands of pounds every year through bills that are wrong."),
        ("Councils are billing landlords and tenants incorrectly since the bills are being issued before the necessary adjustments are being made",
         "Bills are issued to landlords and tenants before the necessary adjustments have been made"),
        ("Vacant building owners are only eligible to limited support, whereas industrial building owners are eligible to total support",
         "Empty property relief periods differ by property type, and the longer relief for industrial premises is not always applied"),
        ("Failure to issue charitable relief", "Charitable relief not applied"),
    ],
    "accounting-services": [],
    "corporation-tax": [
        ("Registering you with HMRC and Companies House Registering you with HMRC and Companies House",
         "Registering you with HMRC and Companies House"),
    ],
    "bookkeeping-services": [
        ("enables you retain", "enables you to retain"),
        ("including; purchases", "including purchases"),
        (" Our service will:", ""),
        ("File your tax returns properly and ensure maximum savings", "File your tax returns properly and claim everything you are entitled to"),
    ],
    "company-secretarial-services": [
        ("We tailor our company secretarial service to meet your needs, providing services to ensure you comply with local regulations. Our principal aim",
         "Our principal aim"),
        ("Photocopies can be produced from original documents if their physical condition allows. Where documents are bound, rolled, delicate in nature or over A3 in size photocopying is not available, this includes printed material from library and study centre collections. In this instance digital copying and colour printing can be offered.",
         "Photocopies can be produced from original documents if their condition allows. Where documents are bound, delicate or larger than A3, we can offer digital copying and colour printing instead."),
        ("cannot be under estimated", "cannot be underestimated"),
        ("the Register of Companies needs", "the Registrar of Companies needs"),
        ("send the Register of Companies", "send the Registrar of Companies"),
    ],
    "confirmation-statements": [
        ("A Confirmation Statement is essentially the same as the Annual Return in that it is a snapshot",
         "A Confirmation Statement, which replaced the Annual Return in 2016, is a snapshot"),
        ("is up to date", "is up to date."),
        ("must be still be made", "must still be made"),
        ("This allows plenty of time to review draft confirmation statement, company dissolution and can improve credit score rating.",
         "This allows plenty of time to review the draft statement, so it is never filed late. Late filing can lead to the company being struck off and can affect its credit rating."),
        ("persecution of directors", "prosecution of directors"),
        ("to the Companies House", "with Companies House"),
    ],
    "vat": [
        ("We are also preparing our existing VAT clients for the introduction of MTD (Making Tax Digital).",
         "VAT-registered businesses must now keep digital records and file returns through Making Tax Digital (MTD) compatible software, and we can set this up for you."),
        ("Complete your VAT return (avoiding any penalties)", "Complete and file your VAT returns on time, helping you avoid penalties"),
    ],
    "payroll": [
        ("With the new RTI (Real time information) regulations", "Under the RTI (Real Time Information) rules"),
        ("We offer a cost effective and reliable payroll service;", "We offer a cost-effective and reliable payroll service:"),
        ("Employer Annual Return P35", None),
        ("Employee Summary P60's and P14's", "Year-end P60s for every employee"),
        ("P11D and P9D benefit and expenses returns", "P11D benefits and expenses returns"),
        ("out Payroll outsource package", "our payroll outsourcing package"),
        ("Outsourcing your payroll will pay for itself as there are no payroll staff overheads to pay out.",
         "Lower overheads, with no in-house payroll staff to fund."),
        ("OUTSOURCE PAYROLL", "Outsource your payroll"),
    ],
    "pension-auto-enrolment": [
        ("all employers will be required to ensure", "all employers are required to ensure"),
        ("you will be required to automatically enrol", "you are required to automatically enrol"),
        ("As pension’s specialists", "As pension specialists"),
        ("those of the pension’s regulator", "those of The Pensions Regulator"),
        ("our financial planner’s research", "our financial planners research"),
    ],
    "personal-tax": [
        ("It’s worth mentioning that Confirmation Statements must be still be made even if there haven’t been any changes since the last time, and even if the company is dormant.", None),
        ("Personal Tax-Self Assessment Registration", "Self Assessment registration"),
        ("Efficient personal tax planning to minimise overall tax bill.", "Efficient personal tax planning to minimise your overall tax bill"),
    ],
    "construction-industry-scheme": [
        ("We offer services to both business and individual subcontractors working within the Construction Industry:",
         "We offer services to both contractors and subcontractors working in the construction industry."),
        ("For companies that supply contractors we offer:", "For contractors (businesses that pay subcontractors) we offer:"),
    ],
    "hmrc-correspondence": [
        ("Why You Can’t Avoid HMRC Correspondence?", "Why you can’t avoid HMRC correspondence"),
        ("Our team of online business accountants at Harrison Accountants can be that representative.",
         "Our team at Harrison Accountants can be that representative."),
        ("What Our Online Business Accountants Can Offer You", "What we can offer you"),
        ("more client’s HMRC correspondence", "more clients’ HMRC correspondence"),
    ],
    "start-ups": [
        ("Bring us your business ideas and we’ll help you to evaluate them in a constructive and realistic manner. We can also help you:",
         ("p", "Bring us your business ideas and we’ll help you to evaluate them in a constructive and realistic manner. We can also help you:")),
    ],
    "small-businesses": [
        ("Small Businesses/Companies in the start-up phase have limitation of everything, and finance is one of them. They have the compulsion to minimize their expenses, tax bill and at the same time make sure that the compliance is met.",
         "Small businesses and companies in the start-up phase are limited in many ways, and finance is one of them. They need to keep expenses and the tax bill down while making sure they stay compliant."),
        ("Small Businesses/ Companies are not in a position to hire expensive lawyers and accountants for the basic accounting and company secretarial services. Harrison Accountants helps small businesses/Companies in the following way to successfully run the business",
         "Small businesses are rarely in a position to hire expensive lawyers and accountants for basic accounting and company secretarial work. Harrison Accountants helps small businesses run successfully in the following ways:"),
        ("Registered office service- you can run your business from your home until it gets sizable client base. In the mean time we can act as your registered office to receive correspondence",
         "Registered office service – you can run your business from home until it has a sizeable client base. In the meantime, we can act as your registered office and receive your correspondence"),
        ("Payroll advise and dividend planning – we suggest you the optimal tax and NI effective pay structure as well as how you can withdraw company dividends in a tax efficient manner.",
         "Payroll advice and dividend planning – we suggest the most tax- and NI-efficient pay structure, and how to draw company dividends tax-efficiently"),
        ("Other generic accountancy services like bookkeeping", "Everyday accountancy services such as bookkeeping"),
        ("Small Businesses / Companies", "Small Businesses"),
    ],
    "established-businesses": [
        ("More Proficient Tax planning", "More proficient tax planning"),
        ("Business Owners want to Exit the business", "Business owners who want to exit the business"),
        ("Management of accounts", "Management accounts"),
    ],
    "directors": [
        ("your filing of each year’s accounts and your annual return.", "filing each year’s accounts and your confirmation statement."),
        ("who holds himself out", "who holds themselves out"),
        ("Failure to do so, could result", "Failure to do so could result"),
    ],
    "sole-traders": [
        ("Small Businesses/ Companies are not in a position to hire expensive lawyers and accountants for the basic accounting and company secretarial services. Harrison Accountants helps small businesses/Companies in the following way to successfully run the business", None),
        ("Register for VAT if your turnover is expected to be more than £85,000 a year",
         "Register for VAT if your taxable turnover goes over £90,000 in any 12 months (the threshold from 1 April 2024)"),
    ],
    "contractors": [
        ("your filing of each year’s accounts and your annual return.", "filing each year’s accounts and your confirmation statement."),
        ("When two or more people work together as a single company – similar to a sole trader structure.",
         "Two or more people running a business together. Each partner is taxed on their share of the profits, much like a sole trader."),
        ("Note that clients are less likely to work with contractors who are sole traders though.",
         "Note, though, that some clients are less likely to work with contractors who are sole traders."),
        ("making you, the contractor, and an employee", "making you, the contractor, an employee"),
    ],
    "freelancers": [],
    "landlords": [],
    "health-workers": [
        ("Health workers always managing a hectic full-time work schedule and adhering to intense time pressure to often having to make life-changing decisions on a daily basis. In this situation, some of the health workers are employees and some are locum doctors",
         "Health workers manage hectic full-time schedules under intense time pressure, often making life-changing decisions every day. Some are employees, and some are locum doctors"),
        ("can be a particular tax-efficient way", "can be a particularly tax-efficient way"),
    ],
    "employed-individuals": [
        ("Your household receives Child Benefit and you have income in excess of £50,000",
         "Your household receives Child Benefit and you have income over £60,000 (the threshold from April 2024)"),
        ("or you have income in excess of £100,000.", "or your income is over £100,000, when your personal allowance starts to be reduced"),
        ("too high/ low", "too high or too low"),
        ("properties or you have", "properties, or because"),
        ("appropriate- meaning", "appropriate, meaning"),
        ("deadlines- whilst", "deadlines, whilst"),
    ],
}

# Paragraph duplicated by mistake on the Contractors page: keep it under Limited Company only.
CONTRACTOR_DUP = "It is also important for you to comply with your filing obligations to Companies House"

# Draft notes shown on each page until Harrison signs the copy off.
REVIEW = {
    "business-rates": "Confirm the claim “We have reclaimed thousands for our previous clients” is still accurate for Harrison.",
    "company-secretarial-services": "Confirm Harrison offers registered office, certification, scanning, shredding and company search services, and still has a Corporate Finance department.",
    "confirmation-statements": "Updated: the Annual Return was replaced in 2016. Since 2024 statements also confirm a registered email address and lawful purpose; mention if wanted.",
    "vat": "Updated: Making Tax Digital is now in force for all VAT-registered businesses (the old copy said it was coming).",
    "payroll": "Updated: P35, P14 and P9D forms were abolished; replaced with current P60 and P11D wording.",
    "pension-auto-enrolment": "Confirm the “financial planners” recommending schemes are FCA-authorised, or reword. The self-assessment paragraph on the old page moved to Personal Tax.",
    "personal-tax": "A stray Confirmation Statement paragraph from the old page was removed.",
    "hmrc-correspondence": "Confirm “over a decade’s worth of experience” for Harrison.",
}
REVIEW_AUDIENCE = {
    "sole-traders": "Updated: VAT registration threshold is £90,000 (was £85,000). Confirm “hundreds of self-employed clients”.",
    "employed-individuals": "Updated: High Income Child Benefit Charge threshold is £60,000 (was £50,000).",
    "contractors": "A Companies House paragraph repeated under every business type on the old page now appears once, under Limited Company.",
    "start-ups": "Confirm the free initial meeting is still offered.",
}


def convert_images():
    """Copy the chosen Red photos into assets/images/pages/<slug>.webp (max 1600px wide)."""
    from PIL import Image
    src_dir = ROOT.parent / "RedAccountants" / "images" / "services"
    out_dir = ROOT / "assets" / "images" / "pages"
    out_dir.mkdir(parents=True, exist_ok=True)
    for slug, name in IMAGES.items():
        im = Image.open((src_dir / name).resolve()).convert("RGB")
        if im.width > 1600:
            im = im.resize((1600, round(im.height * 1600 / im.width)), Image.LANCZOS)
        im.save(out_dir / f"{slug}.webp", "WEBP", quality=80, method=6)


def php_data(path):
    out = subprocess.run(["php", "-r", f"echo json_encode(require '{path}', JSON_UNESCAPED_UNICODE);"],
                         cwd=ROOT, capture_output=True, text=True, encoding="utf8", check=True)
    return json.loads(out.stdout)


def is_nav(block):
    kind, value = block
    if kind == "ul" and ("Business & Tax Advice" in value or "Start-Ups" in value):
        return True
    return kind == "h" and value in ("020 8573 2666", "Leave a Reply") or value == "Your email address will not be published. Required fields are marked *"


def transform(slug, red, title):
    blocks = [b for b in red["blocks"] if not is_nav(b)]
    # Drop a heading that just repeats the page title.
    norm = lambda s: re.sub(r"[^a-z]", "", s.lower())
    blocks = [b for b in blocks if not (b[0] == "h" and (norm(b[1]) in norm(title) or norm(title) in norm(b[1])) and len(b[1]) < 40)]
    if slug == "accounting-services":
        blocks = [b for b in blocks if b != ["h", "Accountant Services"]]
    # Red opened most pages with a heading restating the page name (e.g. "Business Start-Up").
    if blocks and blocks[0][0] == "h":
        blocks = blocks[1:]
    blocks = [b for b in blocks if b != ["h", "Administrative"]]

    def fix(text):
        for a, b in GLOBAL:
            text = text.replace(a, b)
        return text

    out = []
    for kind, value in blocks:
        value = [fix(v) for v in value] if kind == "ul" else fix(value)
        new_kind = kind
        for a, b in EDITS.get(slug, []):
            if kind == "ul":
                value = [v for v in value if not (b is None and v == a)]
                value = [v.replace(a, b) if isinstance(b, str) else v for v in value]
            elif value == a and b is None:
                value = None
                break
            elif isinstance(b, tuple) and value == a:
                new_kind, value = b
            elif isinstance(b, str):
                value = value.replace(a, b)
        if value in (None, "", []):
            continue
        if kind == "p" and value.endswith("?") and len(value) < 60:
            new_kind = "h"  # e.g. "Why Is Bookkeeping Important for Your Company?"
        out.append([new_kind, value])

    # Merge adjacent lists (Red split long lists into two columns).
    merged = []
    for b in out:
        if merged and b[0] == "ul" and merged[-1][0] == "ul":
            merged[-1][1] = merged[-1][1] + b[1]
        else:
            merged.append(b)
    # Remove exact duplicate paragraphs.
    seen, result = set(), []
    for b in merged:
        key = json.dumps(b)
        if b[0] == "p" and key in seen:
            continue
        seen.add(key)
        result.append(b)
    if slug == "contractors":
        first = next(i for i, b in enumerate(result) if b[0] == "p" and b[1].startswith(CONTRACTOR_DUP))
        result = [b for i, b in enumerate(result) if not (i > first and b[0] == "p" and b[1].startswith(CONTRACTOR_DUP))]
    if slug == "start-ups":
        i = next(i for i, b in enumerate(result) if b[0] == "p" and b[1].startswith("The earlier clients"))
        result.insert(i - 1, result.pop(i))
    if slug == "bookkeeping-services":
        result = [b for b in result if b != ["h", "Our service will:"]]
        for b in result:
            if b[0] == "p" and b[1].startswith("Our Bookkeeping service"):
                b[1] = b[1].replace("Our Bookkeeping service", "Our bookkeeping service") + " Our service will:"
    return result


def take_lead(blocks, fallback):
    """First paragraph becomes the hero lead. A lead ending in ':' introduces a list, so its
    last sentence stays in the body next to that list."""
    if not blocks or blocks[0][0] != "p":
        return fallback
    if blocks[0][1].endswith(":") and not re.search(r"[.!?]\s", blocks[0][1]):
        return fallback  # a single introductory sentence stays with its list
    lead = blocks.pop(0)[1]
    if lead.endswith(":"):
        parts = re.split(r"(?<=[.!?])\s+", lead)
        if len(parts) > 1:
            blocks.insert(0, ["p", parts[-1]])
            lead = " ".join(parts[:-1])
    return lead


def split_pension(blocks):
    """The Pension page carried a Self Assessment section by mistake; return (pension, moved)."""
    i = next((i for i, b in enumerate(blocks) if b == ["h", "Easing the burden"]), None)
    return (blocks, []) if i is None else (blocks[:i], blocks[i:])


def php_value(v, indent=8):
    pad = " " * indent
    if isinstance(v, str):
        return "'" + v.replace("\\", "\\\\").replace("'", "\\'") + "'"
    if isinstance(v, bool):
        return "true" if v else "false"
    if v is None:
        return "null"
    if isinstance(v, list):
        if all(isinstance(x, str) for x in v) and sum(len(x) for x in v) < 90:
            return "[" + ", ".join(php_value(x) for x in v) + "]"
        return "[\n" + "".join(f"{pad}    {php_value(x, indent + 4)},\n" for x in v) + pad + "]"
    if isinstance(v, dict):
        return "[\n" + "".join(f"{pad}    '{k}' => {php_value(x, indent + 4)},\n" for k, x in v.items()) + pad + "]"
    raise TypeError(type(v))


def write_php(path, header, records):
    body = "".join(f"    '{slug}' => {php_value(rec, 4)},\n" for slug, rec in records.items())
    (ROOT / path).write_text("<?php\ndeclare(strict_types=1);\n\n" + header + "\nreturn [\n" + body + "];\n", encoding="utf8")


def main(red_json):
    red = json.loads(Path(red_json).read_text(encoding="utf8"))
    convert_images()
    services, audiences = php_data("data/services.php"), php_data("data/audiences.php")
    moved = []
    for slug, page in SERVICES.items():
        rec = services[slug]
        blocks = transform(slug, red[page], rec["title"])
        if slug == "pension-auto-enrolment":
            blocks, moved = split_pension(blocks)
        if slug == "personal-tax":
            blocks = blocks + [list(b) for b in moved] if moved else blocks
        lead = take_lead(blocks, rec.get("lead", ""))
        services[slug] = {k: rec[k] for k in ("title", "group", "summary", "description")}
        services[slug].update({"lead": lead, "body": blocks, "image": f"{slug}.webp" if slug in IMAGES else "",
                               "related": rec["related"], "review": REVIEW.get(slug, "")})
    # Personal Tax runs after Pension in SERVICES order; re-apply the moved section if needed.
    if moved and ["h", "Easing the burden"] not in services["personal-tax"]["body"]:
        services["personal-tax"]["body"] += moved
    for slug, page in AUDIENCES.items():
        rec = audiences[slug]
        blocks = transform(slug, red[page], rec["title"])
        lead = take_lead(blocks, rec["text"])
        audiences[slug] = {k: rec[k] for k in ("title", "summary", "text", "services")}
        audiences[slug].update({"description": f"How Harrison Accountants supports {rec['title'].lower()}: {rec['summary'][0].lower()}{rec['summary'][1:]}",
                                "lead": lead, "body": blocks, "image": f"{slug}.webp" if slug in IMAGES else "",
                                "review": REVIEW_AUDIENCE.get(slug, "")})
    write_php("data/services.php",
              "/*\n * Canonical service records, in menu order. Copy carried over from the firm’s previous website\n"
              " * (same firm, rebranded) by tools/build-content.py, with outdated facts corrected.\n"
              " * body blocks: ['p', text] | ['h', heading] | ['ul', [items]]. image: file in assets/images/pages/.\n */",
              services)
    write_php("data/audiences.php",
              "/*\n * Who We Help records, in menu order. Each has its own page under who-we-help/.\n"
              " * Copy carried over from the firm’s previous website by tools/build-content.py.\n"
              " * `services` values must be slugs from data/services.php.\n */",
              audiences)
    print("services", len(services), "audiences", len(audiences))


if __name__ == "__main__":
    main(sys.argv[1])
