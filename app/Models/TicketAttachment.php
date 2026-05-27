<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    use HasFactory;

    # region variables
    protected $guarded = [];

    public $timestamps = false;
    # endregion

    # region constants
    const FILE_PATH = 'files/ticket/attachments/';
    const MAX_SIZE = 5000;
    # endregion

    # region relations
    public function message()
    {
        return $this->belongsTo(TicketMessage::class);
    }
    # endregion

    # region methods
    public function file()
    {
        return asset(self::FILE_PATH.$this->file);
    }

    public function getFileSize()
    {
        if ($this->getFilePath() === 0) {
            return '0 KB';
        }

        if (filesize($this->getFilePath()) < 1024 * 1024) {
            return round(filesize($this->getFilePath()) / 1024, 2) . ' KB';
        }

        if (filesize($this->getFilePath()) < 1024 * 1024 * 1024) {
            return round(filesize($this->getFilePath()) / 1024 / 1024, 2) . ' MB';
        }

        return round(filesize($this->getFilePath()) / 1024 / 1024 / 1024, 2) . ' GB';
    }
    # endregion
}
