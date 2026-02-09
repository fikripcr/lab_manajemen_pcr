<?php
namespace App\Models\Pemutu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $table      = 'pemutu_label';
    protected $primaryKey = 'label_id';
    protected $fillable   = ['type_id', 'name', 'slug', 'description'];
    public $timestamps    = false; // Migration doesn't have timestamps for this table? checking migration...
                                   // Migration: table 'label' has no timestamps defined in schema, only label_types has.
                                   // Wait, let me check the migration content again.
                                   // 2026_02_07_011017_create_table_pemutuv1.php:
                                   // Schema::create('label', ... function(Blueprint $table) { ... $table->timestamps(); IS MISSING in the snippet I saw?
                                   // Let's re-read the migration file snippet for 'label'.
                                   // line 31: Schema::create('label'...
                                   // line 39: }); // No timestamps() call relative to label_types which has it on line 21.

    // Relationships
    public function type()
    {
        return $this->belongsTo(LabelType::class, 'type_id', 'labeltype_id');
    }
}
