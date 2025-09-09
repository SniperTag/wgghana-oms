<?php 

namespace App\Providers;


class DepartmentStructure
{
    public static function get(): array
    {
       return [
            // ================= Administration =================
            'Administration' => [
                'parent_role' => 'admin',
                'sub_roles' => [
                    'ceo' => [
                        'title' => 'Chief Executive Officer (CEO)',
                        'permissions' => [
                            'view dashboard', 'manage employee', 'approve leave',
                            'generate payslip', 'view reports', 'plan strategy',
                            'approve loans', 'manage roles', 'approve budgets'
                        ]
                    ],
                    'coo' => [
                        'title' => 'Chief Operating Officer (COO)',
                        'permissions' => [
                            'view dashboard', 'manage clients', 'review reports',
                            'assign tasks', 'plan strategy', 'approve operations'
                        ]
                    ],
                    'cfo' => [
                        'title' => 'Chief Financial Officer (CFO)',
                        'permissions' => [
                            'view dashboard', 'approve budgets', 'manage payroll',
                            'approve loans', 'view financial reports', 'plan strategy'
                        ]
                    ],
                    'cio' => [
                        'title' => 'Chief Information Officer (CIO)',
                        'permissions' => [
                            'view dashboard', 'system security', 'approve IT projects',
                            'assign tasks', 'view reports'
                        ]
                    ],
                    'chro' => [
                        'title' => 'Chief Human Resources Officer (CHRO)',
                        'permissions' => [
                            'view dashboard', 'approve leave', 'manage employee',
                            'assign training', 'view HR reports'
                        ]
                    ],
                    'board_of_directors' => [
                        'title' => 'Board of Directors',
                        'permissions' => [
                            'view executive reports', 'approve major policies',
                            'oversee CEO performance', 'long-term governance'
                        ]
                    ],
                    'executive_assistant' => [
                        'title' => 'Executive Assistant',
                        'permissions' => [
                            'view dashboard', 'schedule appointment', 'manage calendar',
                            'schedule meetings', 'assist reporting'
                        ]
                    ],
                ]
            ],

            // ================= Human Resources =================
            'Human Resources' => [
                'parent_role' => 'manager',
                'sub_roles' => [
                    'hr_manager' => [
                        'title' => 'HR Manager',
                        'permissions' => [
                            'view dashboard', 'manage employee', 'approve leave',
                            'view reports', 'manage payroll', 'assign training'
                        ]
                    ],
                    'hr_officer' => [
                        'title' => 'HR Officer',
                        'permissions' => [
                            'view dashboard', 'view employees', 'recommend leaves',
                            'view reports', 'assist training'
                        ]
                    ],
                    'training_coordinator' => [
                        'title' => 'Training Coordinator',
                        'permissions' => [
                            'view dashboard', 'assign training', 'manage training programs',
                            'view reports'
                        ]
                    ]
                ]
            ],

            // ================= Information Technology =================
            'Information Technology' => [
                'parent_role' => 'supervisor',
                'sub_roles' => [
                    'it_director' => [
                        'title' => 'IT Director',
                        'permissions' => [
                            'view dashboard', 'manage IT team', 'approve IT projects',
                            'system security', 'backups', 'assign tasks', 'view reports'
                        ]
                    ],
                    'it_manager' => [
                        'title' => 'IT Manager',
                        'permissions' => [
                            'view dashboard', 'manage roles', 'system security',
                            'backups', 'assign tasks', 'view reports'
                        ]
                    ],
                    'web_developer' => [
                        'title' => 'Web Developer',
                        'permissions' => [
                            'deploy code', 'view dashboard', 'manage website',
                            'develop software', 'fix bugs'
                        ]
                    ],
                    'software_engineer' => [
                        'title' => 'Software Engineer',
                        'permissions' => [
                            'develop software', 'view dashboard', 'manage database',
                            'deploy code', 'fix bugs'
                        ]
                    ],
                    'ux_ui_designer' => [
                        'title' => 'UX/UI Designer',
                        'permissions' => [
                            'design interface', 'view dashboard', 'review usability',
                            'create mockups'
                        ]
                    ],
                    'systems_administrator' => [
                        'title' => 'Systems Administrator',
                        'permissions' => [
                            'manage roles', 'system security', 'backups',
                            'view dashboard', 'manage database', 'view system logs',
                            'monitor servers'
                        ]
                    ],
                    'network_administrator' => [
                        'title' => 'Network Administrator',
                        'permissions' => [
                            'manage network', 'system security', 'view dashboard',
                            'monitor servers', 'configure routers and switches'
                        ]
                    ],
                    'it_support_officer' => [
                        'title' => 'IT Support Officer',
                        'permissions' => [
                            'provide technical support', 'view dashboard', 'manage tickets'
                        ]
                    ],
                    'database_administrator' => [
                        'title' => 'Database Administrator',
                        'permissions' => [
                            'manage database', 'backup database', 'view dashboard',
                            'optimize database performance'
                        ]
                    ],
                    'devops_engineer' => [
                        'title' => 'DevOps Engineer',
                        'permissions' => [
                            'deploy code', 'view dashboard',
                            'monitor servers', 'manage cloud infrastructure'
                        ]
                    ],
                    'cybersecurity_officer' => [
                        'title' => 'Cybersecurity Officer',
                        'permissions' => [
                            'monitor security', 'system security', 'view dashboard',
                            'conduct audits', 'respond to incidents'
                        ]
                    ]
                ]
            ],

            // ================= Finance =================
            'Finance' => [
                'parent_role' => 'manager',
                'sub_roles' => [
                    'finance_director' => [
                        'title' => 'Finance Director',
                        'permissions' => [
                            'view dashboard', 'approve budgets', 'approve payroll',
                            'approve loans', 'view financial reports'
                        ]
                    ],
                    'finance_manager' => [
                        'title' => 'Finance Manager',
                        'permissions' => [
                            'manage payroll', 'approve loans', 'view dashboard',
                            'view financial reports', 'approve payroll'
                        ]
                    ],
                    'accountant' => [
                        'title' => 'Accountant',
                        'permissions' => [
                            'manage payroll', 'generate payslip', 'view dashboard',
                            'view financial reports'
                        ]
                    ],
                    'senior_accountant' => [
                        'title' => 'Senior Accountant',
                        'permissions' => [
                            'manage payroll', 'generate payslip', 'view dashboard',
                            'view financial reports'
                        ]
                    ],
                    'loans_officer' => [
                        'title' => 'Loans Officer',
                        'permissions' => [
                            'manage client loans', 'view dashboard', 'view financial reports',
                            'process loan applications', 'update client loan records'
                        ]
                    ],
                    'finance_analyst' => [
                        'title' => 'Finance Analyst',
                        'permissions' => [
                            'analyze financial data', 'generate reports', 'view dashboard'
                        ]
                    ],
                    'treasury_officer' => [
                        'title' => 'Treasury Officer',
                        'permissions' => [
                            'view dashboard'
                        ]
                    ],
                    'payroll_officer' => [
                        'title' => 'Payroll Officer',
                        'permissions' => [
                            'generate payslip', 'view dashboard'
                        ]
                    ],
                    'finance_coordinator' => [
                        'title' => 'Finance Coordinator',
                        'permissions' => [
                            'support finance operations', 'view dashboard'
                        ]
                    ]
                ]
            ],

            // ================= Business Development =================
            'Business Development' => [
                'parent_role' => 'team_lead',
                'sub_roles' => [
                    'head_of_bd' => [
                        'title' => 'Head of Business Development',
                        'permissions' => [
                            'view dashboard', 'assign tasks',
                            'review reports'
                        ]
                    ],
                    'business_dev_manager' => [
                        'title' => 'Business Development Manager',
                        'permissions' => [
                            'view dashboard', 'assign tasks', 'review strategy',
                            'manage clients', 'generate client reports'
                        ]
                    ],
                    'business_dev_supervisor' => [
                        'title' => 'Business Development Supervisor',
                        'permissions' => [
                            'view dashboard', 'supervise team',
                            'manage clients', 'report to manager'
                        ]
                    ],
                    'strategy_officer' => [
                        'title' => 'Strategy Officer',
                        'permissions' => [
                            'plan strategy', 'view dashboard', 'generate reports',
                            'review strategy'
                        ]
                    ],
                    'market_research_analyst' => [
                        'title' => 'Market Research Analyst',
                        'permissions' => [
                            'conduct research', 'view dashboard'
                        ]
                    ],
                    'sales_strategy_analyst' => [
                        'title' => 'Sales & Strategy Analyst',
                        'permissions' => [
                            'generate reports', 'view dashboard'
                        ]
                    ],
                    'lead_generation_specialist' => [
                        'title' => 'Lead Generation Specialist',
                        'permissions' => [
                            'view dashboard'
                        ]
                    ],
                    'sales_representative' => [
                        'title' => 'Sales Representative',
                        'permissions' => [
                            'convert leads', 'manage clients', 'view dashboard'
                        ]
                    ],
                    'marketing_bd_coordinator' => [
                        'title' => 'Marketing & BD Coordinator',
                        'permissions' => [
                            'generate reports', 'view dashboard'
                        ]
                    ],
                    'bd_operations_coordinator' => [
                        'title' => 'BD Operations Coordinator',
                        'permissions' => [
                            'view dashboard'
                        ]
                    ],
                    'crm_specialist' => [
                        'title' => 'CRM Specialist',
                        'permissions' => [
                            'view dashboard', 'generate client reports'
                        ]
                    ],
                ]
            ],

            // ================= Reception =================
            'Reception' => [
                'parent_role' => 'staff',
                'sub_roles' => [
                    'receptionist' => [
                        'title' => 'Receptionist',
                        'permissions' => [
                            'view dashboard', 'register visitor', 'check in visitor',
                            'check out visitor', 'schedule appointment',
                            'view visitor list', 'print visitor badge'
                        ]
                    ],
                    'front_desk_supervisor' => [
                        'title' => 'Front Desk Supervisor',
                        'permissions' => [
                            'view dashboard', 'register visitor', 'check in visitor',
                            'check out visitor', 'schedule appointment',
                            'view visitor list', 'print visitor badge',
                            'flag visitor', 'generate visitor reports',
                            'manage reception staff schedule'
                        ]
                    ],
                    'concierge' => [
                        'title' => 'Concierge',
                        'permissions' => [
                            'assist VIP guests', 'coordinate logistics', 'manage hospitality requests'
                        ]
                    ],
                    'visitor_security_officer' => [
                        'title' => 'Visitor Security Officer',
                        'permissions' => [
                            'screen visitors', 'verify ID', 'deny access', 'escort visitor'
                        ]
                    ]
                ]
            ],

            // ================= Security =================
            'Security' => [
                'parent_role' => 'staff',
                'sub_roles' => [
                    'chief_security_officer' => [
                        'title' => 'Chief Security Officer (CSO)',
                        'permissions' => [
                            'view dashboard', 'approve incident logs', 'plan security strategy', 'access all security data'
                        ]
                    ],
                    'security_supervisor' => [
                        'title' => 'Security Supervisor',
                        'permissions' => [
                            'view dashboard', 'view visitor list', 'verify visitor check-in',
                            'flag visitor', 'view emergency log', 'monitor overstays',
                            'assign tasks'
                        ]
                    ],
                    'security_officer' => [
                        'title' => 'Security Officer',
                        'permissions' => [
                            'view dashboard', 'view visitor list', 'verify visitor check-in',
                            'view emergency log', 'monitor overstays', 'report incidents'
                        ]
                    ],
                    'control_room_operator' => [
                        'title' => 'Control Room Operator',
                        'permissions' => [
                            'view dashboard', 'monitor CCTV',
                            'alert supervisor', 'generate monitoring reports'
                        ]
                    ],
                    'access_control_officer' => [
                        'title' => 'Access Control Officer',
                        'permissions' => [
                            'view dashboard', 'manage access cards', 'approve entry',
                            'deny entry'
                        ]
                    ],
                    'emergency_response_officer' => [
                        'title' => 'Emergency Response Officer',
                        'permissions' => [
                            'view dashboard', 'view emergency log', 'log emergency drills',
                            'respond to incidents'
                        ]
                    ]
                ]
            ],

            // ================= Legal =================
            'Legal' => [
                'parent_role' => 'staff',
                'sub_roles' => [
                    'chief_legal_officer' => [
                        'title' => 'Chief Legal Officer (CLO) / General Counsel',
                        'permissions' => [
                            'view dashboard', 'manage legal team', 'approve contracts',
                            'review compliance reports', 'advise management',
                            'access all legal documents'
                        ]
                    ],
                    'deputy_general_counsel' => [
                        'title' => 'Deputy General Counsel',
                        'permissions' => [
                            'view dashboard', 'draft contracts',
                            'advise departments', 'manage junior lawyers',
                            'oversee litigation'
                        ]
                    ],
                    'corporate_counsel' => [
                        'title' => 'Corporate Counsel / Legal Advisor',
                        'permissions' => [
                            'view dashboard', 'draft contracts',
                            'handle compliance', 'advise departments'
                        ]
                    ],
                    'compliance_officer' => [
                        'title' => 'Compliance Officer',
                        'permissions' => [
                            'view dashboard', 'review compliance reports',
                            'advise departments'
                        ]
                    ],
                    'contract_manager' => [
                        'title' => 'Contract Manager',
                        'permissions' => [
                            'view dashboard', 'draft contracts'
                        ]
                    ],
                    'paralegal' => [
                        'title' => 'Paralegal / Legal Assistant',
                        'permissions' => [
                            'view dashboard',
                            'maintain legal records'
                        ]
                    ],
                    'legal_intern' => [
                        'title' => 'Legal Intern / Trainee',
                        'permissions' => [
                            'view dashboard', 'assist paralegal',
                            'conduct research', 'draft basic documents'
                        ]
                    ],
                ]
            ],

            // ================= Client Services =================
            'Client Services' => [
                'parent_role' => 'staff',
                'sub_roles' => [
                    'client_service_manager' => [
                        'title' => 'Client Service Manager',
                        'permissions' => [
                            'view dashboard', 'manage client accounts', 'assign client officers',
                            'handle escalations', 'review service quality', 'generate client reports'
                        ]
                    ],
                    'account_manager' => [
                        'title' => 'Account Manager',
                        'permissions' => [
                            'view dashboard', 'manage assigned clients', 'track client satisfaction',
                            'resolve client issues', 'coordinate with departments'
                        ]
                    ],
                    'customer_success_specialist' => [
                        'title' => 'Customer Success Specialist',
                        'permissions' => [
                            'view dashboard', 'manage onboarding', 'track product usage',
                            'provide training', 'recommend solutions', 'log client feedback'
                        ]
                    ],
                    'client_relations_officer' => [
                        'title' => 'Client Relations Officer',
                        'permissions' => [
                            'view dashboard', 'respond to client inquiries',
                            'schedule client meetings', 'update client records'
                        ]
                    ],
                    'support_associate' => [
                        'title' => 'Support Associate',
                        'permissions' => [
                            'view dashboard', 'respond to support tickets',
                            'assist with troubleshooting'
                        ]
                    ],
                ]
            ],

            // ================= Innovation =================
            'Innovation' => [
                'parent_role' => 'manager',
                'sub_roles' => [
                    'chief_innovation_officer' => [
                        'title' => 'Chief Innovation Officer',
                        'permissions' => [
                            'view dashboard', 'plan strategy', 'review strategy',
                            'generate reports', 'review reports', 'assign tasks', 'manage clients'
                        ]
                    ],
                    'innovation_manager' => [
                        'title' => 'Innovation Manager',
                        'permissions' => [
                            'view dashboard', 'plan strategy', 'review strategy',
                            'assign tasks', 'review tasks', 'generate reports'
                        ]
                    ],
                    'product_developer' => [
                        'title' => 'Product Developer',
                        'permissions' => [
                            'view dashboard', 'develop software', 'deploy code',
                            'design interface', 'review usability', 'manage website', 'manage database'
                        ]
                    ],
                    'innovation_analyst' => [
                        'title' => 'Innovation Analyst',
                        'permissions' => [
                            'view dashboard', 'view reports', 'generate reports',
                            'submit reports', 'review reports', 'assign tasks', 'complete tasks'
                        ]
                    ],
                    'innovation_coordinator' => [
                        'title' => 'Innovation Coordinator',
                        'permissions' => [
                            'view dashboard', 'assign tasks', 'complete tasks',
                            'review tasks', 'manage calendar', 'schedule meetings', 'view calendar'
                        ]
                    ]
                ]
            ],
        ];
    }
}