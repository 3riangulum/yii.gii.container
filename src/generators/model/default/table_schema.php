<?php

//use yii\db\mysql\ColumnSchema ;

/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

/* @var string $schemaNameClassName string */

echo "<?php\n"; ?>

namespace <?= $generator->ns ?>;

final class <?= $schemaNameClassName ."\n"?>
{
<?php foreach ($tableSchema->columns as $name => $column): ?>

    <?= "public const " . strtoupper($name) . " = " . $generator->generateString($name) . "; \n" ?>
<?php endforeach; ?>
}
