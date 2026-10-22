<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use phpDocumentor\Reflection\Types\Boolean;

class Property extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Property>
     */
    public static $model = \App\Models\Property\Property::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'reference';

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
            // ID::make()->sortable(),
            Text::make('reference'),
            Text::make('name'),
            Text::make('price'),
            Text::make('price_meta')->hideFromIndex(),
            Text::make('description')->hideFromIndex(),
            Text::make('neighborhood')->hideFromIndex(),
            Text::make('address')->hideFromIndex(),
            Text::make('city','city_name'),
            Text::make('state','state_name')->hideFromIndex(),
            Text::make('postal_code', 'postal_code')->hideFromIndex(),
            Text::make('country','country_name'),
            Text::make('min_bookable_night', 'min_bookable_night')->hideFromIndex(),
            Text::make('listed_by')->hideFromIndex(),
            Text::make('map_address')->hideFromIndex(),
            Text::make('status', function(){
                return $this->status->name;
            }),
            Text::make('status_id')->hideFromIndex(),
            Text::make('property_type')->hideFromIndex(),
            Text::make('check_in')->hideFromIndex(),
            Text::make('check_out')->hideFromIndex(),
            Text::make('languages')->hideFromIndex(),
            Text::make('parking_is_available')->hideFromIndex(),
            Text::make('parking_options')->hideFromIndex(),
            Text::make('room_type_id')->hideFromIndex(),
            Text::make('room_quantity')->hideFromIndex(),
            Text::make('max_guest')->hideFromIndex(),
            Text::make('room_size')->hideFromIndex(),
            Text::make('bathroom_type')->hideFromIndex(),
            Text::make('check_in_from')->hideFromIndex(),
            Text::make('check_in_to')->hideFromIndex(),
            Text::make('check_out_from')->hideFromIndex(),
            Text::make('check_out_to')->hideFromIndex(),
            Text::make('refund_policy')->hideFromIndex(),
            Text::make('sofa_availability')->hideFromIndex(),
            Text::make('bedroom_qty')->hideFromIndex(),
            Text::make('bathroom_qty')->hideFromIndex(),
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

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
}
