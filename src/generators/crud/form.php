<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator Triangulum\Yii\GiiContainer\generators\crud\Generator */

//echo $form->field($generator, 'moduleNS');
//echo $form->field($generator, 'moduleId');
//echo $form->field($generator, 'containerId');


echo $form->field($generator, 'unitNs');
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'repository');
echo $form->field($generator, 'tableSchema');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'grid' => 'GridView',
    'list' => 'ListView',
]);
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'enablePjax')->checkbox();
echo $form->field($generator, 'messageCategory');
