<?php

return [
    'schema' => [
        /**
         * Define your base query schema. The rest of the schema will
         * be inferred by crawling the graph recursively in line with
         * max_depth defined below.
         */
        'query' => [],

        /**
         * Coming Soon
         */
        'mutation' => [],
    ],

    /**
     * All GraphQL requests come through a single endpoint.
     * This endpoint will respond to GET and POST requests.
     */
    'endpoint' => '/graphql',

    /**
     * The graph will be automatically followed recursively to the defined
     * depth. This can be set to a larger number, but is often not necessary.
     *
     * The larger this number, the slower and more memory intensive a request
     * will be.
     *
     * If you do not need a number this high and want better performance
     * you can try a lower number. Any number between 3 and 5 should be a
     * good mix between functionality and performance.
     */
    'max_depth' => 5,
];