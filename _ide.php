<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace yii\web {

    /**
     * @property-read ErrorHandler $errorHandler The error handler application component. This property is read-only.
     * @property-read string $homeUrl The homepage URL.
     * @property-read Request $request The request component. This property is read-only.
     * @property-read Response $response The response component. This property is read-only.
     * @property-read Session $session The session component. This property is read-only.
     * @property-read User $user The user component. This property is read-only.
     * @property-read User $adminUser The user component. This property is read-only.
     * @property-read \app\components\Uploads $uploads The upload component. This property is read-only.
     * @property-read \app\components\Sms $sms The sms component. This property is read-only.
     * @property-read \app\components\RbacManager $rbacManager The rbac component. This property is read-only.
     * @property-read \app\components\ConfigManager $config The config component. This property is read-only.
     * @property-read \app\components\MailManager $mailManager The mail manager component. This property is read-only.
     * @author cleverstone
     * @since ym1.0
     */
    class Application extends \yii\base\Application
    {
        public function handleRequest($request)
        {

        }
    }
}