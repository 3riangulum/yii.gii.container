<?php

/* @var $this yii\web\View */
/* @var $containerUse string */
/* @var $frontUse string */
/* @var $gridNS string */
/* @var $containerClass string */
/* @var $frontClass string */
/* @var $gridClass string */

?>

<?php echo "<?php \n"?>

use Triangulum\Yii\ModuleContainer\System\Cache\RedisPrefixedCache;
use Triangulum\Yii\ModuleContainer\System\Log\BaseLog;
use Triangulum\Yii\ModuleContainer\UI\Access\AccessBase;
use Triangulum\Yii\ModuleContainer\UI\Access\RouterBase;
use Triangulum\Yii\ModuleContainer\UI\Menu\MenuItem;
use yii\di\Instance;

<?php echo $containerUse?>

<?php echo $frontUse?>

use <?php echo $gridNS?>;


return [
    <?php echo $containerClass?>::ID => [
    <?php echo $containerClass?>::ID  => ['class' => <?php echo $containerClass?>::class],
        RouterBase::ID         => [
            'class'   => RouterBase::class,
            'access'  => Instance::of(AccessBase::class),
            'actions' => [
                RouterBase::ACTION_INDEX,
                RouterBase::ACTION_EDIT,
                RouterBase::ACTION_CREATE,
                RouterBase::ACTION_DELETE,
                RouterBase::ACTION_DUPLICATE,
                RouterBase::ACTION_VIEW,
            ],
        ],
        MenuItem::ID           => [
            'class' => MenuItem::class,
            'title' => <?php echo $containerClass?>::NAME,
        ],
        BaseLog::ID            => ['class' => BaseLog::class],
        RedisPrefixedCache::ID => ['class' => RedisPrefixedCache::class],
        <?php echo $frontClass?>::ID      => [
            'class'     => <?php echo $frontClass?>::class,
            'gridClass' => <?php echo $gridClass?>::class,
        ],
    ],
];
