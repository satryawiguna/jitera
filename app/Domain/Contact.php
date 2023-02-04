<?php

namespace App\Domain;

use App\Core\Domain\BaseEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *      schema="ContactModel",
 *      description="Contact model schema",
 *      type="object"
 * )
 */

class Contact extends BaseEntity
{
    use HasFactory, SoftDeletes;

    /**
     * @OA\Property(
     *      property="NickName",
     *      title="nick_name",
     *      description="Nick name",
     *      example="Satrya",
     *      type="string"
     * )
     */
    private string $nick_name;

    protected $fillable = [
        'contactable_id',
        'contactable_type',
        'full_name',
        'nick_name',
        'country',
        'state',
        'city',
        'address',
        'post_code',
        'mobile'
    ];

    protected $keyType = 'string';

    protected $dates = ['deleted_at'];

    public $incrementing = false;

    public static function boot(){
        parent::boot();

        static::creating(function ($contact) {
            $contact->id = Str::uuid(36);
        });
    }

    public function contactable()
    {
        return $this->morphTo();
    }

    /**
     * @OA\Property(
     *      property="NickName",
     *      title="nick_name",
     *      description="Nick name",
     *      example="Satrya",
     *      type="string"
     * )
     */

    /**
     * @OA\Property(
     *      property="Country",
     *      title="country",
     *      description="Country",
     *      example="Indonesia",
     *      type="string"
     * )
     */

    /**
     * @OA\Property(
     *      property="State",
     *      title="state",
     *      description="State",
     *      example="Bali",
     *      type="string"
     * )
     */

    /**
     * @OA\Property(
     *      property="City",
     *      title="city",
     *      description="City",
     *      example="Gianyar",
     *      type="string"
     * )
     */

    /**
     * @OA\Property(
     *      property="Address",
     *      title="address",
     *      description="Address",
     *      example="Jl. Kresna No 1",
     *      type="string"
     * )
     */

    /**
     * @OA\Property(
     *      property="Postcode",
     *      title="postcode",
     *      description="Postcode",
     *      example="12345",
     *      type="string"
     * )
     */

    /**
     * @OA\Property(
     *      property="Mobile",
     *      title="mobile",
     *      description="Mobile",
     *      example="0811223344",
     *      type="string"
     * )
     */
}
