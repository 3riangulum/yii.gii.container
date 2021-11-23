<?php

/* @var $this yii\web\View */
/* @var $generator \Triangulum\Yii\GiiContainer\generators\crud\Generator */
/* @var $gridNS string */


echo "<?php\n";
?>

use Triangulum\Yii\ModuleContainer\UI\Html\RowCol;

/* @var $this yii\web\View */
/* @var $grid <?php echo $gridNS ?> */
/* @var $uiCreator Triangulum\Yii\Unit\Front\Items\FrontSimple */
/* @var $pageTitle string */

$this->title = $pageTitle;
$uiCreator->registerPopup($this);
RowCol::two([$uiCreator->htmlButton()]);
$grid->renderByPjax($this);
