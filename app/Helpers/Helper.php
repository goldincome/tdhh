<?php

use App\Models\GeneralSetting;
use Carbon\CarbonPeriod;

if (! function_exists('currency_convert')) {
    /**
     * Convert given number.
     *
     * @param float  $amount
     * @param string $from
     * @param string $to
     * @param bool   $format
     *
     * @return \Torann\Currency\Currency|string
     */
    function currency_convert($amount = null, $from = null, $to = null, $format = true)
    {
        if (is_null($amount)) {
            return app('currency');
        }

        return app('currencyservice')->convert($amount, $from, $to, $format);
    }
}

if (! function_exists('currency_format')) {
    /**
     * Format given number.
     *
     * @param float  $amount
     * @param string $currency
     * @param bool   $include_symbol
     *
     * @return string
     */
    function currency_format($amount = null, $currency = null, $include_symbol = true)
    {
        return app('currency')->format($amount, $currency, $include_symbol);
    }
}


if (! function_exists('currency_symbol')) {
    /**
     * Format given number.
     *
     * @param float  $amount
     * @param string $currency
     * @param bool   $include_symbol
     *
     * @return string
     */
    function currency_symbol()
    {
        return app('currencyservice')->getCurrencySymbol();
    }
}

if(!function_exists('generateDateRange')){
    function generateDateRange( $startDate,  $endDate, $affectedDays = [])
    {
        $period = CarbonPeriod::create($startDate, $endDate);
        $dates = $period->toArray();

        if($affectedDays){
            foreach ($dates as $key => $date) {
                if(!in_array($date->dayOfWeek, $affectedDays)) unset($dates[$key]);
            }
        }

        return $dates;
    }
}

if(!function_exists('createPDF')){ 
    function createPDF($data, string $viewFile,$fileName='invoice') {
        // retreive all records from db
        // share data to view
        //view()->share($viewFile,$data);
        $pdf = PDF::loadView($viewFile, $data);
        // download PDF file with download method
        return $pdf->download($fileName.'.pdf');
    }
}

if(!function_exists('get_settings')){ 
    function get_settings($name)
    {
        return GeneralSetting::where('name', $name)->first()->value;
    }
}

if (! function_exists('tdhh_nap')) {
    /**
     * The single source of truth for the company name, address and contact
     * details of T D HOLIDAY HOMES LTD.
     *
     * Every consumer of the business name, address, phone number or email
     * address must read it from here so that the footer, the structured
     * data and the legal line can never drift apart.
     *
     * The registered office and the operating address are deliberately kept
     * as two separate values. The operating address is what customers visit
     * and appears in the footer. The registered office appears only on the
     * legal line. Conflating them is a common source of inconsistent NAP
     * citations.
     *
     * @return array<string, string>
     */
    function tdhh_nap(): array
    {
        return [
            'legal_name' => 'T D HOLIDAY HOMES LTD',
            'brand' => 'TD Holiday Homes',
            'company_number' => '11247086',
            'registered_office' => '53 Tideslea Path, London, England, SE28 0LY',
            'operating_address' => 'Unit 6, Block 3 Woolwich Dockyard Industrial Estate, Woolwich Church Street, Charlton, London, SE18 5PQ, United Kingdom',
            'operating_address_short' => 'Unit 6, Block 3 Woolwich Dockyard Industrial Estate, Woolwich Church Street, Charlton, London SE18 5PQ',
            'phone_display' => '+44 (0) 203 247 4747',
            'phone_href' => 'tel:+442032474747',
            'email' => 'info@tdhh.co.uk',
            'domain' => 'tdhh.co.uk',
            'url' => 'https://tdhh.co.uk',
        ];
    }
}

if (! function_exists('tdhh_schema_address')) {
    /**
     * The operating address as a PostalAddress structured data object.
     *
     * @return array<string, string>
     */
    function tdhh_schema_address(): array
    {
        $nap = tdhh_nap();

        return [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Unit 6, Block 3 Woolwich Dockyard Industrial Estate, Woolwich Church Street, Charlton',
            'addressLocality' => 'London',
            'addressRegion' => 'London',
            'postalCode' => 'SE18 5PQ',
            'addressCountry' => 'GB',
        ];
    }
}
