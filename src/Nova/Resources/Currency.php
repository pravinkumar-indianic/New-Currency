<?php
namespace Indianic\CurrencyManagement\Nova\Resources;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use Trin4ik\NovaSwitcher\NovaSwitcher;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\HandleScreeingOption;


class Currency extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Indianic\CurrencyManagement\Models\Currency::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'shortname', 'name', 'phonecode'
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
            
            Image::make('Country Flag', 'flag_image')
                    ->path('flags')
                    ->preview(function ($value, $disk) {
                        return $value ? Storage::disk($disk)->url($value) : null;
                    })
                    ->help('Upload an image to display the country flag')
                    ->maxWidth(100)
                    ->prunable()
                    ->deletable(false),
            
            Text::make('name')
                    ->sortable()
                    ->rules('required', 'max:255'),
            
            Text::make('Currency Name','currency_name')
                    ->sortable()
                    ->rules('required', 'max:25'),
            
            Text::make('Currency Symbol','currency_symbol')
                    ->sortable()
                    ->rules('required', 'max:25'),
            
            Text::make('Currency Code','currency_code')
                    ->sortable()
                    ->rules('required', 'max:25'),
            
            NovaSwitcher::make('Status')
            
            
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
        return [
        ];
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
}
