<?php

declare(strict_types=1);

namespace Velor\Navigation\Models;

use App\Concerns\Models\HasRowOrdering;
use App\Concerns\Models\SortTranslations;
use App\Concerns\Models\UsesAudit;
use App\Contracts\Models\RowOrderableInterface;
use App\Contracts\Models\TranslatableInterface;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Translatable\HasTranslations;
use Spatie\Translatable\Translatable;

/**
 * @mixin \Eloquent
 */
class NavigationItem extends AbstractModel implements RowOrderableInterface, TranslatableInterface
{
    /** @use HasFactory<\Velor\Navigation\Database\Factories\NavigationItemFactory> */
    use HasFactory;
    use HasRowOrdering;
    use HasTranslations;
    use HasUuids;
    use Sortable;
    use SortTranslations;
    use UsesAudit;

    /**
     * @var array<int, string>
     */
    public array $translatable = [
        'text',
        'url',
    ];

    /**
     * @var array<int, string>
     */
    public array $sortable = [
        'is_active',
        'text',
        'url',
        'sort_order',
    ];

    /**
     * @var array<int, string>
     */
    protected array $audit = [
        'navigation_id',
        'is_active',
        'text',
        'url',
        'sort_order',
    ];

    /**
     * @var array<int, string>
     */
    protected array $rowOrderScopeColumns = [
        'navigation_id',
    ];

    protected $fillable = [
        'navigation_id',
        'is_active',
        'text',
        'url',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'text'       => Translatable::class,
            'url'        => Translatable::class,
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Navigation, $this>
     */
    public function navigation(): BelongsTo
    {
        return $this->belongsTo(Navigation::class);
    }

    /**
     * @param Builder<AbstractModel> $query
     * @param string $direction
     * @return Builder<AbstractModel>
     */
    public function textSortable(Builder $query, string $direction): Builder
    {
        return $this->sortableTranslation($query, $direction, 'text');
    }

    /**
     * @param Builder<AbstractModel> $query
     * @param string $direction
     * @return Builder<AbstractModel>
     */
    public function urlSortable(Builder $query, string $direction): Builder
    {
        return $this->sortableTranslation($query, $direction, 'url');
    }
}
