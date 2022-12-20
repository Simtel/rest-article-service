<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Article newModelQuery()
 * @method static Builder|Article newQuery()
 * @method static Builder|Article query()
 * @method static Builder|Article whereCreatedAt($value)
 * @method static Builder|Article whereId($value)
 * @method static Builder|Article whereName($value)
 * @method static Builder|Article whereUpdatedAt($value)
 * @property-read Collection|Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Database\Factories\ArticleFactory factory(...$parameters)
 * @method static Builder|Article withAllTags(array $tagsIds)
 */
class Article extends Model
{
    use HasFactory;
    /**
     * @var string[]
     */
    protected $fillable = [
        'name'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return BelongsToMany<Tag>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tags_articles', 'article_id', 'tag_id');
    }

    /**
     * @param  Builder<Article>  $query
     * @param  array  $tagsIds
     *
     * @return Builder<Article>
     */
    public function scopeWithAllTags(Builder $query, array $tagsIds): Builder
    {
        $tags = Tag::findMany($tagsIds);

        collect($tags)->each(function ($tag) use ($query) {
            $query->whereHas('tags', function (Builder $query) use ($tag) {
                $query->where('tags.id', $tag->id ?? 0);
            });
        });

        return $query;
    }
}
