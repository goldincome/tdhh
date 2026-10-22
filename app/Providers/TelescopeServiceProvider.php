<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            if (! config('telescope.enabled')) {
                return false;
            }

            if ($this->app->environment('local')) {
                return true;
            }

            return $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedJob() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag();
        });
    }

    /**
     * Configure the Telescope authorization services.
     *
     * The parent implementation registers an authorisation callback that
     * grants access to ANYONE when the application environment is "local":
     *
     *     return app()->environment('local') || Gate::check('viewTelescope', ...);
     *
     * The production server was running with APP_ENV=local, so the
     * authorisation callback returned true for anonymous visitors and
     * https://tdhh.co.uk/telescope served the full dashboard: every
     * recorded request, SQL query, exception and email.
     *
     * This override replaces that callback. The dashboard now always
     * requires an authenticated user whose email address is listed in
     * TELESCOPE_ALLOWED_EMAILS, and it denies everyone when that list is
     * empty. The environment is never consulted.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        Telescope::auth(function ($request) {
            $allowed = $this->allowedEmails();

            if ($allowed === []) {
                return false;
            }

            $user = $request->user();

            return $user !== null
                && in_array(strtolower((string) $user->email), $allowed, true);
        });
    }

    /**
     * Register the Telescope gate.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            if ($user === null) {
                return false;
            }

            return in_array(strtolower((string) $user->email), $this->allowedEmails(), true);
        });
    }

    /**
     * The list of email addresses permitted to view the Telescope dashboard.
     *
     * @return array<int, string>
     */
    protected function allowedEmails(): array
    {
        $raw = (string) env('TELESCOPE_ALLOWED_EMAILS', '');

        return array_values(array_filter(array_map(
            fn ($email) => strtolower(trim($email)),
            explode(',', $raw)
        )));
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }
}
