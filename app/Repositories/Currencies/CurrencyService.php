<?php
namespace App\Repositories\Currencies;

use NumberFormatter;
use Illuminate\Support\Arr;
use Torann\Currency\Currency;
use Illuminate\Contracts\Cache\Factory as FactoryContract;

class CurrencyService extends Currency
{

  protected $formatter;
  /**
     * Currency configuration.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Laravel application
     *
     * @var \Illuminate\Contracts\Cache\Factory
     */
    protected $cache;

    /**
     * User's currency
     *
     * @var string
     */
    protected $user_currency;

    /**
     * Currency driver instance.
     *
     * @var Contracts\DriverInterface
     */
    protected $driver;

    /**
     * Cached currencies
     *
     * @var array
     */
    protected $currencies_cache;


  public function __construct()
  {
    parent::__construct(config('currency'), cache());
  }
 
  public function formatValue($value, $code = null)
  {
      return $this->formatter->formatCurrency($value, $code);
  }

  public function getCurrencySymbol()
  {
    $code = currency()->getUserCurrency()?: $this->config('default');
    return $this->getCurrencyProp($code, 'symbol');
  }
  //  /**
  //    * Format given number.
  //    *
  //    * @param float  $amount
  //    * @param string $from
  //    * @param string $to
  //    * @param bool   $format
  //    *
  //    * @return string|null
  //    */
    public function convert($amount, $from = null, $to = null, $format = true)
    {
        // Get currencies involved
        $from = $from ?: $this->config('default');
        $to = $to ?: $this->getUserCurrency();

        // Get exchange rates
        $from_rate = $this->getCurrencyProp($from, 'exchange_rate');
        $to_rate = $this->getCurrencyProp($to, 'exchange_rate');
        $toNaira = floatval($amount) * floatval($from_rate);
      
        // Skip invalid to currency rates
        if ($to_rate === null) {
            return null;
        }

        try {
            // Convert amount
            if ($from === $to) {
                $value = $amount;
            } else {
                //$value = ($amount * $to_rate) / $from_rate;
                $value = $toNaira/$to_rate;
            }
        } catch (\Exception $e) {
            // Prevent invalid conversion or division by zero errors
            return null;
        }
    
        // Should the result be formatted?
        if ($format === true) {

          $fValue = $this->format($value, $to);
          //Check if Naira and format output
          if (stristr($fValue, 'NGN'))
          {
            $len = strlen($fValue);
            $val = substr($fValue, 5, $len);
            $fValue = $this->getCurrencyProp('NGN', 'symbol'). $val;
          }

          // remove .00 at the front
          $values = explode('.', $fValue);
          $count = count($values);
          if($values[$count-1] <= 00)
          {
            $fValue = substr($fValue, 0, -3);
          }
            //return formatted value
            return $fValue;
        }
        // Return value
        return $value;
    }

    /**
     * Format the value into the desired currency.
     *
     * @param float  $value
     * @param string $code
     * @param bool   $include_symbol
     *
     * @return string
     */
    public function format($value, $code = null, $include_symbol = true)
    {
        // Get default currency if one is not set
        $code = $code ?: $this->config('default');

        // Remove unnecessary characters
        $value = preg_replace('/[\s\',!]/', '', $value);

        // Check for a custom formatter
        if ($this->getFormatter()) {
            return $this->formatValue($value, $code);
        }

        // Get the measurement format
        $format = $this->getCurrencyProp($code, 'format');

        // Value Regex
        $valRegex = '/([0-9].*|)[0-9]/';

        // Match decimal and thousand separators
        preg_match_all('/[\s\',.!]/', $format, $separators);

        if ($thousand = Arr::get($separators, '0.0', null)) {
            if ($thousand == '!') {
                $thousand = '';
            }
        }

        $decimal = Arr::get($separators, '0.1', null);

        // Match format for decimals count
        preg_match($valRegex, $format, $valFormat);

        $valFormat = Arr::get($valFormat, 0, 0);

        // Count decimals length
        $decimals = $decimal ? strlen(substr(strrchr($valFormat, $decimal), 1)) : 0;

        // Do we have a negative value?
        if ($negative = $value < 0 ? '-' : '') {
            $value = $value * -1;
        }

        // Format the value
        $value = number_format($value, $decimals, $decimal, $thousand);

        // Apply the formatted measurement
        if ($include_symbol) {
            $value = preg_replace($valRegex, $value, $format);
        }
        // Return value
        return $negative . $value;
    }

