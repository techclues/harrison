<?php
declare(strict_types=1);

/*
 * Who We Help records, in menu order. Each has its own page under who-we-help/.
 * Copy carried over from the firm’s previous website by tools/build-content.py.
 * `services` values must be slugs from data/services.php.
 */
return [
    'start-ups' => [
        'title' => 'Start-ups',
        'summary' => 'Getting a new business set up properly from day one.',
        'text' => 'The earlier you talk to an accountant, the simpler the early decisions tend to be. We can talk through structure, registrations, bookkeeping and the tax questions that come with a new business.',
        'services' => ['business-tax-advice', 'company-secretarial-services', 'bookkeeping-services'],
        'description' => 'How Harrison Accountants supports start-ups: getting a new business set up properly from day one.',
        'lead' => 'At Harrison Accountants, we love working with people to create successful new businesses. In our experience, clients are often unaware of the amount of administration involved in setting a business up in the correct way to avoid future problems.',
        'body' => [
            ['h', 'We often help clients:'],
            [
                'ul',
                [
                    'Prepare a business plan',
                    'Prepare a financial forecast',
                    'Incorporate the company, including looking at different types of incorporation based on the client',
                    'Open a bank account',
                    'Apply for VAT registration',
                    'Set up a payroll scheme',
                    'Apply for corporation tax registration',
                    'Set up appropriate accounting systems',
                    'Plan their tax for the first few years',
                ],
            ],
            ['p', 'The earlier clients come and see us, the simpler it is!'],
            [
                'p',
                'Bring us your business ideas and we’ll help you to evaluate them in a constructive and realistic manner. We can also help you:',
            ],
            [
                'ul',
                [
                    'Decide on the most suitable structure for your business – sole trader, partnership, or limited company',
                    'Prepare a business plan, cashflow projections, budgets, and trading forecasts',
                    'Assess your finance requirements, advise on the best sources of finance, and draw up the necessary proposals',
                    'Establish a good working relationship with your bank',
                    'Complete any registration procedures with Companies House or HMRC',
                    'Deal with company secretarial issues',
                    'Set up a recording system for your internal use and for complying with statutory requirements',
                ],
            ],
            [
                'p',
                'Over the last few years, we have assisted many clients in setting up their own businesses, which are now hugely profitable. Depending on your business sector, we are happy to work with you to create a successful business. Please contact us for a free initial meeting to see how we can work together.',
            ],
        ],
        'image' => 'start-ups.webp',
        'review' => 'Confirm the free initial meeting is still offered.',
    ],
    'small-businesses' => [
        'title' => 'Small Businesses',
        'summary' => 'Practical support without the overhead of a big firm.',
        'text' => 'Small businesses have to keep costs sensible and compliance in order at the same time. We help with the routine work, such as accounts, VAT and payroll, so it does not take over your week.',
        'services' => ['accounting-services', 'vat', 'payroll'],
        'description' => 'How Harrison Accountants supports small businesses: practical support without the overhead of a big firm.',
        'lead' => 'Small businesses and companies in the start-up phase are limited in many ways, and finance is one of them. They need to keep expenses and the tax bill down while making sure they stay compliant. We ensure that our clients will not have any unwanted legal and compliance hassles from HMRC, Companies House and debt collection agencies later.',
        'body' => [
            ['h', 'How We Can Help You:'],
            [
                'p',
                'Small businesses are rarely in a position to hire expensive lawyers and accountants for basic accounting and company secretarial work. Harrison Accountants helps small businesses run successfully in the following ways:',
            ],
            [
                'ul',
                [
                    'Initial consultation on what form of business to choose (sole trader, partnership or a company)',
                    'Business plan, marketing research and financial forecasting',
                    'Liaising with banks for company bank accounts and advising your capital structure',
                    'Registered office service – you can run your business from home until it has a sizeable client base. In the meantime, we can act as your registered office and receive your correspondence',
                    'Payroll advice and dividend planning – we suggest the most tax- and NI-efficient pay structure, and how to draw company dividends tax-efficiently',
                    'Everyday accountancy services such as bookkeeping, VAT, Statutory Accounts preparation and filing, corporation tax and self-assessment.',
                ],
            ],
        ],
        'image' => 'small-businesses.webp',
        'review' => '',
    ],
    'established-businesses' => [
        'title' => 'Established Businesses',
        'summary' => 'A dependable second view as the business grows and changes.',
        'text' => 'As a business matures, decisions get larger and the reporting more detailed. We provide steady accounting support and a place to talk through growth, restructuring and planning.',
        'services' => ['accounting-services', 'business-tax-advice', 'corporation-tax'],
        'description' => 'How Harrison Accountants supports established businesses: a dependable second view as the business grows and changes.',
        'lead' => 'As an established business, you will have a good track record within your industry, with many years of financial success as well as loyal customers who respect your reputation.',
        'body' => [
            [
                'p',
                'As an established business, you may discover that you are faced with one or more of these challenges:',
            ],
            [
                'ul',
                [
                    'Need to improve profitability',
                    'As part of your growth strategy, need to acquire another business',
                    'Global and local expansion or trading',
                    'More proficient tax planning',
                    'Implementing an up-to-date cloud based accounting system',
                    'Business owners who want to exit the business',
                ],
            ],
            [
                'p',
                'It is important that an established business seeks professional business and accounting advice before any growth strategies are embarked upon. As an established business, a bespoke service would need to be agreed upon. Every business has different needs but a comprehensive package should include the following services.',
            ],
            [
                'ul',
                [
                    'Management accounts',
                    'Management of cash flow and cash flow forecasts',
                    'Payroll services',
                    'Reporting Expenses and Profits',
                    'Auto enrolment services',
                    'Preparing Year-end accounts',
                    'Proactive Tax planning',
                    'Corporate Tax returns',
                    'VAT returns',
                ],
            ],
            [
                'p',
                'Harrison Accountants will ensure that your accounting and business needs as an established business will be dealt with on a one-to-one basis creating a bespoke package specifically for your business needs.',
            ],
        ],
        'image' => 'established-businesses.webp',
        'review' => '',
    ],
    'directors' => [
        'title' => 'Directors',
        'summary' => 'Support with your company and your personal position.',
        'text' => 'Directors carry responsibilities for the company and have their own personal tax to consider. We help you keep company filings in order and understand how the two fit together.',
        'services' => ['company-secretarial-services', 'confirmation-statements', 'personal-tax'],
        'description' => 'How Harrison Accountants supports directors: support with your company and your personal position.',
        'lead' => 'A Limited Company is a legal entity in its own right and, as a director, you are the person responsible for ensuring that it conducts its affairs in a way that is compliant with all relevant laws and tax authority requirements.',
        'body' => [
            [
                'p',
                'Failure to do so could result in you being held to account and can mean you being personally financially responsible for certain losses. It is therefore vital that you ensure you have advisors who are knowledgeable in all these matters and can help you to adhere to all your obligations.',
            ],
            ['h', 'Remain Fully Compliant'],
            [
                'p',
                'As your accountants we will ensure that you are fully compliant. We will also be able to give you guidance on meeting your record keeping obligations so as to be able to render a full and complete return on your trading activities to HMRC. We will also keep you updated about the timetable for submission of various returns so you avoid late filing penalties.',
            ],
            [
                'p',
                'It is also important for you to comply with your filing obligations to Companies House, which include, notifications of changes to the officers of the company, the details for each officer, as well as filing each year’s accounts and your confirmation statement.',
            ],
            ['h', 'Companies House'],
            [
                'p',
                'These days, your trading partners will very likely have up to date information about your company from any one of a number of credit rating agencies. If your information at Companies House is not up to date, then this could affect your trading relationships. An example of this would be someone who has recently been appointed to be a director of your company, who holds themselves out as having such authority, but whose appointment had not yet been notified to Companies House.',
            ],
            [
                'p',
                'We will look after most of the statutory reporting requirements for you and to provide you with expert guidance and advice on all other aspects of company governance. For more information and to discuss your requirements with one of our experts please contact us today.',
            ],
        ],
        'image' => 'directors.webp',
        'review' => '',
    ],
    'sole-traders' => [
        'title' => 'Sole Traders',
        'summary' => 'Keeping your records and tax return in order.',
        'text' => 'When you trade as an individual, the business and your tax return are closely linked. We help with records, your Self Assessment return and questions along the way.',
        'services' => ['personal-tax', 'bookkeeping-services', 'business-tax-advice'],
        'description' => 'How Harrison Accountants supports sole traders: keeping your records and tax return in order.',
        'lead' => 'As a Sole Trader you are classified as self-employed by HM Revenue and Customs (HMRC) whether you sell goods or services. You also have to register with HMRC and certain rules and regulations apply, all of which we can take care of.',
        'body' => [
            [
                'p',
                'The responsibilities that come with being a Sole Trader are different to those of a company director who runs a limited company. You run your business as an individual and keep all business profits after tax has been paid on them. You are also solely responsible for losses that your business may make.',
            ],
            ['h', 'There are also certain HMRC rules and regulations that must be followed:'],
            [
                'ul',
                [
                    'Keep records of all business sales and expenses',
                    'Submit a Self-Assessment Tax Return each year',
                    'Pay Income Tax due on profits made',
                    'Pay National Insurance',
                    'Register for VAT if your taxable turnover goes over £90,000 in any 12 months (the threshold from 1 April 2024)',
                ],
            ],
            [
                'p',
                'We have hundreds of self-employed clients with years of experience. This means you’re in good hands. We will take on all the hard work and stress that comes with doing the bookkeeping and meeting HMRC deadlines so that you can run your successful business.',
            ],
        ],
        'image' => '',
        'review' => 'Updated: VAT registration threshold is £90,000 (was £85,000). Confirm “hundreds of self-employed clients”.',
    ],
    'contractors' => [
        'title' => 'Contractors',
        'summary' => 'Clear support for contract-based work.',
        'text' => 'Contract work brings its own tax and reporting questions, including those that arise under the Construction Industry Scheme. We help you understand how your arrangements are treated and keep your records straight.',
        'services' => ['construction-industry-scheme', 'personal-tax', 'business-tax-advice'],
        'description' => 'How Harrison Accountants supports contractors: clear support for contract-based work.',
        'lead' => 'A contractor has ‘contracts’ for jobs that are to be undertaken and contractors are bound to complete all work promised to the client under this contract. However, the contracting rules can make it incredibly complicated and specialist advice should be sought.',
        'body' => [
            [
                'p',
                'The key decision when starting out as a contractor is to decide what type of business model you will follow. This is an important choice as it will affect how much tax you will eventually pay. Our expert contractor accountants can advise you on the best options.',
            ],
            ['h', 'Limited Company'],
            [
                'p',
                'A limited company gives contractors “limited” liability which means that they are not personally liable for any financial difficulties faced by the company. Business expenses are deducted from the company’s profit meaning that you will end up paying less tax at the end of each tax year. Note that a Limited company also pays Corporation Tax.',
            ],
            [
                'p',
                'It is also important for you to comply with your filing obligations to Companies House, which include, notifications of changes to the officers of the company, the details for each officer, as well as filing each year’s accounts and your confirmation statement.',
            ],
            ['h', 'Partnership'],
            [
                'p',
                'Two or more people running a business together. Each partner is taxed on their share of the profits, much like a sole trader.',
            ],
            ['h', 'Sole Trader'],
            [
                'p',
                'The easiest and most straight forward option for contractors as there is no separation between you and the business and all profits are yours. Note, though, that some clients are less likely to work with contractors who are sole traders.',
            ],
            ['h', 'Umbrella Company'],
            [
                'p',
                'An umbrella company deals with things like sending invoices and chasing payments making you, the contractor, an employee of this company. It is a simple solution for contractors but you will pay higher taxes due to the fact that you are an employee and not a contractor.',
            ],
            [
                'p',
                'Harrison Accountants’ many years of accounting experience means that we can provide a great specialist accounting service for contractors.',
            ],
        ],
        'image' => '',
        'review' => 'A Companies House paragraph repeated under every business type on the old page now appears once, under Limited Company.',
    ],
    'freelancers' => [
        'title' => 'Freelancers',
        'summary' => 'Straightforward support for independent work.',
        'text' => 'Freelancing means managing invoices, expenses and tax yourself. We help you put a simple routine in place and prepare your return without last-minute scrambling.',
        'services' => ['personal-tax', 'bookkeeping-services', 'business-tax-advice'],
        'description' => 'How Harrison Accountants supports freelancers: straightforward support for independent work.',
        'lead' => 'Freelancing means managing invoices, expenses and tax yourself. We help you put a simple routine in place and prepare your return without last-minute scrambling.',
        'body' => [
            [
                'p',
                'People usually turn to freelancing because of all the benefits self-employment has over full-time employment:',
            ],
            [
                'ul',
                [
                    'You get to do something you love doing, and something which you are really good at.',
                    'You get the freedom that comes with being your own boss.',
                    'You get to work with the businesses and individuals you want to work with, and when you want to work.',
                    'You get more take-home pay.',
                ],
            ],
            [
                'p',
                'As a freelancer, you have to keep extensive financial records, which then form the basis of your Self-Assessment Tax Return, which you have to send after the end of each tax year (5th April). Accountancy for freelancers can all be very confusing and time-consuming, distracting you from the work you love doing.',
            ],
            [
                'p',
                'With our many years of experience, we’ve worked with individuals and businesses in all kinds of industries. We’re experts in taking the hard work of accountancy out of freelancers’ hands so they can spend more time doing what they love doing.',
            ],
        ],
        'image' => 'freelancers.webp',
        'review' => '',
    ],
    'landlords' => [
        'title' => 'Landlords',
        'summary' => 'Rental income and property records made manageable.',
        'text' => 'Rental income has its own reporting and record-keeping needs. We help you organise property income and expenses and prepare the related tax return.',
        'services' => ['personal-tax', 'bookkeeping-services', 'hmrc-correspondence'],
        'description' => 'How Harrison Accountants supports landlords: rental income and property records made manageable.',
        'lead' => 'As a landlord, you receive rent from one or more properties. It might be a challenge to manage rental accounts and tax returns when you have other responsibilities to address, as a landlord. For this reason, we provide a comprehensive range of services that make the job easier for you.',
        'body' => [
            [
                'ul',
                [
                    'We assist individual landlords and organisations that rent residential properties',
                    'We assist landlords who rent out furnished rooms to lodgers in their main home.',
                    'We assist landlords selling their rental property with Capital Gains Tax.',
                    'We provide many more accounting services especially for landlords.',
                ],
            ],
            [
                'p',
                'We provide insights and advice that propel your business in the right direction. We also make sure that you are compliant with the latest legislation. We can discuss your options with you and make sure that you make the right choice; to optimise your tax position and fit your needs. We can also make sure that you are claiming all of the finance costs for any loans or mortgages you have on your properties, as again, this can be a complicated area and taxpayers can miss out on valuable tax relief.',
            ],
            [
                'p',
                'Focus on the needs of your tenants and allow us to handle all your accountancy concerns. Schedule a free initial consultation, today, and receive a detailed proposal and preliminary advice from our accountants.',
            ],
        ],
        'image' => 'landlords.webp',
        'review' => '',
    ],
    'health-workers' => [
        'title' => 'Health Workers',
        'summary' => 'Tax support alongside a demanding working life.',
        'text' => 'Health professionals often have a mix of employment and additional income. We help you understand your position and keep your tax affairs in order around your schedule.',
        'services' => ['personal-tax', 'business-tax-advice', 'hmrc-correspondence'],
        'description' => 'How Harrison Accountants supports health workers: tax support alongside a demanding working life.',
        'lead' => 'Health workers manage hectic full-time schedules under intense time pressure, often making life-changing decisions every day. Some are employees, and some are locum doctors working through a limited company or an umbrella company, to improve workplace flexibility and their own wellbeing.',
        'body' => [
            [
                'p',
                'Working through business structures, such as limited companies or as a sole trader, can offer many advantages to your overall income, which can be a particularly tax-efficient way of operating depending on your circumstances. Setting up a limited company is easy and quick, and we can help you at every stage of the process, be it helping with your tax return or national insurance.',
            ],
            [
                'p',
                'We have extensive experience providing accountancy services to health workers, which means we can provide the tax and accounting support you need to successfully improve your financials and gain some more time back for yourself. This, in turn, will improve your work/life balance and give you additional time to spend with family, friends and hobbies.',
            ],
            ['h', 'Some of the services included are:'],
            [
                'ul',
                [
                    'Managing your business accounts effectively',
                    'Submission of your financial statements',
                    'Preparation and submission of tax returns',
                    'Tax planning to maximise tax efficiency',
                    'HMRC record checks and liaising with Companies House',
                    'Offering PAYE/Pension services',
                ],
            ],
        ],
        'image' => '',
        'review' => '',
    ],
    'employed-individuals' => [
        'title' => 'Employed Individuals',
        'summary' => 'When PAYE is not the whole picture.',
        'text' => 'Most employees are taxed through PAYE, but some circumstances call for a tax return or a closer look at your tax code. We can help you check your position and deal with HMRC.',
        'services' => ['personal-tax', 'hmrc-correspondence'],
        'description' => 'How Harrison Accountants supports employed individuals: when PAYE is not the whole picture.',
        'lead' => 'As an employee you tend to meet all of your responsibilities to the UK tax regime using the PAYE system, but there can be instances when submitting a tax return can be the best move for reducing the amount of tax you have to pay or because you are obliged to under HMRC rules.',
        'body' => [
            ['p', 'This might apply where you are employed and:'],
            [
                'ul',
                [
                    'You are self-employed or a partner in a partnership',
                    'You are a company director',
                    'You have large amounts of savings or investment income',
                    'You have untaxed savings or investment income',
                    'You’ve lived or worked abroad or aren’t domiciled in the UK',
                    'You own land or property that is being let',
                    'Your household receives Child Benefit and you have income over £60,000 (the threshold from April 2024)',
                    'You have income from overseas',
                    'You have sold or given an asset away (such as a holiday home or some shares)',
                ],
            ],
            [
                'p',
                'Even if some of these activities do not generate a profit (such as rental properties or self-employment), completing a tax return may be compulsory and even when a tax return is not required, it could lower your tax bill and therefore voluntarily completing one can be beneficial.',
            ],
            [
                'p',
                'Harrison Accountants can do a number of things for taxpayers that receive most or all of the income from employment, such as:',
            ],
            [
                'ul',
                [
                    'Prepare your tax return and check your tax position for you, because you suspect the tax collected by your employer(s) was too high or too low',
                    'Prepare your tax return because you have some untaxed income from savings/ properties or your income is over £100,000, when your personal allowance starts to be reduced',
                    'Make a claim for a tax refund from HMRC on your behalf',
                    'Check your tax code to ensure it is appropriate, meaning the tax collected by your employer each pay period is appropriate. Checking tax codes where you have multiple employments can be particularly important, as errors can often occur in such circumstances',
                    'If you have incurred expenses as a result of being employed, we can complete the claim form or tax return for you in order for you to obtain tax relief on them. This might be for items such as business mileage (this excludes commuting to your normal place of work), hotel accommodation/ meals/ business phone calls and professional subscriptions',
                ],
            ],
            [
                'p',
                'We will take the worry away when it comes to meeting your responsibilities and deadlines, whilst still ensuring that you pay only the right amount of tax and not a penny more.',
            ],
        ],
        'image' => 'employed-individuals.webp',
        'review' => 'Updated: High Income Child Benefit Charge threshold is £60,000 (was £50,000).',
    ],
];
