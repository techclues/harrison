<?php
declare(strict_types=1);

/*
 * Canonical service records, in menu order. Copy carried over from the firm’s previous website
 * (same firm, rebranded) by tools/build-content.py, with outdated facts corrected.
 * body blocks: ['p', text] | ['h', heading] | ['ul', [items]]. image: file in assets/images/pages/.
 */
return [
    'business-tax-advice' => [
        'title' => 'Business & Tax Advice',
        'group' => 'advice',
        'summary' => 'Planning conversations for the decisions that shape your business.',
        'description' => 'Business and tax advice from Harrison Accountants: practical planning for start-ups, growth and the decisions in between.',
        'lead' => 'Running a business can be challenging at the best of times, whether you are a sole trader or have a senior management team and board. There are times when things are not going to plan, or when you\'re looking to do something new or different.',
        'body' => [
            [
                'p',
                'Don’t forget that as your business becomes more successful, you’ll want to have ways to benefit personally from that in the most tax efficient way possible. That’s why you’ll want to talk to us about your personal tax planning.',
            ],
            [
                'p',
                'The advice and support received from your accountant is often invaluable in such situations, however, it can also be the case that more specialist or dedicated support is required.',
            ],
            [
                'p',
                'We work with a variety of different sized businesses, large or small, but we also specialise in start-ups and entrepreneurs. As a result, we pride ourselves on being able to provide strong tax planning and advice specially tailored for you. Getting taxation right from the beginning can only help to grow your business and ensure it is operating in the most efficient way possible.',
            ],
            [
                'p',
                'We know that we can provide you with the right tax planning and the right tax advice so that your obligations are met without paying a pound more than you need to.',
            ],
            [
                'p',
                'The Harrison Accountants team is here to help guide you through the expansion period ensuring that, if you’re ready for expansion, everything goes as planned and we’ll provide valuable advice and insights along the way.',
            ],
        ],
        'image' => '',
        'related' => ['corporation-tax', 'personal-tax', 'accounting-services'],
        'review' => '',
    ],
    'business-rates' => [
        'title' => 'Business Rates',
        'group' => 'advice',
        'summary' => 'A closer look at whether your business rates bill is right.',
        'description' => 'Business rates support from Harrison Accountants: reviewing your bill and the reliefs that may apply to your property.',
        'lead' => 'Overpaying business rates is common, and businesses lose thousands of pounds every year through bills that are wrong.',
        'body' => [
            ['h', 'Why might you be getting overcharged for business rates?'],
            [
                'ul',
                [
                    'Bills are issued to landlords and tenants before the necessary adjustments have been made',
                    'Empty property relief periods differ by property type, and the longer relief for industrial premises is not always applied',
                    'Small business rate relief not applied',
                    'Charitable relief not applied',
                    'Empty Property relief not applied',
                ],
            ],
            [
                'p',
                'Here at Harrison Accountants, we understand how much this could affect your business, therefore we offer a comprehensive service of reclaiming business rates. We have reclaimed thousands for our previous clients.',
            ],
        ],
        'image' => 'business-rates.webp',
        'related' => ['business-tax-advice', 'accounting-services'],
        'review' => 'Confirm the claim “We have reclaimed thousands for our previous clients” is still accurate for Harrison.',
    ],
    'accounting-services' => [
        'title' => 'Accounting Services',
        'group' => 'records',
        'summary' => 'Accounts that explain the business, not just satisfy a deadline.',
        'description' => 'Accounting services from Harrison Accountants: year-end accounts and ongoing support presented clearly.',
        'lead' => 'We take great pride in providing accounting excellence which is why our accounting and business support services involve much more than completing a set of accounts.',
        'body' => [
            [
                'p',
                'We take the time to listen and discuss the vision for you and your business and then provide the services that are relevant and specific to you and your needs giving you advice that is easy to understand and truly being a support to your business.',
            ],
            [
                'p',
                'With the raft of current business challenges, it is important to obtain clear and correct accounting advice at the right time. Our accounts team offer business support services and expertise that you would normally expect to find in a much larger firm of accountants.',
            ],
            [
                'p',
                'Competent accountancy support with a professional service is the minimum you would expect from your accounting providers. You will be able to concentrate on the things that make you successful, secure in the knowledge that you have great accountants looking after your finances.',
            ],
        ],
        'image' => '',
        'related' => ['bookkeeping-services', 'corporation-tax', 'business-tax-advice'],
        'review' => '',
    ],
    'corporation-tax' => [
        'title' => 'Corporation Tax',
        'group' => 'tax',
        'summary' => 'Clarity on your company’s tax position and filing responsibilities.',
        'description' => 'Corporation tax support from Harrison Accountants: computations, returns and a clear view of your company’s position.',
        'lead' => 'If your business is a Limited Company, you are required to pay corporation tax on your profits. As a business owner, it is your legal responsibility to ensure strict compliance with your corporate tax obligations, so it is vital to access the accountancy support you need to get it right.',
        'body' => [
            [
                'p',
                'UK Corporate tax regulations require business owners to calculate how much profit a company makes for each accounting period, the amount of corporation tax payable on those profits and then report this to HMRC on a corporation tax return form along with accounts and tax computations that support your return. Filing a return that is either incomplete or late can result in financial penalties, so it is essential that you provide all required information and meet your company’s annual accounting date deadline.',
            ],
            [
                'p',
                'At Harrison Accountants, our highly experienced corporation tax accountants will accurately and efficiently prepare your company accounts and tax returns, thus ensuring your company is compliant with all your corporation tax obligations. At the same time, we will also ensure all applicable allowances and deductions are made to minimise your tax liability.',
            ],
            ['h', 'Our accountants can provide you with the following services:'],
            [
                'ul',
                [
                    'Registering you with HMRC and Companies House',
                    'Preparation of corporation tax returns',
                    'Determining corporation tax liabilities and repayments',
                    'Advising you of tax payments and their payment deadlines',
                    'Filing a corporation tax return with HM Revenue & Customs',
                    'Keeping you up to date with current tax legislation and thresholds',
                ],
            ],
        ],
        'image' => '',
        'related' => ['accounting-services', 'business-tax-advice', 'confirmation-statements'],
        'review' => '',
    ],
    'bookkeeping-services' => [
        'title' => 'Bookkeeping Services',
        'group' => 'records',
        'summary' => 'Accurate day-to-day records you can rely on.',
        'description' => 'Bookkeeping services from Harrison Accountants: tidy, accurate records that support decisions, VAT and tax.',
        'lead' => 'Bookkeeping is a key part of your overall control, so don’t underestimate its importance as it can make the difference on how profitable your business is.',
        'body' => [
            ['h', 'Why Is Bookkeeping Important for Your Company?'],
            [
                'p',
                'Bookkeeping is absolutely fundamental to successful operation. It provides you with a clear financial picture of your business. Accurate books allow you to:',
            ],
            [
                'ul',
                [
                    'Make business decisions based on your current financial situation',
                    'Create and amend budgets',
                    'Formulate reports and profit projections',
                    'Provide HMRC with transparent information',
                    'File your tax returns properly and claim everything you are entitled to',
                ],
            ],
            [
                'p',
                'Our bookkeeping service enables you to retain control of your finances and ensure that correct controls are in place. We make sure that we accurately record all of the financial transactions for your business including purchases, sales, receipts and payments by an individual or organization. Our service will:',
            ],
            [
                'ul',
                [
                    'Save your valuable time and money',
                    'Ensure that your books are managed by competent bookkeepers',
                    'Ensure you have accurate processing of your invoices',
                    'Take away your stress in managing administrative tasks',
                    'Allow you more time and money to focus on the strategic affairs of the business',
                ],
            ],
        ],
        'image' => 'bookkeeping-services.webp',
        'related' => ['accounting-services', 'vat', 'payroll'],
        'review' => '',
    ],
    'company-secretarial-services' => [
        'title' => 'Company Secretarial Services',
        'group' => 'company',
        'summary' => 'The formal side of running a limited company, kept in order.',
        'description' => 'Company secretarial services from Harrison Accountants: company records, changes and filings with Companies House.',
        'lead' => 'We tailor our company secretarial service to meet your needs, providing services to ensure you comply with local regulations.',
        'body' => [
            [
                'p',
                'Businesses of all sizes are faced with increasing challenges in the administration of legal entities. You will need to ensure that you are compliant with all the requirements and laws. As businesses grow, maintaining an overview of these activities and managing their risk is challenging.',
            ],
            [
                'p',
                'Our principal aim is to ensure you avoid compliance defaults and meet filing deadlines reducing the costs of fines and mitigating the risk of prosecution.',
            ],
            ['h', 'Company Formation'],
            [
                'p',
                'When you set up a company there is a myriad of form filling. We can register your company name, provide a certificate of incorporation and assist with the company’s memorandum and articles of association. We file all necessary forms with the Registrar of Companies.',
            ],
            ['h', 'Provision of Registered Facilities'],
            [
                'p',
                'Formal legal notices are sent to a company’s registered office. As your accountants we can host your registered office. This ensures that your important mail is dealt with promptly.',
            ],
            ['h', 'Changes in Directors & Company Secretaries'],
            [
                'p',
                'When you appoint a new director or company secretary, the Registrar of Companies needs to be informed. We will complete the necessary information and send it to Companies House.',
            ],
            ['h', 'Amendments to Memorandum & Articles of Association'],
            [
                'p',
                'Sometimes you need to change the company’s constitution, for example to increase the authorised share capital or to permit a purchase by the company of its own shares. We draft the necessary resolution, file a copy with the Registrar of Companies, and write up the statutory books.',
            ],
            ['h', 'Company Searches'],
            [
                'p',
                'If you wish to find out information about other companies, we have an adept research team with access to a company’s information database plus we can assess the information filed at Companies House. More in-depth research about other companies can be performed by our Corporate Finance department.',
            ],
            ['h', 'Restructuring of Share Capital'],
            [
                'p',
                'From time to time the company’s share capital needs to change. We can guide you through the process of changing voting and dividend rights and will send the Registrar of Companies the necessary paperwork.',
            ],
            ['h', 'Document Verification'],
            [
                'p',
                'If you have been asked to supply certified copies of your original documents, Harrison Accountants can certify that the copies presented/produced are a true likeness of the original document/s.',
            ],
            [
                'p',
                'If you’re providing photocopies to the recipient company, you’ll need to ensure that these are like-for-like with the originals. We’ll check up to three original documents against the photocopies and certify that each is a true likeness.',
            ],
            ['h', 'Which documents can I get checked?'],
            [
                'ul',
                ['Passport', 'Driving licence', 'Recent utility bills', 'Bank statements'],
            ],
            ['h', 'Scanning & Filing'],
            [
                'p',
                'Now more than ever businesses are looking for ways to go paperless. The convenience of being able to keep records, save files, store large amounts of information and more importantly have the information to hand at all times makes it very attractive. This is why we offer to support our clients with implementing a paperless approach. We aim to achieve this within our own office and encourage others to do so for many beneficial reasons.',
            ],
            ['h', 'Photocopying'],
            [
                'p',
                'Photocopies can be produced from original documents if their condition allows. Where documents are bound, delicate or larger than A3, we can offer digital copying and colour printing instead.',
            ],
            ['h', 'Shredding & Disposal'],
            [
                'p',
                'Financial information or non-financial information for that matter when running a business can be sensitive and confidential. The importance of disposing sensitive information cannot be underestimated and for this reason we provide shredding & disposal services for the convenience of our clients.',
            ],
        ],
        'image' => '',
        'related' => ['confirmation-statements', 'corporation-tax', 'accounting-services'],
        'review' => 'Confirm Harrison offers registered office, certification, scanning, shredding and company search services, and still has a Corporate Finance department.',
    ],
    'confirmation-statements' => [
        'title' => 'Confirmation Statements',
        'group' => 'company',
        'summary' => 'An annual check that your company’s public details are up to date.',
        'description' => 'Confirmation statement support from Harrison Accountants: preparing and filing your company’s annual statement.',
        'lead' => 'A Confirmation Statement, which replaced the Annual Return in 2016, is a snapshot of the general information about a company, its directors, secretary, registered office address and share capital, and must be filed with Companies House annually confirming that the information held for the company is up to date.',
        'body' => [
            [
                'p',
                'Confirmation Statements are made at least once a year. More often than not, they are due on the anniversary of the date the company was incorporated, or else a year after the date the last Confirmation Statement was filed, and you also have an extra 14 days from this date in which to file.',
            ],
            [
                'p',
                'It’s worth mentioning that Confirmation Statements must still be made even if there haven’t been any changes since the last time, and even if the company is dormant.',
            ],
            [
                'p',
                'Our accountants start preparing confirmation statements, just as soon as the anniversary of the incorporation date has passed. This allows plenty of time to review the draft statement, so it is never filed late. Late filing can lead to the company being struck off and can affect its credit rating.',
            ],
            [
                'p',
                'It’s important to note that missing your filing date can lead to the prosecution of directors and dissolution of your company.',
            ],
            [
                'p',
                'Our accountants will prepare and file your confirmation statement with Companies House, so please get in touch.',
            ],
        ],
        'image' => '',
        'related' => ['company-secretarial-services', 'corporation-tax'],
        'review' => 'Updated: the Annual Return was replaced in 2016. Since 2024 statements also confirm a registered email address and lawful purpose; mention if wanted.',
    ],
    'vat' => [
        'title' => 'VAT',
        'group' => 'tax',
        'summary' => 'Help choosing, registering for and keeping up with VAT.',
        'description' => 'VAT support from Harrison Accountants: registration, scheme selection, returns and dealing with HMRC.',
        'lead' => 'Value Added Tax (“VAT”) is a tax on transactions - the decisions on whether it applies are taken every day on charges applied by businesses to their customers. Once an incorrect decision is made, businesses can waste many hours making corrections. This is where we come in.',
        'body' => [
            [
                'p',
                'We’ll help you choose the right VAT scheme, and make sure you understand and comply with VAT laws and regulations. VAT-registered businesses must now keep digital records and file returns through Making Tax Digital (MTD) compatible software, and we can set this up for you. By keeping up to date with the latest VAT legislation, our clients can be confident that they are being compliant with the VAT regulation.',
            ],
            ['h', 'Our service covers a number of key areas:'],
            [
                'ul',
                [
                    'Application to enrol on the VAT scheme',
                    'Recommend the most suitable VAT scheme to join',
                    'VAT planning',
                    'Complete and file your VAT returns on time, helping you avoid penalties',
                    'Manage any disputes which may arise with HM Revenue & Customs',
                    'Attendance for VAT inspections',
                ],
            ],
        ],
        'image' => 'vat.webp',
        'related' => ['bookkeeping-services', 'hmrc-correspondence', 'accounting-services'],
        'review' => 'Updated: Making Tax Digital is now in force for all VAT-registered businesses (the old copy said it was coming).',
    ],
    'payroll' => [
        'title' => 'Payroll',
        'group' => 'people',
        'summary' => 'Dependable payroll so your team is paid correctly and on time.',
        'description' => 'Payroll services from Harrison Accountants: payslips, PAYE reporting and year-end returns handled for you.',
        'lead' => 'Running payroll is now becoming increasingly complex and time consuming. Under the RTI (Real Time Information) rules it is even more important now to run the payroll on time and accurately report all the information to HMRC. This avoids mistakes which can be costly in terms of penalties and fines.',
        'body' => [
            ['p', 'We offer a cost-effective and reliable payroll service:'],
            [
                'ul',
                [
                    'Payslips - production and distribution',
                    'Monthly Payroll summaries and reports (by department for example)',
                    'Management of new starters and leavers',
                    'Analysis of staff costs',
                    'PAYE returns',
                    'Assistance with automated payment set-up to your employees',
                    'Year-end P60s for every employee',
                    'P11D benefits and expenses returns',
                ],
            ],
            [
                'p',
                'If you are looking for someone to take the payroll burden off your shoulders then our payroll outsourcing package is the one for you. It allows our clients to:',
            ],
            [
                'ul',
                [
                    'Risk reduction – payrolls not affected if in-house payroll people were off sick or left the company.',
                    'Cost Saving – no need to buy expensive payroll software and support systems.',
                    'Payroll advice from team of experts.',
                    'Freeing up time and resources that can be re-invested in other parts of your business.',
                    'No payroll security problems.',
                    'Daily back-ups and full disaster recovery plan in operation.',
                    'Lower overheads, with no in-house payroll staff to fund.',
                    'Peace of mind that your payroll is in expert hands.',
                ],
            ],
        ],
        'image' => '',
        'related' => ['pension-auto-enrolment', 'bookkeeping-services', 'hmrc-correspondence'],
        'review' => 'Updated: P35, P14 and P9D forms were abolished; replaced with current P60 and P11D wording.',
    ],
    'pension-auto-enrolment' => [
        'title' => 'Pension / Auto-Enrolment',
        'group' => 'people',
        'summary' => 'Support with your workplace pension responsibilities as an employer.',
        'description' => 'Pension and auto-enrolment support from Harrison Accountants: helping employers meet workplace pension duties.',
        'lead' => 'Auto-enrolment is the biggest change to workplace pensions for generations. The crux is about making sure employees are saving for retirement and that they have a pension. As a result of the changes to workplace pension legislation, all employers are required to ensure more of their employees have a pension in place.',
        'body' => [
            [
                'p',
                'Auto-enrolment means that as an employer, you are required to automatically enrol your employees in a pension scheme.',
            ],
            [
                'p',
                'As pension specialists, we are able to guide and support you through the process of establishing an auto-enrolment scheme that meets your needs and those of The Pensions Regulator.',
            ],
            ['h', 'The work of financial planners includes:'],
            [
                'ul',
                [
                    'An initial consultation - an assessment is made of the workforce and a bespoke report is prepared to ascertain the impact auto-enrolment will have on the organisation from a financial and administrative perspective.',
                    'Research and Recommendations - our financial planners research and recommend an appropriate scheme to meet Auto-enrolment requirements.',
                    'Scheme Implementation - providing support and assistance with the full implementation process to integrate the scheme structure.',
                ],
            ],
        ],
        'image' => '',
        'related' => ['payroll', 'business-tax-advice'],
        'review' => 'Confirm the “financial planners” recommending schemes are FCA-authorised, or reword. The self-assessment paragraph on the old page moved to Personal Tax.',
    ],
    'personal-tax' => [
        'title' => 'Personal Tax',
        'group' => 'tax',
        'summary' => 'Self Assessment and personal tax handled with care.',
        'description' => 'Personal tax and Self Assessment support from Harrison Accountants for individuals, directors, landlords and the self-employed.',
        'lead' => 'Our self-assessment service for individuals helps to ensure that your tax return is completed in a timely and efficient way, whilst at the same time ensuring the right amount of tax is paid.',
        'body' => [
            [
                'p',
                'If you are self-employed, you\'ll need to let HMRC know about your annual earnings, so that you pay the appropriate amount of tax.',
            ],
            ['h', 'Self-Assessment applies to:'],
            [
                'ul',
                [
                    'Self-employed people including business partners',
                    'Company Directors',
                    'Individuals with more complicated tax affairs including those who pay higher rate tax',
                    'Pensioners with more complex tax affairs',
                    'People who receive rent or other income from land and property in the UK',
                    'Non-resident company landlords',
                ],
            ],
            [
                'p',
                'Self-Assessment can be a time consuming and often confusing business for busy individuals and judging by the hundreds of thousands of taxpayers who are penalised by HMRC each year it can also be very costly if:',
            ],
            [
                'ul',
                [
                    'Deadlines are missed',
                    'Incorrect returns are delivered',
                    'Appropriate records are not retained or presented',
                ],
            ],
            [
                'p',
                'We aim to spare you all these headaches and free up your valuable time by offering a comprehensive Individual Self-Assessment service, including:',
            ],
            [
                'ul',
                [
                    'Self Assessment registration for directors and self employed',
                    'Preparation of personal tax returns for self-employed, partners and company directors',
                    'Calculating your tax liability',
                    'Advising you when various payments are due and how much to pay',
                    'Advising on appropriate record retention',
                    'Efficient personal tax planning to minimise your overall tax bill',
                    'Tax claims and refund administration',
                    'Handling disputes with HMRC regarding tax liability and other related issues',
                ],
            ],
            ['h', 'Easing the burden'],
            [
                'p',
                'You may choose to complete your own self-assessment tax return. However, you may find using our services has a number of benefits. These include:',
            ],
            [
                'ul',
                [
                    'Ensuring all tax allowances and deductions are claimed',
                    'Easing the burden of additional paperwork',
                    'Specialist tax knowledge which can help you minimise or even mitigate fully any tax liability, now or in the future.',
                ],
            ],
        ],
        'image' => '',
        'related' => ['hmrc-correspondence', 'business-tax-advice', 'corporation-tax'],
        'review' => 'A stray Confirmation Statement paragraph from the old page was removed.',
    ],
    'construction-industry-scheme' => [
        'title' => 'Construction Industry Scheme',
        'group' => 'tax',
        'summary' => 'Support for contractors and subcontractors working under CIS.',
        'description' => 'Construction Industry Scheme support from Harrison Accountants for contractors and subcontractors.',
        'lead' => 'We offer services to both contractors and subcontractors working in the construction industry.',
        'body' => [
            ['h', 'For contractors (businesses that pay subcontractors) we offer:'],
            [
                'ul',
                [
                    'Processing of subcontractor payments and tax deductions',
                    'Provision of monthly statements (including gross payments and tax deductions) to subcontractors',
                    'Online submission of monthly CIS returns',
                    'Tax related advice on various aspects of the Construction Industry',
                    'Advice and assistance on obtaining CIS registration',
                ],
            ],
            ['h', 'For individual subcontractors we offer:'],
            [
                'ul',
                [
                    'Preparation and submission of annual accounts',
                    'Preparation and submission of tax returns',
                    'Reclaiming of tax deducted on earnings',
                    'Tax related advice on various aspects of the Construction Industry',
                    'Advice and assistance on registering with HMRC',
                ],
            ],
        ],
        'image' => 'construction-industry-scheme.webp',
        'related' => ['personal-tax', 'bookkeeping-services', 'hmrc-correspondence'],
        'review' => '',
    ],
    'hmrc-correspondence' => [
        'title' => 'HMRC Correspondence',
        'group' => 'tax',
        'summary' => 'A steady representative for letters, queries and enquiries.',
        'description' => 'HMRC correspondence support from Harrison Accountants: helping you respond to letters, queries and enquiries.',
        'lead' => 'Correspondence with HM Revenue & Customs (HMRC) is something many business owners dread. Not only do these kinds of administrative task absorb valuable time, but the prospect of contact can also be daunting. When you aren’t a financial expert, the idea of communicating with such an important governing body can be stressful.',
        'body' => [
            [
                'ul',
                [
                    'What if you say the wrong thing?',
                    'What if you don’t understand what is being said?',
                    'What if you miss important information?',
                    'What if you forget to ask something crucial?',
                ],
            ],
            ['h', 'Why you can’t avoid HMRC correspondence'],
            [
                'p',
                'Failure to meet HMRC correspondence responsibilities can result in major legal consequences, including penalties, fines and even court cases if the implications are serious enough. If HMRC correspondence is required of your business, it cannot be avoided. It’s important to be aware, however, that you as the business owner do not have to be the one to talk to HMRC.',
            ],
            [
                'p',
                'HMRC correspondence can be covered by a representative of your company. Our team at Harrison Accountants can be that representative.',
            ],
            ['h', 'What we can offer you'],
            [
                'p',
                'Remove the stress and uncertainty from your HMRC correspondence by having the Harrison Accountants team manage communication on your behalf. Our accountants have over a decade’s worth of experience in the industry, throughout which time we’ve handled more clients’ HMRC correspondence obligations than we can count.',
            ],
            [
                'p',
                'We know exactly how to deal with HMRC. We know how it likes to communicate. We know how to get the best results out of communication for our clients. We know how to make sure your business is providing everything HMRC requires.',
            ],
            [
                'p',
                'With our services, there is never any question of missing information or potential consequences from improper correspondence. We take any question marks out of the process and offer true peace of mind. Our team of HMRC tax advisors is highly specialised in all tax legislation, and that’s what makes us so good at what we do.',
            ],
        ],
        'image' => 'hmrc-correspondence.webp',
        'related' => ['personal-tax', 'vat', 'business-tax-advice'],
        'review' => 'Confirm “over a decade’s worth of experience” for Harrison.',
    ],
];