    /**
     * Set user's currency.
     *
     * @param string $code
     */
    public function setUserCurrency($code)
    {
        $this->user_currency = strtoupper($code);
    }

    /**
     * Return the user's currency code.
     *
     * @return string
     */
    public function getUserCurrency()
    {
        return $this->user_currency ?: $this->config('default');
    }

    /**
     * Determine if the provided currency is valid.
     *
     * @param string $code
     *
     * @return array|null
     */
    public function hasCurrency($code)
    {
        return array_key_exists(strtoupper($code), $this->getCurrencies());
    }

    /**
     * Determine if the provided currency is active.
     *
     * @param string $code
     *
     * @return bool
     */
    public function isActive($code)
    {
        return $code && (bool) Arr::get($this->getCurrency($code), 'active', false);
    }

    /**
     * Return the current currency if the
     * one supplied is not valid.
     *
     * @param string $code
     *
     * @return array|null
     */
    public function getCurrency($code = null)
    {
        $code = $code ?: $this->getUserCurrency();

        return Arr::get($this->getCurrencies(), strtoupper($code));
    }

    /**
     * Return all currencies.
     *
     * @return array
     */
    public function getCurrencies()
    {
        if ($this->currencies_cache === null) {
            if (config('app.debug', false) === true) {
                $this->currencies_cache = $this->getDriver()->all();
            } else {
                $this->currencies_cache = $this->cache->rememberForever('td.currency', function () {
                    return $this->getDriver()->all();
                });
            }
        }

        return $this->currencies_cache;
    }

    /**
     * Return all active currencies.
     *
     * @return array
     */
    public function getActiveCurrencies()
    {
        return array_filter($this->getCurrencies(), function ($currency) {
            return $currency['active'] == true;
        });
    }

    /**
     * Get storage driver.
     *
     * @return \Torann\Currency\Contracts\DriverInterface
     */
    public function getDriver()
    {
        if ($this->driver === null) {
            // Get driver configuration
            $config = $this->config('drivers.' . $this->config('driver'), []);

            // Get driver class
            $driver = Arr::pull($config, 'class');

            // Create driver instance
            $this->driver = new $driver($config);
        }

        return $this->driver;
    }

    /**
     * Get formatter driver.
     *
     * @return \Torann\Currency\Contracts\FormatterInterface
     */
    public function getFormatter()
    {
        if ($this->formatter === null ) {
            // Create formatter instance
            $this->formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);
        }

        return $this->formatter;
    }

    /**
     * Clear cached currencies.
     */
    public function clearCache()
    {
        $this->cache->forget('torann.currency');
        $this->currencies_cache = null;
    }

    /**
     * Get configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function config($key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }

        return Arr::get($this->config, $key, $default);
    }

    /**
     * Get the given property value from provided currency.
     *
     * @param string $code
     * @param string $key
     * @param mixed  $default
     *
     * @return array
     */
    protected function getCurrencyProp($code, $key, $default = null)
    {
        return Arr::get($this->getCurrency($code), $key, $default);
    }

    /**
     * Get a given value from the current currency.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return Arr::get($this->getCurrency(), $key);
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->getDriver(), $method], $parameters);
    }


  public function test()
  {
    dd('it is working');
  }
  public function total($decimals = null, $decimalPoint = null, $thousandSeparator = null, $shipping = 0.00)
  {
    return "It is working";
  }
}

