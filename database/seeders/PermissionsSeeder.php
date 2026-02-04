<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Bookings
            ['name' => 'view-bookings', 'category' => 'bookings', 'description' => 'View all bookings'],
            ['name' => 'edit-bookings', 'category' => 'bookings', 'description' => 'Edit booking details'],
            ['name' => 'cancel-bookings', 'category' => 'bookings', 'description' => 'Cancel bookings'],
            
            // Payments
            ['name' => 'view-payments', 'category' => 'payments', 'description' => 'View payment history'],
            ['name' => 'verify-payments', 'category' => 'payments', 'description' => 'Verify manual payments'],
            ['name' => 'reconcile-payments', 'category' => 'payments', 'description' => 'Access reconciliation dashboard'],
            
            // Refunds
            ['name' => 'view-refunds', 'category' => 'refunds', 'description' => 'View refund requests'],
            ['name' => 'approve-refunds', 'category' => 'refunds', 'description' => 'Approve/reject refunds'],
            ['name' => 'process-refunds', 'category' => 'refunds', 'description' => 'Process approved refunds'],
            
            // Users
            ['name' => 'view-users', 'category' => 'users', 'description' => 'View user list'],
            ['name' => 'edit-users', 'category' => 'users', 'description' => 'Edit user details'],
            ['name' => 'manage-roles', 'category' => 'users', 'description' => 'Change user roles'],
            ['name' => 'verify-kyc', 'category' => 'users', 'description' => 'Verify user KYC'],
            
            // Properties
            ['name' => 'view-properties', 'category' => 'properties', 'description' => 'View properties'],
            ['name' => 'edit-properties', 'category' => 'properties', 'description' => 'Edit property details'],
            ['name' => 'manage-photos', 'category' => 'properties', 'description' => 'Upload/delete property photos'],
            
            // Support
            ['name' => 'view-tickets', 'category' => 'support', 'description' => 'View support tickets'],
            ['name' => 'reply-tickets', 'category' => 'support', 'description' => 'Reply to tickets'],
            ['name' => 'assign-tickets', 'category' => 'support', 'description' => 'Assign tickets to staff'],
            ['name' => 'escalate-tickets', 'category' => 'support', 'description' => 'Escalate tickets'],
            
            // Payouts
            ['name' => 'view-payouts', 'category' => 'payouts', 'description' => 'View payout requests'],
            ['name' => 'approve-payouts', 'category' => 'payouts', 'description' => 'Approve payouts'],
            ['name' => 'process-payouts', 'category' => 'payouts', 'description' => 'Process approved payouts'],
            
            // Reports
            ['name' => 'view-reports', 'category' => 'reports', 'description' => 'View analytics and reports'],
            ['name' => 'export-reports', 'category' => 'reports', 'description' => 'Export report data'],
            
            // Audit
            ['name' => 'view-audit-logs', 'category' => 'audit', 'description' => 'View audit logs'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore($permission);
        }

        // Admin permissions (all permissions)
        $adminPermissions = DB::table('permissions')->pluck('id');
        foreach ($adminPermissions as $permissionId) {
            DB::table('role_permissions')->insertOrIgnore([
                'role' => 'admin',
                'permission_id' => $permissionId,
            ]);
        }

        // Staff permissions (limited)
        $staffPermissionNames = [
            'view-bookings',
            'view-payments',
            'verify-payments',
            'view-tickets',
            'reply-tickets',
            'view-properties',
        ];

        $staffPermissions = DB::table('permissions')
            ->whereIn('name', $staffPermissionNames)
            ->pluck('id');

        foreach ($staffPermissions as $permissionId) {
            DB::table('role_permissions')->insertOrIgnore([
                'role' => 'staff',
                'permission_id' => $permissionId,
            ]);
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
