<?php namespace Flarum\Core\Posts;

use Flarum\Core\Discussions\Discussion;
use Flarum\Core\Users\User;
use Flarum\Events\ModelAllow;
use Flarum\Events\RegisterPostTypes;
use Flarum\Events\ScopePostVisibility;
use Flarum\Support\ServiceProvider;
use Flarum\Extend;

class PostsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Post::setValidator($this->app->make('validator'));

        CommentPost::setFormatter($this->app->make('flarum.formatter'));

        $this->registerPostTypes();

        $events = $this->app->make('events');

        $events->listen(ModelAllow::class, function (ModelAllow $event) {
            if ($event->model instanceof Post) {
                $post = $event->model;
                $action = $event->action;
                $actor = $event->actor;

                if ($action === 'view' &&
                    (! $post->hide_user_id || $post->can($actor, 'edit'))) {
                    return true;
                }

                // A post is allowed to be edited if the user has permission to moderate
                // the discussion which it's in, or if they are the author and the post
                // hasn't been deleted by someone else.
                if ($action === 'edit' &&
                    ($post->discussion->can($actor, 'editPosts') ||
                        ($post->user_id == $actor->id &&
                            (! $post->hide_user_id || $post->hide_user_id == $actor->id)))) {
                    return true;
                }

                if ($post->discussion->can($actor, $action.'Posts')) {
                    return true;
                }
            }
        });

        // When fetching a discussion's posts: if the user doesn't have permission
        // to moderate the discussion, then they can't see posts that have been
        // hidden by someone other than themself.
        $events->listen(ScopePostVisibility::class, function (ScopePostVisibility $event) {
            $user = $event->actor;

            if (! $event->discussion->can($user, 'editPosts')) {
                $event->query->where(function ($query) use ($user) {
                    $query->whereNull('hide_user_id')
                        ->orWhere('hide_user_id', $user->id);
                });
            }
        });
    }

    /**
     * Register post types.
     *
     * @return void
     */
    public function registerPostTypes()
    {
        $models = [
            'Flarum\Core\Posts\CommentPost',
            'Flarum\Core\Posts\DiscussionRenamedPost'
        ];

        event(new RegisterPostTypes($models));

        foreach ($models as $model) {
            Post::setModel($model::$type, $model);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}