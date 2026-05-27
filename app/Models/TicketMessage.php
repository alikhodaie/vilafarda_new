<?php

namespace App\Models;

use App\Classes\Traits\PersianDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;

class TicketMessage extends Model
{
    use HasFactory, PersianDate, SoftDeletes;

    # region variables
    protected $guarded = [];

    public $timestamps = true;

    protected $touches = ['ticket'];
    # endregion

    # region constants
    const ADMIN = 'admin';
    const USER = 'user';

    const SENT_FROM = [
        self::ADMIN => [
            'value' => self::ADMIN,
            'text' => 'ادمین'
        ],
        self::USER => [
            'value' => self::USER,
            'text' => 'کاربر',
        ],
    ];
    # endregion

    # region scope
    # endregion

    # region relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
    # endregion

    # region methods
    public function sentFrom($parameter = 'text')
    {
        return self::SENT_FROM[$this->sent_from][$parameter];
    }

    public function saveAttachment(UploadedFile $file)
    {
        $file_name = $this->id.rand(1000, 9999). '-' .$file->getClientOriginalName();
        $file->storeAs(TicketAttachment::FILE_PATH, $file_name);

        $this->attachments()->create(['file' => $file_name]);
    }
    # endregion
}
