<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static DASHBOARD
 * @method static static USER
 */
final class Module extends Enum
{
    /**
     * Dashbords's module in the application
     */
    #[Description('Dashboard')]
    const DASHBOARD = 'dashboard';

    /**
     * User's module in the application
     */
    #[Description('User')]
    const USER = 'user';
}
