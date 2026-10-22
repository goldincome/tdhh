<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Reservations extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Reservations>
     */
    public static $model = \App\Models\Reservations::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('property', function(){
                return $this->property->name;
            }),

            Text::make('partner', function(){
                return $this->property->partner->full_name;
            }),

            Text::make('customer', function(){
                return $this->customer->full_name;
            }),

            Text::make('price', function(){
                return $this->price;
            }),

            Text::make('num_of_night', function(){
                return $this->num_of_night;
            }),

            Text::make('cleaning_fee')->hideFromIndex(),
            Text::make('parking_fee')->hideFromIndex(),
            Text::make('subtotal')->hideFromIndex(),
            Text::make('subtotal')->hideFromIndex(),
            Text::make('payment_method')->hideFromIndex(),
            Text::make('proposed_check_in_date')->hideFromIndex(),
            Text::make('proposed_check_in_time')->hideFromIndex(),
            Text::make('proposed_check_out_date')->hideFromIndex(),
            Text::make('proposed_check_out_time')->hideFromIndex(),
            Text::make('actual_check_in_date')->hideFromIndex(),
            Text::make('actual_check_in_time')->hideFromIndex(),
            Text::make('actual_check_out_date')->hideFromIndex(),
            Text::make('actual_check_out_time')->hideFromIndex(),
            Text::make('adult')->hideFromIndex(),
            Text::make('children')->hideFromIndex(),
            Text::make('pet')->hideFromIndex(),
            Text::make('message')->hideFromIndex(),
            Text::make('booking_status_id')->hideFromIndex(),
            Text::make('payment_status_id')->hideFromIndex(),
            Text::make('payment_date')->hideFromIndex(),
            Text::make('max num of guest','max_num_of_guest')->hideFromIndex(),
            Text::make('num_of_guest')->hideFromIndex(),
            Text::make('room details', 'room_details')->hideFromIndex(),
            Text::make('parking_details')->hideFromIndex(),
            Text::make('location_details')->hideFromIndex(),
            Text::make('property_rules')->hideFromIndex(),
            Text::make('guest_info')->hideFromIndex(),
            Text::make('property_characteristics')->hideFromIndex(),
            Text::make('cleaning_options')->hideFromIndex(),
            Text::make('price_details')->hideFromIndex(),
            Text::make('refund_policy')->hideFromIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
