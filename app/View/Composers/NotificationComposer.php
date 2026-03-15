<?php

namespace App\View\Composers;

use Illuminate\View\View;

class NotificationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = auth()->user();

        if ($user) {
            $unreadCount = $user->unreadNotifications()->count();
            $topNotifications = $user->notifications()->take(10)->get();

            $view->with('unreadCount', $unreadCount)
                 ->with('topNotifications', $topNotifications);
        } else {
            $view->with('unreadCount', 0)
                 ->with('topNotifications', collect([]));
        }
    }
}
