<?php

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */
/* @var $model yii\db\ActiveRecord */
/* @var $tableSchemaUse string */



$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use Triangulum\Yii\ModuleContainer\UI\Front\Element\ElementPopup;
use Triangulum\Yii\ModuleContainer\UI\Html\Label;
use Triangulum\Yii\ModuleContainer\UI\Html\LabelInline;
use Triangulum\Yii\ModuleContainer\UI\Html\Dropdown\Dropdown;
use Triangulum\Yii\ModuleContainer\UI\Html\Dropdown\FilterDropdown;
use Triangulum\Yii\ModuleContainer\UI\Html\Time;
use Triangulum\Yii\ModuleContainer\UI\Html\Text;
use Triangulum\Yii\ModuleContainer\UI\Html\Icons;
use Triangulum\Yii\ModuleContainer\UI\Html\Span;
use Triangulum\Yii\ModuleContainer\UI\Html\Growl;
use Triangulum\Yii\ModuleContainer\UI\Html\Button;
<?php echo $tableSchemaUse?>;

use yii\helpers\Html;
use <?= ltrim($generator->modelClass, '\\') ?>;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
/* @var $hideModal int */
/* @var $actionTitle string */
/* @var $popup Triangulum\Yii\ModuleContainer\UI\Front\Element\ElementPopup */

$popup->pjaxBegin($model->hasErrors());
$popup->panelBeginAdvanced($actionTitle, $model->primaryKey ? ['id' => $model->pkGet()] : []);
$form = $popup->formBegin();
echo Button::submitTop(); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo "<?php";?>

        <?php foreach ($generator->getColumnNames() as $attribute) {
            if (\in_array($attribute, $safeAttributes, false)) {
                echo "     echo " . $generator->generateActiveFieldByTableSchema($attribute) . "; \n";
            }
        } ?>
        <?php echo "?>";?>

    </div>
</div>
<?= "<?php \r\n" ?>
echo Button::submitBottom();
$popup->formEnd();
$popup->panelEndAdvanced();
$popup->hideAndReloadGrid($hideModal, $this, $actionTitle);
$popup->pjaxEnd();
