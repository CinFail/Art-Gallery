<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\ArtworkGroup;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        
        // Permissions
        
        $permissions = [
            // Artists
            'view artists', 'manage artists',
            // Artworks
            'view artworks', 'manage artworks',
            // Groups
            'view groups', 'manage groups',
            // Customers
            'view customers', 'manage customers',
            // Users
            'manage users',
            // Roles
            'manage roles',
            // Activity Logs
            'view activity logs',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

       
        //  Roles
        
        $adminRole = Role::firstOrCreate([
            'name' => 'Administrator', 'guard_name' => 'web'
        ], ['description' => 'Full access to all features.']);

        $staffRole = Role::firstOrCreate([
            'name' => 'Staff', 'guard_name' => 'web'
        ], ['description' => 'Can manage records but not users or roles.']);

        $viewerRole = Role::firstOrCreate([
            'name' => 'Viewer', 'guard_name' => 'web'
        ], ['description' => 'Read-only access.']);

        // Administrator — all permissions
        $adminRole->syncPermissions(Permission::all());

        // Staff — manage records, NOT users/roles/logs
        $staffRole->syncPermissions([
            'view artists', 'manage artists',
            'view artworks', 'manage artworks',
            'view groups', 'manage groups',
            'view customers', 'manage customers',
            'view activity logs',
        ]);

        // Viewer — read-only
        $viewerRole->syncPermissions([
            'view artists', 'view artworks', 'view groups', 'view customers',
        ]);

        
        //  Users
        
        $admin = User::firstOrCreate(['email' => 'admin@agms.com'], [
            'name'     => 'System Administrator',
            'password' => 'password',
            'is_active'=> true,
        ]);
        $admin->syncRoles(['Administrator']);

        $staff = User::firstOrCreate(['email' => 'staff@agms.com'], [
            'name'     => 'Gallery Staff',
            'password' => 'password',
            'is_active'=> true,
        ]);
        $staff->syncRoles(['Staff']);

        $viewer = User::firstOrCreate(['email' => 'viewer@agms.com'], [
            'name'     => 'Gallery Viewer',
            'password' => 'password',
            'is_active'=> true,
        ]);
        $viewer->syncRoles(['Viewer']);

       
        // Sample Artists
        
        $artists = [
            ['name' => 'Leonardo da Vinci',  'birthplace' => 'Vinci, Italy',        'age' => 67, 'art_style' => 'High Renaissance'],
            ['name' => 'Vincent van Gogh',   'birthplace' => 'Zundert, Netherlands','age' => 37, 'art_style' => 'Post-Impressionism'],
            ['name' => 'Frida Kahlo',        'birthplace' => 'Coyoacán, Mexico',    'age' => 47, 'art_style' => 'Surrealism / Folk Art'],
            ['name' => 'Pablo Picasso',      'birthplace' => 'Málaga, Spain',       'age' => 91, 'art_style' => 'Cubism'],
            ['name' => 'Claude Monet',       'birthplace' => 'Paris, France',       'age' => 86, 'art_style' => 'Impressionism'],
        ];

        foreach ($artists as $data) {
            Artist::firstOrCreate(['name' => $data['name']], $data);
        }

   
        // Sample Groups
        
        $groups = [
            ['name' => 'Renaissance Masters',  'description' => 'Works from the Renaissance period.'],
            ['name' => 'Modern Art',           'description' => 'Art from the 20th century onwards.'],
            ['name' => 'Landscapes',           'description' => 'Paintings and photographs of natural scenery.'],
            ['name' => 'Portraits',            'description' => 'Artworks depicting human subjects.'],
            ['name' => 'Abstract',             'description' => 'Non-representational works.'],
        ];

        foreach ($groups as $data) {
            ArtworkGroup::firstOrCreate(['name' => $data['name']], $data);
        }

       
        //  Sample Artworks
       
        $artworkData = [
            [
                'artist' => 'Leonardo da Vinci',
                'title'  => 'The Vitruvian Study',
                'year'   => 1490,
                'type'   => 'Drawing',
                'price'  => 5000000.00,
                'groups' => ['Renaissance Masters', 'Portraits'],
            ],
            [
                'artist' => 'Vincent van Gogh',
                'title'  => 'Starry Fields at Dusk',
                'year'   => 1889,
                'type'   => 'Painting',
                'price'  => 8500000.00,
                'groups' => ['Modern Art', 'Landscapes'],
            ],
            [
                'artist' => 'Frida Kahlo',
                'title'  => 'Self Portrait with Flowers',
                'year'   => 1938,
                'type'   => 'Painting',
                'price'  => 3200000.00,
                'groups' => ['Modern Art', 'Portraits'],
            ],
            [
                'artist' => 'Claude Monet',
                'title'  => 'Water Lilies No. 12',
                'year'   => 1906,
                'type'   => 'Painting',
                'price'  => 7000000.00,
                'groups' => ['Landscapes', 'Impressionism'],
            ],
        ];

        foreach ($artworkData as $item) {
            $artist  = Artist::where('name', $item['artist'])->first();
            if (!$artist) continue;

            $artwork = Artwork::firstOrCreate(
                ['title' => $item['title']],
                [
                    'artist_id'    => $artist->id,
                    'year_created' => $item['year'],
                    'art_type'     => $item['type'],
                    'price'        => $item['price'],
                ]
            );

            $groupIds = ArtworkGroup::whereIn('name', $item['groups'])->pluck('id');
            $artwork->groups()->syncWithoutDetaching($groupIds);
        }

     
        //  Sample Customers
        
        $customers = [
            [
                'name'        => 'Alice Reyes',
                'address'     => '123 Rizal Ave, Manila, Philippines',
                'total_spent' => 250000.00,
                'artists'     => ['Vincent van Gogh', 'Claude Monet'],
                'groups'      => ['Landscapes', 'Modern Art'],
            ],
            [
                'name'        => 'Marco Dela Cruz',
                'address'     => '45 Ayala St, Makati, Philippines',
                'total_spent' => 80000.00,
                'artists'     => ['Frida Kahlo'],
                'groups'      => ['Portraits', 'Abstract'],
            ],
        ];

        foreach ($customers as $data) {
            $customer = Customer::firstOrCreate(
                ['name' => $data['name']],
                ['address' => $data['address'], 'total_spent' => $data['total_spent']]
            );

            $artistIds = Artist::whereIn('name', $data['artists'])->pluck('id');
            $groupIds  = ArtworkGroup::whereIn('name', $data['groups'])->pluck('id');
            $customer->preferredArtists()->syncWithoutDetaching($artistIds);
            $customer->preferredGroups()->syncWithoutDetaching($groupIds);
        }

        $this->command->info('✅ Seeding complete!');
        $this->command->info('   Admin:  admin@agms.com  / password');
        $this->command->info('   Staff:  staff@agms.com  / password');
        $this->command->info('   Viewer: viewer@agms.com / password');
    }
}
