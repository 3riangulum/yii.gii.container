<?php

/* @var $this yii\web\View */
/* @var $generator \Triangulum\Yii\GiiContainer\generators\crud\Generator */
/* @var $gridNS string */


echo "<?php\n";
?>

use \Triangulum\Yii\ModuleContainer\UI\Html\RowCol;
use yii\helpers\Html;

/* @var $actionTitle string */
/* @var $grid <?php echo $gridNS ?> */
/* @var $creator Triangulum\Yii\ModuleContainer\UI\Front\Element\ElementPopup */
/* @var $this yii\web\View */

$creator->registerPopup($this);
RowCol::two([$creator->htmlButton()]);
$grid->renderByPjax($this, $actionTitle);
