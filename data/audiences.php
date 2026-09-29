<?php
declare(strict_types=1);

/*
 * Who We Help audience records, in display order. Draft copy for Harrison approval.
 * "services" values must be slugs from data/services.php.
 */
return [
    'start-ups' => [
        'title' => 'Start-ups',
        'summary' => 'Getting a new business set up properly from day one.',
        'text' => 'The earlier you talk to an accountant, the simpler the early decisions tend to be. We can talk through structure, registrations, bookkeeping and the tax questions that come with a new business.',
        'services' => ['business-tax-advice', 'company-secretarial-services', 'bookkeeping-services'],
    ],
    'small-businesses' => [
        'title' => 'Small Businesses',
        'summary' => 'Practical support without the overhead of a big firm.',
        'text' => 'Small businesses have to keep costs sensible and compliance in order at the same time. We help with the routine work, such as accounts, VAT and payroll, so it does not take over your week.',
        'services' => ['accounting-services', 'vat', 'payroll'],
    ],
    'established-businesses' => [
        'title' => 'Established Businesses',
        'summary' => 'A dependable second view as the business grows and changes.',
        'text' => 'As a business matures, decisions get larger and the reporting more detailed. We provide steady accounting support and a place to talk through growth, restructuring and planning.',
        'services' => ['accounting-services', 'business-tax-advice', 'corporation-tax'],
    ],
    'directors' => [
        'title' => 'Directors',
        'summary' => 'Support with your company and your personal position.',
        'text' => 'Directors carry responsibilities for the company and have their own personal tax to consider. We help you keep company filings in order and understand how the two fit together.',
        'services' => ['company-secretarial-services', 'confirmation-statements', 'personal-tax'],
    ],
    'sole-traders' => [
        'title' => 'Sole Traders',
        'summary' => 'Keeping your records and tax return in order.',
        'text' => 'When you trade as an individual, the business and your tax return are closely linked. We help with records, your Self Assessment return and questions along the way.',
        'services' => ['personal-tax', 'bookkeeping-services', 'business-tax-advice'],
    ],
    'contractors' => [
        'title' => 'Contractors',
        'summary' => 'Clear support for contract-based work.',
        'text' => 'Contract work brings its own tax and reporting questions, including those that arise under the Construction Industry Scheme. We help you understand how your arrangements are treated and keep your records straight.',
        'services' => ['construction-industry-scheme', 'personal-tax', 'business-tax-advice'],
    ],
    'freelancers' => [
        'title' => 'Freelancers',
        'summary' => 'Straightforward support for independent work.',
        'text' => 'Freelancing means managing invoices, expenses and tax yourself. We help you put a simple routine in place and prepare your return without last-minute scrambling.',
        'services' => ['personal-tax', 'bookkeeping-services', 'business-tax-advice'],
    ],
    'landlords' => [
        'title' => 'Landlords',
        'summary' => 'Rental income and property records made manageable.',
        'text' => 'Rental income has its own reporting and record-keeping needs. We help you organise property income and expenses and prepare the related tax return.',
        'services' => ['personal-tax', 'bookkeeping-services', 'hmrc-correspondence'],
    ],
    'health-workers' => [
        'title' => 'Health Workers',
        'summary' => 'Tax support alongside a demanding working life.',
        'text' => 'Health professionals often have a mix of employment and additional income. We help you understand your position and keep your tax affairs in order around your schedule.',
        'services' => ['personal-tax', 'business-tax-advice', 'hmrc-correspondence'],
    ],
    'employed-individuals' => [
        'title' => 'Employed Individuals',
        'summary' => 'When PAYE is not the whole picture.',
        'text' => 'Most employees are taxed through PAYE, but some circumstances call for a tax return or a closer look at your tax code. We can help you check your position and deal with HMRC.',
        'services' => ['personal-tax', 'hmrc-correspondence'],
    ],
];
