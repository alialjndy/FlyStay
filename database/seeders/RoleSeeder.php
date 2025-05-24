<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name'=>'admin','guard_name'=>'api']);
        $admin->syncPermissions(Permission::all());

        $flight_agent = Role::create(['name'=>'flight_agent','guard_name'=>'api']);
        $flight_agent->syncPermissions(
            Permission::whereIn('name',[
                'search_flights',
                'create_flight_bookings',
                'send_flight_tickets',
                'edit_flight_bookings',
                'cancel_flight_bookings',
                'view_flight_bookings',
            ])->get());

        $hotel_agent = Role::create(['name'=>'hotel_agent','guard_name'=>'api']);
        $hotel_agent->syncPermissions(Permission::whereIn('name',[
            'search_hotels',
            'create_hotel_bookings',
            'send_hotel_confirmations',
            'edit_hotel_bookings',
            'cancel_hotel_bookings',
            'view_hotel_bookings',
        ])->get());

        $finance_officer = Role::create(['name'=>'finance_officer','guard_name'=>'api']);
        $finance_officer->syncPermissions(Permission::whereIn('name',[
            'manage_payments',
            'record_payments',
            'generate_invoices',
            'view_financial_reports',
        ])->get());

        $customer = Role::create(['name'=>'customer','guard_name'=>'api']);
        $customer->syncPermissions(Permission::whereIn('name',[
            'view_own_bookings',
            'make_new_bookings',
            'cancel_own_bookings',
            'view_invoices',
            'update_profile',
        ])->get());
    }
}
