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
                    'user.delete'
                ],
                'name' => [
                    'Show User',
                    'Manage User',
                    'Create User',
                    'Edit User',
                    'Delete User'
                ]
            ],
            [
                'module' => "Authorizations",
                'permissions' => [
                    'authorization.show',
                    'authorization.index',
                    'authorization.create',
                    'authorization.edit',
                    'authorization.delete'
                ],
                'name' => [
                    'Show Authorizations',
                    'Manage Authorizations',
                    'Create Authorizations',
                    'Edit Authorizations',
                    'Delete Authorizations'
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
                    'leads.download'
                ],
                'name' => [
                    'Show Leads',
                    'Manage Leads',
                    'Manage Own Leads',
                    'Create Leads',
                    'Edit Leads',
                    'Delete Leads',
                    'Download Leads'
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
                    'Manage Client',
                    'Manage Own Client',
                    'Create Client',
                    'Edit Client',
                    'Delete Client',
                ]
            ],
            [
                'module' => "Platform",
                'permissions' => [
                    'platform.show',
                    'platform.index',
                    'platform.create',
                    'platform.edit',
                    'platform.delete'
                ],
                'name' => [
                    'Show Platform',
                    'Manage Platform',
                    'Create Platform',
                    'Edit Platform',
                    'Delete Platform'
                ]
            ],
            [
                'module' => "Packages",
                'permissions' => [
                    'packages.show',
                    'packages.index',
                    'packages.create',
                    'packages.edit',
                    'packages.delete'
                ],
                'name' => [
                    'Show Packages',
                    'Manage Packages',
                    'Create Packages',
                    'Edit Packages',
                    'Delete Packages'
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
                    'Manage Services',
                    'Create Services',
                    'Edit Services',
                    'Delete Services'
                ]
            ],
            [
                'module' => "Quotation",
                'permissions' => [
                    'quotation.show',
                    'quotation.index',
                    'quotation.create',
                    'quotation.edit',
                    'quotation.delete',
                    'quotation.invoice'
                ],
                'name' => [
                    'Show Quotation',
                    'Manage Quotation',
                    'Create Quotation',
                    'Edit Quotation',
                    'Delete Quotation',
                    'Invoice Quotation'
                ]
            ],
            [
                'module' => "Invoice",
                'permissions' => [
                    'invoice.show',
                    'invoice.index',
                    'invoice.create',
                    'invoice.edit',
                    'invoice.delete'
                ],
                'name' => [
                    'Show Invoice',
                    'Manage Invoice',
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
                    'project.create',
                    'project.edit',
                    'project.delete',
                    'project.budget',
                    'project.employees',
                ],
                'name' => [
                    'Show Project',
                    'Manage Project',
                    'Create Project',
                    'Edit Project',
                    'Delete Project',
                    'Show Budget',
                    'Employees Project'
                ]
            ],
            [
                'module' => "Note",
                'permissions' => [
                    'note.show',
                    'note.index',
                    'note.create',
                    'note.edit',
                    'note.delete',
                    'note.employees'
                ],
                'name' => [
                    'Show Note',
                    'Manage Note',
                    'Create Note',
                    'Edit Note',
                    'Delete Note',
                    'Show Employees'
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
                    'Manage Expanse',
                    'Create Expanse',
                    'Edit Expanse',
                    'Delete Expanse'
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
                    'Manage Method',
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
                    'Manage Purpose',
                    'Create Purpose',
                    'Edit Purpose',
                    'Delete Purpose'
                ]
            ],
            [
                'module' => "Transaction",
                'permissions' => [
                    'transaction.show',
                    'transaction.index',
                    'transaction.create',
                    'transaction.edit',
                    'transaction.delete'
                ],
                'name' => [
                    'Show Transaction',
                    'Manage Transaction',
                    'Create Transaction',
                    'Edit Transaction',
                    'Delete Transaction'
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
            ]
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

        Role::updateOrCreate(['name' => 'Administrator', 'is_delete' => false]);


//        User::updateOrCreate([
//            'name' => 'admin',
//            'email' => 'admin@admin.com',
//            'password' => bcrypt(12345678),
//        ])->assignRole('Administrator');

        $developer = Role::updateOrCreate(['name' => 'Developer']);
        foreach ($all as $item) {
            foreach ($item['permissions'] as $permission) {
                $developer->givePermissionTo( $permission );
            }
        };

//        User::updateOrCreate([
//            'name' => 'Jugol Kumar',
//            'email' => 'jugol@creativetechpark.com',
//            'password' => bcrypt(12345678),
//        ])->assignRole('Developer');
//
//        User::updateOrCreate([
//        'name' => 'Creative Tech Park',
//            'email' => 'info@creativetechpark.com',
//            'password' => bcrypt('creativetechpark'),
//        ])->assignRole('Administrator');


    }
}
