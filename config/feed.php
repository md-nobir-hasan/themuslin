<?php

return [
    'feeds' => [
        'main' => [
            /*
             * Here you can specify which class and method will return
             * the items that should appear in the feed. For example:
             * 'App\Model@getAllFeedItems'
             *
             * You can also pass an argument to that method:
             * ['App\Model@getAllFeedItems', 'argument']
             */
            'items' => 'App\Blog@getAllFeedItems',

            /*
             * The feed will be available on this url.
             */
            'url' => getenv('RSS_FEED_URL'),

            'title' => getenv('RSS_FEED_TITLE'),
            'description' => getenv('RSS_FEED_DESCRIPTION'),
            'language' => getenv('RSS_FEED_LANGUAGE'),

            /*
             * The view that will render the feed.
             */
            'view' => 'feed::atom',

            /*
             * The type to be used in the <link> tag
             */
            'type' => 'application/atom+xml',
        ],
    ],
];
