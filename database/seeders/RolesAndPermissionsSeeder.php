<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all = [
            [
                'module' => "Dashboard",
                'permissions' => [
                    'dashboard.show',
                    'dashboard.edit',
                ],
                'name' => [
                    'Show Dashboard',
                    'Edit Dashboard',
                ]
            ],
            [
                'module' => "User",
                'permissions' => [
                    'user.show',
                    'user.index',
                    'user.create',
                    'user.edit',
                    'user.delete',
                    'user.loginas',
                ],
                'name' => [
                    'Show User',
                    'List User',
                    'Create User',
                    'Edit User',
                    'Delete User',
                    'Login As User',
                ]
            ],
            [
                'module' => "Authorizations",
                'permissions' => [
                    'authorization.index',
                    'authorization.create',
                    'authorization.edit',
                    'authorization.delete'
                ],
                'name' => [
                    'List Authorization',
                    'Create Authorization',
                    'Edit Authorization',
                    'Delete Authorization'
                ]
            ],
            [
                'module' => "Leads",
                'permissions' => [
                    'leads.show',
                    'leads.index',
                    'leads.ownonly',
                    'leads.create',
                    'leads.edit',
                    'leads.delete',
                ],
                'name' => [
                    'Show Leads',
                    'List Leads',
                    'List Own Leads',
                    'Create Leads',
                    'Edit Leads',
                    'Delete Leads',
                ]
            ],
            [
                'module' => "Client",
                'permissions' => [
                    'client.show',
                    'client.index',
                    'client.ownonly',
                    'client.create',
                    'client.edit',
                    'client.delete',
                ],
                'name' => [
                    'Show Client',
                    'List Client',
                    'List Own Client',
                    'Create Client',
                    'Edit Client',
                    'Delete Client',
                ]
            ],
            [
                'module' => "Services",
                'permissions' => [
                    'services.show',
                    'services.index',
                    'services.create',
                    'services.edit',
                    'services.delete'
                ],
                'name' => [
                    'Show Services',
                    'List Services',
                    'Create Services',
                    'Edit Services',
                    'Delete Services'
                ]
            ],
            [
                'module' => "Packages",
                'permissions' => [
                    'packages.create',
                    'packages.edit',
                    'packages.delete'
                ],
                'name' => [
                    'Create Packages',
                    'Edit Packages',
                    'Delete Packages'
                ]
            ],
            [
                'module' => "Features",
                'permissions' => [
                    'features.create',
                    'features.edit',
                    'features.delete'
                ],
                'name' => [
                    'Create Features',
                    'Edit Features',
                    'Delete Features'
                ]
            ],
            [
                'module' => "Quotation",
                'permissions' => [
                    'quotation.show',
                    'quotation.index',
                    'quotation.ownonly',
                    'quotation.create',
                    'quotation.edit',
                    'quotation.delete',
                ],
                'name' => [
                    'Show Quotation',
                    'List Quotation',
                    'Own Quotation',
                    'Create Quotation',
                    'Edit Quotation',
                    'Delete Quotation',
                ]
            ],
            [
                'module' => "Invoice",
                'permissions' => [
                    'invoice.show',
                    'invoice.index',
                    'invoice.ownonly',
                    'invoice.create',
                    'invoice.edit',
                    'invoice.delete'
                ],
                'name' => [
                    'Show Invoice',
                    'List Invoice',
                    'Own Invoice',
                    'Create Invoice',
                    'Edit Invoice',
                    'Delete Invoice'
                ]
            ],
            [
                'module' => "Project",
                'permissions' => [
                    'project.show',
                    'project.index',
                    'project.employees',
                    'project.create',
                    'project.edit',
                    'project.delete',
                    'project.budget',
                    'project.credentials',
                    'project.clients',
                    'project.attachment',
                ],
                'name' => [
                    'Show Project',
                    'List Project',
                    'List Own Project',
                    'Create Project',
                    'Edit Project',
                    'Delete Project',
                    'Show Budget',
                    'Show Credentials',
                    'Client Details',
                    'Show Attachments'
                ]
            ],
            [
                'module' => "Expanse",
                'permissions' => [
                    'expanse.show',
                    'expanse.index',
                    'expanse.create',
                    'expanse.edit',
                    'expanse.delete'
                ],
                'name' => [
                    'Show Expanse',
                    'List Expanse',
                    'Create Expanse',
                    'Edit Expanse',
                    'Delete Expanse'
                ]
            ],
            [
                'module' => "Note",
                'permissions' => [
                    'note.show',
                    'note.index',
                    'note.ownonly',
                    'note.create',
                    'note.edit',
                    'note.delete',
                    'note.category'
                ],
                'name' => [
                    'Show Note',
                    'List Note',
                    'List Own Note',
                    'Create Note',
                    'Edit Note',
                    'Delete Note',
                    'Mange Note Category'
                ]
            ],
            [
                'module' => "Method",
                'permissions' => [
                    'method.show',
                    'method.index',
                    'method.create',
                    'method.edit',
                    'method.delete'
                ],
                'name' => [
                    'Show Method',
                    'List Method',
                    'Create Method',
                    'Edit Method',
                    'Delete Method'
                ]
            ],
            [
                'module' => "Purpose",
                'permissions' => [
                    'purpose.show',
                    'purpose.index',
                    'purpose.create',
                    'purpose.edit',
                    'purpose.delete'
                ],
                'name' => [
                    'Show Purpose',
                    'List Purpose',
                    'Create Purpose',
                    'Edit Purpose',
                    'Delete Purpose'
                ]
            ],
            [
                'module' => "Transaction",
                'permissions' => [
                    'transaction.index',
                    'transaction.own',
                    'transaction.export'
                ],
                'name' => [
                    'List Transaction',
                    'List Own Transaction',
                    'Export Transaction',
                ]
            ],
            [
                'module' => "Settings",
                'permissions' => [
                    'settings.show'
                ],
                'name' => [
                    'Show Settings',
                ]
            ],
            [
                'module' => "Old Data",
                'permissions' => [
                    'settings.old_data'
                ],
                'name' => [
                    'Show Old Data',
                ]
            ],
            [
                'module' => "Activity Log",
                'permissions' => [
                    'settings.activity_log'
                ],
                'name' => [
                    'Show Old Data',
                ]
            ],

            [
                'module' => "Due Transaction",
                'permissions' => [
                    'duetrx.index',
                    'duetrx.own',
                ],
                'name' => [
                    'List Due Transaction',
                    'List Own Due Transaction',
                ]
            ],
        ];

        foreach ($all as $item) {
            $module = Module::updateOrCreate([
                'module_name' => $item['module'],
                'slug' => Str::slug($item['module']),
            ]);

            foreach ($item['permissions'] as $key => $permission){
                Permission::updateOrCreate([
                    'name' => $permission,
                    'show_name' => $item['name'][$key],
                    'module_name' => $item['module'],
                    'module_id' => $module->id,
                ]);
            }
        };

        Role::create([
            'name' => 'Administrator', 'is_delete' => false
        ],
            [
                'name' => 'Customer', 'is_delete' => false
            ]);


        User::updateOrCreate([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt(12345678),
        ])->assignRole('Administrator');

        $developer = Role::updateOrCreate(['name' => 'Developer']);
        foreach ($all as $item) {
            foreach ($item['permissions'] as $permission) {
                $developer->givePermissionTo( $permission );
            }
        };

        User::updateOrCreate([
            'name' => 'Jugol Kumar',
            'email' => 'jugol@creativetechpark.com',
            'password' => bcrypt(12345678),
        ])->assignRole('Developer');

        User::updateOrCreate([
        'name' => 'Creative Tech Park',
            'email' => 'info@creativetechpark.com',
            'password' => bcrypt('creativetechpark'),
        ])->assignRole('Administrator');

    }
}

