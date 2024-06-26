<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Http\Controllers\VideoController;

class Video extends Model
{
    use HasFactory;

    public function getId(): string {
        return VideoController::alphaId($this->id);
    }

    public function owner(): HasOne {
        return $this->hasOne(User::class, "id", "owner_id");
    }

    public function thumbnail(): HasOne {
        return $this->hasOne(Thumbnail::class, "id", "thumbnail_id");
    }

    public function likes(): HasMany {
        return $this->hasMany(VideoLike::class, "video_id", "id");
    }

    public function getLikes() {
        $likeRows = $this->hasMany(VideoLike::class)->get();
        $likeAmount = 0;
        $likeAmount = $likeRows->filter(function($row) {
            return $row->liked == 1;
        })->count();
        return $likeAmount;
    }

    public function getDislikes() {
        $dislikeRows = $this->hasMany(VideoLike::class)->get();
        $dislikeAmount = 0;
        $dislikeAmount = $dislikeRows->filter(function($row) {
            return $row->liked == 0;
        })->count();
        return $dislikeAmount;
    }

    public function getViews() {
        $viewRows = $this->hasMany(VideoView::class)->get();
        $viewAmount = 0;
        foreach ($viewRows as $row) {
            $viewAmount += $row->amount;
        }
        return $viewAmount;
    }
}
