<?php

class Access
{
    public static function resolveRole(array $user): string
    {
        $role = strtolower($user['role'] ?? 'guest');
        if ($role !== 'staff') {
            return $role;
        }

        $position = mb_strtolower(trim($user['chucvu'] ?? ''), 'UTF-8');
        if (in_array($position, ['nhân viên thủ kho', 'nhân viên kho'], true)) {
            return 'warehouse';
        }
        if (in_array($position, ['nhân viên bán hàng', 'nhân viên thu ngân & duyệt đơn', 'thu ngân'], true)) {
            return 'sales';
        }
        return 'staff';
    }

    public static function role(): string
    {
        return strtolower($_SESSION['user_role'] ?? 'guest');
    }

    public static function requireRoles(array $roles): void
    {
        if (!in_array(self::role(), $roles, true)) {
            http_response_code(403);
            exit('Bạn không có quyền truy cập chức năng này.');
        }
    }
}
