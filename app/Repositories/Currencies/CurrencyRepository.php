<?php
namespace App\Repositories\Currencies;

use DateTime;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Currencies\CurrencyRepositoryInterface;


/**
 * Class BookingsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CurrencyRepository extends BaseRepository implements CurrencyRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Currency::class;
    }


    public function createCurrency(array $data): Currency
    {
       return  $this->create($data);
    }

    public function populateCurrencies(array $params)
    {
        // Ensure the currency doesn't already exist
        if ($this->find($params['code'], null) !== null) {
            return 'exists';
        }

        // Created at stamp
        $created = new DateTime('now');

        $params = array_merge([
            'name' => '',
            'code' => '',
            'symbol' => '',
            'format' => '',
            'exchange_rate' => 1,
            'active' => 1,
            'created_at' => $created,
            'updated_at' => $created,
        ], $params);

        return $this->insert($params);
    }

    /**
     * {@inheritdoc}
     */
    public function allCurrencies()
    {
        $collection = new Collection($this->get());

        return $collection->keyBy('code')
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => strtoupper($item->code),
                    'symbol' => $item->symbol,
                    'format' => $item->format,
                    'exchange_rate' => $item->exchange_rate,
                    'active' => $item->active,
                    'created_at' => $item->updated_at,
                    'updated_at' => $item->updated_at,
                ];
            })
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function find($code, $active = 1)
    {
        $query = $this->where('code', strtoupper($code));

        // Make active optional
        if (is_null($active) === false) {
            $query->where('active', $active);
        }

        return $query->first();
    }

    /**
     * {@inheritdoc}
     */
    public function updateCurrency($code, array $attributes, DateTime $timestamp = null)
    {
        $table = $this->config('table');

        // Create timestamp
        if (empty($attributes['updated_at']) === true) {
            $attributes['updated_at'] = new DateTime('now');
        }

        return $this
            ->where('code', strtoupper($code))
            ->update($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($code)
    {
        $table = $this->config('table');

        return $this
            ->where('code', strtoupper($code))
            ->delete();
    }
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
