<?php

declare(strict_types=1);

namespace Velor\Navigation\Models;

use App\Concerns\Models\UsesAudit;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;

/**
 * @mixin \Eloquent
 */
class Navigation extends AbstractModel
{
    /** @use HasFactory<\Velor\Navigation\Database\Factories\NavigationFactory> */
    use HasFactory;
    use HasUuids;
    use Sortable;
    use UsesAudit;

    /**
     * @var array<int, string>
     */
    protected array $audit = [
        'name',
    ];

    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany<NavigationItem, $this>
     */
    public function navigationItems(): HasMany
    {
        return $this->hasMany(NavigationItem::class);
    }
}
