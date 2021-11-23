<?php

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */
/* @var $crudFormNS string */

echo "<?php\n";
?>

use \Triangulum\Yii\Unit\Front\Items\FrontItem;
use \Triangulum\Yii\Unit\Html\Label\Label;
use \Triangulum\Yii\Unit\Html\Label\LabelInline;
use \Triangulum\Yii\Unit\Html\Dropdown\Dropdown;
use \Triangulum\Yii\Unit\Html\Dropdown\FilterDropdown;
use \Triangulum\Yii\Unit\Html\Time\Time;
use \Triangulum\Yii\Unit\Html\Text\Text;
use \Triangulum\Yii\Unit\Html\Icons\Icons;
use \Triangulum\Yii\Unit\Html\Span\Span;
use \Triangulum\Yii\Unit\Html\Growl;

use yii\helpers\Html;
use <?= ltrim($generator->modelClass, '\\') ?>;

/* @var $this yii\web\View */
/* @var $form <?php echo $crudFormNS?> */

    $form->begin(); <?php echo '?>'?>

    <div class="row">
        <div class="col-md-6">
            A
        </div>
        <div class="col-md-6">
            B
        </div>
    </div>

<?php echo '<?php'?>

echo $form->submitBottom();
$form->end();
