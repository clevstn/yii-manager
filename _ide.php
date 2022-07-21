<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace yii\web {

    /**
     * @property ErrorHandler $errorHandler The error handler application component. This property is read-only.
     * @property string $homeUrl The homepage URL.
     * @property Request $request The request component. This property is read-only.
     * @property Response $response The response component. This property is read-only.
     * @property Session $session The session component. This property is read-only.
     * @property User $user The user component. This property is read-only.
     * @property User $adminUser The user component. This property is read-only.
     * @author cleverstone
     * @since 1.0
     */
    class Application extends \yii\base\Application
    {
        public function handleRequest($request)
        {

        }
    }
}