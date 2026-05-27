<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{
    use HasFactory, PersianDate, SoftDeletes;

    # region variables
    protected $guarded = [];
    public $timestamps = true;

    # endregion

    # region constants
    const USER_ANSWERED = 'user-answered';
    const ADMIN_ANSWERED  = 'admin-answered';
    const PROCESSING  = 'processing';
    const CLOSED   = 'closed';

    const STATUS = [
        self::USER_ANSWERED => [
            'color'      => 'warning',
            'value'      => self::USER_ANSWERED,
            'fa_text'       => 'در انتظار پاسخ',
            'fa_text_admin' => 'پاسخ داده شده',
            'en_text'       => 'waiting for answer',
            'en_text_admin' => 'answered',
        ],
        self::PROCESSING => [
            'color'      => 'info',
            'value'      => self::PROCESSING,
            'fa_text'       => 'در حال بررسی',
            'fa_text_admin' => 'در انتظار بررسی',
            'en_text'       => 'checking',
            'en_text_admin' => 'waiting for check',
        ],
        self::ADMIN_ANSWERED => [
            'color'      => 'success',
            'value'      => self::ADMIN_ANSWERED,
            'fa_text'       => 'پاسخ داده شده',
            'fa_text_admin' => 'در انتظار پاسخ',
            'en_text'       => 'answered',
            'en_text_admin' => 'waiting for answer',
        ],
        self::CLOSED => [
            'color'      => 'danger',
            'value'      => self::CLOSED,
            'fa_text'       => 'بسته شده',
            'fa_text_admin' => 'بسته شده',
            'en_text'       => 'closed',
            'en_text_admin' => 'closed',
        ],
    ];
    # endregion

    # region scope
    public function scopeSearch($query)
    {
        if (request()->filled('id')){
            $query->where('id', request()->get('id'));
        }
        if (request()->filled('user')){
            $query->where('user_id', request()->get('user'));
        }
        if (request()->filled('title')){
            $query->where('title', 'LIKE', '%'.request()->get('title').'%');
        }
        if (request()->filled('status')){
            $query->where('status', request()->get('status'));
        }

        return $query;
    }
    # endregion

    # region relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }
    # endregion

    # region methods
    public static function getCancelDate(): Carbon
    {
        return now()->subDays(2);
    }

    public function status($parameter = 'fa_text')
    {
        return self::STATUS[$this->status][$parameter];
    }

    public function saveMessage(Request $request, $sent_from = TicketMessage::USER)
    {
        $message = $this->messages()->create(['content' => $request->message, 'sent_from' => $sent_from, 'user_id' => Auth()->id()]);

        if ($request->file('attachments')){
            $files = $request->file('attachments');

            foreach ($files as $file){
                $message->saveAttachment($file);
            }
        }
    }
    # endregion
}
