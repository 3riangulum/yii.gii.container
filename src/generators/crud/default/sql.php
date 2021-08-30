<?php
/**
 * This is the template for generating Routing class of the specified model.
 */

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator \Triangulum\Yii\GiiContainer\generators\crud\Generator */

$modelClass = str_replace('Model', '', StringHelper::basename($generator->modelClass));

$moduleId = $generator->moduleId;
$moduleName = ucfirst($generator->moduleId) . '.';

$time = time();
$id = $generator->getControllerID();
$actionList = [
    'index'     => '[grid]',
    'view'      => '[view]',
    'create'    => '[create]',
    'update'    => '[update]',
    'delete'    => '[delete]',
    'duplicate' => '[duplicate]',
];

foreach ($actionList as $action => $title) {
    $rule = $generator->moduleId . DS . $id . DS . $action;
    $ruleName = $moduleName . $modelClass . $title;
    echo <<<SQL
INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('$rule', 2,'$ruleName', NULL, NULL, $time, $time);\r\n
SQL;
}

echo "\r\n";

$rootRole = 'role-root';
foreach ($actionList as $action => $title) {
    $rule = $generator->moduleId . DS . $id . DS . $action;
    $ruleName = $modelClass . $title;
    echo <<<SQL
INSERT INTO `auth_item_child` (`parent`, `child`) VALUES ('$rootRole', '$rule');\r\n
SQL;
}
