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

/* @var string $modelClassName string */
/* @var string $entityFullClass string */
/* @var string $queryFullClass string */
/* @var string $schemaFullClass string */
/* @var string $tableSchemaClass string */
/* @var string $repositoryClassNs string */
/* @var string $repositoryClassName string */

echo "<?php\n"; ?>

namespace <?= $repositoryClassNs ?>;

use DomainException;
use Throwable;
use Triangulum\Yii\Unit\Data\Db\DbRepositoryAbstract;
use yii\db\Transaction;

use <?php echo $queryFullClass ?>;
use <?php echo $entityFullClass ?>;
use <?php echo $schemaFullClass ?>;

final class <?= $repositoryClassName  ?>  extends DbRepositoryAbstract
{
    public function query(): <?php echo $className ?>
    {
        return new <?php echo $queryClassName ?>(<?php echo $modelClassName ?>::class);
    }

    public function single(int $pk, bool $throw = true): ?<?php echo $modelClassName ?>
    {
        $entity = $this
        ->query()
        ->andWhere(['=', <?php echo $tableSchemaClass ?>::PK, $pk])
        ->limit(1)
        ->one();

        if ($throw && empty($entity)) {
            throw new DomainException('Entity not exist');
        }

        return $entity;
    }

    public function save(<?php echo $modelClassName ?> $entity): bool
    {
        return $entity->save();
    }

    public function create(): <?php echo $modelClassName ?>
    {
        return new <?php echo $modelClassName ?>();
    }

    public function delete(<?php echo $modelClassName ?> $entity)
    {
        return $entity->delete();
    }

    public function beginTransaction(): Transaction
    {
        return <?php echo $modelClassName ?>::getDb()->beginTransaction();
    }
}
