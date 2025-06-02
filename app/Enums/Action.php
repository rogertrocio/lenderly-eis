<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static ACCESS
 * @method static static VIEW
 * @method static static CREATE
 * @method static static UPDATE
 * @method static static DELETE
 */
final class Action extends Enum
{
    /**
     * Permission action to access list of data in module
     */
    #[Description('Access')]
    const ACCESS = 'access';

    /**
     * Permission action to view specific data in module
     */
    #[Description('View')]
    const VIEW = 'view';

    /**
     * Permission action to create new data in module
     */
    #[Description('Create')]
    const CREATE = 'create';

    /**
     * Permission action to update specific data in module
     */
    #[Description('Update')]
    const UPDATE = 'update';

    /**
     * Permission action to delete specific data in module
     */
    #[Description('Delete')]
    const DELETE = 'delete';
}
