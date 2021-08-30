<?php

/* @var $this yii\web\View */
/* @var $containerNS string */
/* @var $containerClass string */
/* @var $containerId string */ ?>

<?php echo "<?php"?>

namespace <?php echo $containerNS?>;

use Triangulum\Yii\ModuleContainer\ModuleContainerBase;

final class <?php echo $containerClass?> extends ModuleContainerBase
{
    public const ID   = '<?php echo $containerId?>';
    public const NAME = '<?php echo ucfirst($containerId)?>';
}
