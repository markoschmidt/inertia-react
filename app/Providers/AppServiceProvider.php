<?php

namespace App\Providers;

use Inertia\Inertia;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Pagination\UrlWindow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
// use League\Glide\Server;
// use Illuminate\Support\Facades\Storage; // Uncomment if using Glide
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLengthAwarePaginator();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Date::use(CarbonImmutable::class);
    }

    protected function registerLengthAwarePaginator()
    {
        $this->app->bind(LengthAwarePaginator::class, function ($app, $values) {
            return new class (...array_values($values)) extends LengthAwarePaginator
            {
                public function only(...$attributes)
                {
                    return $this->transform(function ($item) use ($attributes) {
                        return $item->only($attributes);
                    });
                }

                public function transform($callback)
                {
                    $this->items->transform($callback);

                    return $this;
                }

                public function toArray()
                {
                    return [
                        'data' => $this->items->toArray(),
                        'links' => $this->links(),
                        'total' => $this->total,
                        'perPage' => $this->perPage(),
                        'lastPage' => $this->lastPage(),
                        'currentPage' => $this->currentPage()
                    ];
                }

                public function links($view = null, $data = [])
                {
                    $this->appends(Request::all());

                    // $window = UrlWindow::make($this);
                    $elements = [];

                    $current = [
                        'url' => null,
                        'label' => 'Current',
                        'active' => true
                    ];
                    $first = [
                        'url' => $this->url(1),
                        'label' => 'First',
                        'active' => false,
                    ];
                    $previous = [
                        'url' => $this->previousPageUrl(),
                        'label' => 'Previous',
                        'active' => false,
                    ];
                    $next = [
                        'url' => $this->nextPageUrl(),
                        'label' => 'Next',
                        'active' => false,
                    ];
                    $last = [
                        'url' => $this->url($this->lastPage()),
                        'label' => 'Last',
                        'active' => false,
                    ];

                    array_push($elements, $current);
                    $this->currentPage !== 1 && array_unshift($elements, $first, $previous);
                    $this->currentPage !== $this->lastPage() && array_push($elements, $next, $last);

                    return $elements;

                    // $elements = array_filter([
                    //     $window['first'],
                    //     is_array($window['slider']) ? '...' : null,
                    //     $window['slider'],
                    //     is_array($window['last']) ? '...' : null,
                    //     $window['last'],
                    // ]);

                    // return Collection::make($elements)->flatMap(function ($item) {
                    //     if (is_array($item)) {
                    //         return Collection::make($item)->map(function ($url, $page) {
                    //             return [
                    //                 'url' => $url,
                    //                 'label' => $page,
                    //                 'active' => $this->currentPage() === $page,
                    //             ];
                    //         });
                    //     } else {
                    //         return [
                    //             [
                    //                 'url' => null,
                    //                 'label' => '...',
                    //                 'active' => false,
                    //             ],
                    //         ];
                    //     }
                    // })->prepend([
                    //     'url' => $this->previousPageUrl(),
                    //     'label' => 'Previous',
                    //     'active' => false,
                    // ])->push([
                    //     'url' => $this->nextPageUrl(),
                    //     'label' => 'Next',
                    //     'active' => false,
                    // ]);
                }
            };
        });
    }
}
