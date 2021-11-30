<?php

/* @var $modelNs string */
/* @var $routeClassName string */
/* @var $unitAlias string */

echo '<?php' ?>

namespace <?php echo $modelNs ?>;

use Triangulum\Yii\Unit\Admittance\RouteBase;

/**
* @method static self build
*/
final class <?php echo $routeClassName?> extends RouteBase
{
    protected string $unitAlias = '<?php  echo $unitAlias;?>';
    protected array  $actions   = [
        self::ACTION_INDEX,
        self::ACTION_CREATE,
        self::ACTION_EDIT,
        self::ACTION_DELETE,
        self::ACTION_DUPLICATE,
        self::ACTION_VIEW,
    ];
}
