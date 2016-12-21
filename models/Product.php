<?php namespace Octoshop\Core\Models;

use Model;
use Carbon\Carbon;

class Product extends Model
{
    use \Octoshop\Core\Util\UrlMaker;
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'octoshop_products';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules
     */
    protected $rules = [
        'title' => ['required', 'between:4,255'],
        'slug' => [
            'required',
            'alpha_dash',
            'between:1,255',
            'unique:octoshop_products'
        ],
        'price' => ['numeric', 'max:99999999.99', 'min:0'],
    ];

    /**
     * @var array Attributes to mutate as dates
     */
    protected $dates = ['available_at', 'created_at', 'updated_at'];

    /**
     * @var array Image attachments
     */
    public $attachMany = [
        'images' => ['System\Models\File']
    ];

    protected $urlComponentName = 'shopProduct';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setUrlPageName('product.htm');
    }

    public function canBePurchased()
    {
        if ((bool) $this->attributes['is_enabled'] === false) {
            return false;
        }

        switch ((int) $this->attributes['is_available']) {
            case 2:
                $date = $this->attributes['available_at'];

                return Carbon::now()->gt(new Carbon($date));
            case 1:
                return true;
            default:
                return false;
        }
    }

    public function cannotBePurchased()
    {
        return !$this->canBePurchased();
    }

    public function isComingSoon()
    {
        $available = (int) $this->attributes['is_available'];

        if ($available !== 2 || !isset($this->attributes['available_at'])) {
            return false;
        }

        $date = $this->attributes['available_at'];

        return Carbon::now()->lt(new Carbon($date));
    }


    #############################################################
    # Search scopes                                             #
    #############################################################

    public function scopeFindBySlug($query, $slug)
    {
        return $query->whereSlug($slug);
    }

    public function scopeEnabled($query)
    {
        return $query->whereIsEnabled(true);
    }

    public function scopeVisible($query)
    {
        return $query->enabled()->whereIsVisible(true);
    }

    public function scopeAvailable($query)
    {
        return $query->enabled()
                     ->where('is_available', '>', '0')
                     ->orWhere('available_at', '<=', Carbon::now());
    }


    #############################################################
    # Single object getters                                     #
    #############################################################

    public function scopeFirstEnabled($query)
    {
        return $query->enabled()->firstOrFail();
    }

    public function scopeFirstAvailable($query)
    {
        return $query->available()->firstOrFail();
    }

    public function scopeFirstWithImages($query)
    {
        return $query->withImages()->firstOrFail();
    }

    public function scopeFirstEnabledWithImages($query)
    {
        return $query->enabled()->firstWithImages();
    }

    public function scopeFirstAvailableWithImages($query)
    {
        return $query->available()->firstWithImages();
    }


    #############################################################
    # Collection getters                                        #
    #############################################################

    public function scopeAllEnabled($query)
    {
        return $query->enabled()->get();
    }

    public function scopeAllAvailable($query)
    {
        return $query->available()->get();
    }

    public function scopeAllWithImages($query)
    {
        return $query->withImages()->get();
    }

    public function scopeAllEnabledWithImages($query)
    {
        return $query->enabled()->allWithImages();
    }

    public function scopeAllVisibleWithImages($query)
    {
        return $query->visible()->allWithImages();
    }

    public function scopeAllAvailableWithImages($query)
    {
        return $query->available()->allWithImages();
    }


    #############################################################
    # Eager load helpers                                        #
    #############################################################

    public function scopeWithImages($query)
    {
        return $query->with(['images' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);
    }
}
