<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // System Admin
            'manage_users', 'access_reports', 'manage_roles', 'system_settings','view_all_bookings', 'view_all_payments',

            // Flight Agent
            'search_flights', 'create_flight_bookings', 'send_flight_tickets',
            'edit_flight_bookings', 'cancel_flight_bookings', 'view_flight_bookings',

            // Hotel Agent
            'search_hotels', 'create_hotel_bookings', 'send_hotel_confirmations',
            'edit_hotel_bookings', 'cancel_hotel_bookings', 'view_hotel_bookings',

            // Finance
            'manage_payments', 'record_payments', 'generate_invoices', 'view_financial_reports',

            // End Customer
            'view_own_bookings', 'make_new_bookings', 'cancel_own_bookings',
            'view_invoices', 'update_profile',
        ];
        foreach($permissions as $permission){
            Permission::firstOrCreate(['name'=>$permission,'guard_name'=>'api']);
        }
    }
}
