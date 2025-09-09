<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Roles & Sub-Roles
    |--------------------------------------------------------------------------
    |
    | Define all parent roles (departments) and their sub-roles, including titles
    | and permissions. This allows dynamic generation of menus, access controls,
    | and seeders.
    |
    */

    'roles' => [
        'admin' => [
            'title' => 'Administration',
            'sub_roles' => [
                'ceo' => [
                    'title' => 'Chief Executive Officer',
                    'permissions' => ['view dashboard', 'manage employee', 'approve leave', 'generate payslip'],
                ],
                'coo' => [
                    'title' => 'Chief Operating Officer',
                    'permissions' => ['view dashboard', 'manage operations', 'review reports'],
                ],
                'executive_assistant' => [
                    'title' => 'Executive Assistant',
                    'permissions' => ['view dashboard', 'schedule appointment', 'manage calendar'],
                ],
            ],
        ],

        'hr' => [
            'title' => 'Human Resources',
            'sub_roles' => [
                'human_resource_officer' => [
                    'title' => 'Human Resource Officer',
                    'permissions' => ['view dashboard', 'manage employee', 'approve leave', 'view reports'],
                ],
                'head_training' => [
                    'title' => 'Head Training and Education',
                    'permissions' => ['assign training', 'view dashboard', 'review performance'],
                ],
                'education_support' => [
                    'title' => 'Education and Training Support',
                    'permissions' => ['view dashboard', 'assist training', 'view reports'],
                ],
            ],
        ],

        'it' => [
            'title' => 'IT Department',
            'sub_roles' => [
                'web_developer' => [
                    'title' => 'Web Developer',
                    'permissions' => ['deploy code', 'view dashboard', 'manage website'],
                ],
                'software_engineer' => [
                    'title' => 'Software Engineer',
                    'permissions' => ['develop software', 'view dashboard', 'manage database'],
                ],
                'ux_ui_designer' => [
                    'title' => 'UX/UI Designer',
                    'permissions' => ['design interface', 'view dashboard', 'review usability'],
                ],
                'systems_administrator' => [
                    'title' => 'Systems Administrator',
                    'permissions' => ['manage roles', 'system security', 'backups', 'view dashboard'],
                ],
                'technical_assistant' => [
                    'title' => 'Technical Assistant',
                    'permissions' => ['support IT tasks', 'view dashboard'],
                ],
            ],
        ],

        'finance' => [
            'title' => 'Finance',
            'sub_roles' => [
                'accountant' => [
                    'title' => 'Accountant',
                    'permissions' => ['manage payroll', 'generate payslip', 'view dashboard'],
                ],
                'loans_manager' => [
                    'title' => 'Loans Manager',
                    'permissions' => ['approve loans', 'view dashboard', 'manage client loans'],
                ],
            ],
        ],

        'business' => [
            'title' => 'Business & Strategy',
            'sub_roles' => [
                'business_dev_manager' => [
                    'title' => 'Business Development Manager',
                    'permissions' => ['view dashboard', 'assign tasks', 'review strategy'],
                ],
                'strategy_officer' => [
                    'title' => 'Strategy and Business Planning Officer',
                    'permissions' => ['plan strategy', 'view dashboard', 'generate reports'],
                ],
                'client_service_officer' => [
                    'title' => 'Client Service Officer',
                    'permissions' => ['manage clients', 'view dashboard', 'generate client reports'],
                ],
            ],
        ],

        'staff' => [
            'title' => 'Staff Members',
            'sub_roles' => [
                'employee' => [
                    'title' => 'Employee',
                    'permissions' => ['submit reports', 'apply for leave', 'view payslips', 'view dashboard', 'view reports', 'view leave'],
                ],
            ],
        ],

        'receptionist' => [
            'title' => 'Reception Desk',
            'sub_roles' => [
                'front_desk' => [
                    'title' => 'Front Desk Receptionist',
                    'permissions' => ['view dashboard', 'register visitor', 'check in visitor', 'check out visitor', 'schedule appointment', 'view visitor list', 'print visitor badge'],
                ],
            ],
        ],

        'security' => [
            'title' => 'Security',
            'sub_roles' => [
                'security_officer' => [
                    'title' => 'Security Officer',
                    'permissions' => ['view dashboard', 'view visitor list', 'verify visitor check-in', 'flag visitor', 'view emergency log', 'monitor overstays'],
                ],
            ],
        ],
    ],

];
