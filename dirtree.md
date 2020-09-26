yii-manager
├── admin
│   ├── controllers
│   │   ├── AdminBehaviorLogController.php
│   │   ├── AdminLoginLogController.php
│   │   ├── AppLogController.php
│   │   ├── ErrorController.php
│   │   ├── ErrorLogController.php
│   │   ├── IndexController.php
│   │   ├── OpsCronController.php
│   │   ├── OpsQueueController.php
│   │   ├── OpsScriptController.php
│   │   ├── SiteController.php
│   │   └── SystemSettingController.php
│   ├── Module.php
│   └── views
│       ├── error
│       └── site
├── api
│   ├── ActiveController.php
│   ├── Module.php
│   ├── RestController.php
│   ├── v1
│   │   ├── controllers
│   │   ├── Module.php
│   │   └── views
│   └── v2
│       ├── controllers
│       ├── Module.php
│       └── views
├── assets
│   ├── AppAsset.php
│   └── SiteAsset.php
├── behaviors
│   ├── BeforeResponseBehavior.php
│   ├── DatetimeBehavior.php
│   └── PasswordBehavior.php
├── builder
│   ├── assets
│   │   ├── AngularAsset.php
│   │   ├── AngularSelect2.php
│   │   ├── BaseAsset.php
│   │   ├── CommonAsset.php
│   │   ├── FontAwesomeAsset.php
│   │   ├── IcheckAsset.php
│   │   ├── LaydateAsset.php
│   │   ├── LayerAsset.php
│   │   ├── MainAsset.php
│   │   ├── NgUpload.php
│   │   ├── PromiseAsset.php
│   │   ├── Select2Asset.php
│   │   ├── SpinnerAsset.php
│   │   ├── SweetAlert2.php
│   │   ├── Toastr2.php
│   │   └── WangEditorAsset.php
│   ├── common
│   │   ├── CommonActiveRecord.php
│   │   ├── CommonController.php
│   │   └── Group.php
│   ├── contract
│   │   ├── BuilderException.php
│   │   ├── BuilderInterface.php
│   │   ├── ConfigureInterface.php
│   │   ├── InvalidInstanceException.php
│   │   ├── NotFoundAttributeException.php
│   │   └── UndefinedOptionsException.php
│   ├── database
│   │   ├── config
│   │   ├── migrations
│   │   └── node
│   ├── filters
│   │   └── RbacFilter.php
│   ├── form
│   │   ├── app.php
│   │   ├── Builder.php
│   │   ├── views
│   │   └── widgets
│   ├── helper
│   │   ├── ConfigHelper.php
│   │   ├── DateSplitHelper.php
│   │   ├── MenuHelper.php
│   │   ├── NavbarHelper.php
│   │   └── NavHelper.php
│   ├── layouts
│   │   ├── layout.php
│   │   └── partial.php
│   ├── static
│   │   └── libs
│   ├── table
│   │   ├── app.php
│   │   ├── Builder.php
│   │   ├── CustomControl.php
│   │   ├── RowActionOptions.php
│   │   ├── Table.php
│   │   ├── ToolbarCustomOptions.php
│   │   ├── ToolbarFilterOptions.php
│   │   ├── views
│   │   └── widgets
│   ├── traits
│   │   └── Http.php
│   ├── ViewBuilder.php
│   └── widgets
│       ├── LinkPager.php
│       └── Menu.php
├── codecept
├── codecept.bat
├── codeception.yml
├── COMMAND.md
├── commands
│   ├── cliscript
│   │   └── ClearController.php
│   ├── cronjobs
│   │   ├── business
│   │   ├── Cron.php
│   │   ├── jobs
│   │   └── Module.php
│   ├── processjobs
│   │   ├── business
│   │   ├── jobs
│   │   ├── Module.php
│   │   └── Process.php
│   └── queuejobs
│       ├── business
│       ├── jobs
│       ├── Module.php
│       └── Queue.php
├── compiler.jar
├── composer.json
├── composer.lock
├── compress
│   └── assets.php
├── config
│   ├── bootstrap.php
│   ├── console.php
│   ├── db.php
│   ├── db1.php
│   ├── params.php
│   ├── test.php
│   ├── test_db.php
│   └── web.php
├── controllers
│   ├── CommonController.php
│   ├── ErrorController.php
│   └── SiteController.php
├── dirtree.md
├── docker-compose.yml
├── extend
│   ├── Extend.php
│   ├── google
│   │   └── GoogleAuthenticator.php
│   ├── qrcode
│   │   └── Qrcode.php
│   └── spreadsheet
│       └── Spreadsheet.php
├── FRAMEWORK.md
├── functions
│   ├── common.php
│   └── function.php
├── LICENSE.md
├── mail
│   └── layouts
│       └── html.php
├── migrations
│   ├── db
│   │   └── m200814_070648_create_user_table.php
│   └── db1
│       └── m200814_071113_create_test_table.php
├── models
│   ├── AdminUser.php
│   ├── SystemConfig.php
│   └── User.php
├── README.md
├── requirements.php
├── tests
│   ├── api
│   ├── api.suite.yml
│   ├── unit
│   ├── unit.suite.yml
│   ├── _bootstrap.php
│   ├── _output
│   └── _support
│       ├── ApiTester.php
│       ├── Helper
│       ├── UnitTester.php
│       └── _generated
├── vagrant
│   ├── config
│   │   └── vagrant-local.example.yml
│   ├── nginx
│   │   ├── app.conf
│   │   └── log
│   └── provision
│       ├── always-as-root.sh
│       ├── once-as-root.sh
│       └── once-as-vagrant.sh
├── Vagrantfile
├── views
│   ├── error
│   │   └── index.php
│   ├── layouts
│   │   └── main.php
│   └── site
│       └── index.php
├── web
│   ├── assets
│   │   ├── 43af352
│   │   ├── 4db7a6d4
│   │   ├── 9694162b
│   │   └── e242be97
│   ├── favicon.ico
│   ├── index-test.php
│   ├── index.php
│   ├── min
│   │   ├── css
│   │   └── js
│   ├── resources
│   │   ├── css
│   │   └── js
│   └── robots.txt
├── widgets
│   └── Alert.php
├── yii
├── yii.bat
├── yuicompressor.jar
└── _ide.php
