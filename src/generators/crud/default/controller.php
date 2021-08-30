<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $containerUse string */
/* @var $containerClass string */
/* @var $frontUse string */
/* @var $frontVar string */
/* @var $frontParam string */
/* @var $logUse string */
/* @var $logParam string */
/* @var $logVar string */
/* @var $cacheUse string */
/* @var $cacheParam string */
/* @var $cacheVar string */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n"; ?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Throwable;
use Yii;
<?=$containerUse?><?="\n"?>
<?=$frontUse?><?="\n"?>
<?=$logUse?><?="\n"?>
<?=$cacheUse?><?="\n"?>

class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{<?="\n"?>
    public function actionIndex(
        <?=$frontParam?>,
        <?=$logParam?><?="\n"?>
    ): string {<?="\n"?>
        try {<?="\n"?>
            return $this->render(
                <?=$frontVar?>->templatePath('index.php'),
                [
                    'actionTitle' => <?=$containerClass?>::NAME,
                    'creator'     => <?=$frontVar?>->creator(),
                    'grid'        => <?=$frontVar?>->gridSearch(
                        new <?=  $searchModelClass ?>(),
                        $this->request->queryParams
                    ),
                ]
            );<?="\n"?>
        } catch (Throwable $t) {
            <?=$logVar?>->onThrowable($t);
            return $this->renderThrowable($t);
        }
    }

    public function actionView(
        int $id,
        <?=$frontParam?>,
        <?=$logParam?><?="\n"?>
    ): string {<?="\n"?>
        try {<?="\n"?>
            return $this->renderViewAction([
                'model' => $this->findModel($id),
                'actionTitle' => <?=$containerClass?>::NAME,
                'popup'     => <?=$frontVar?>->viewer(),

            ]);<?="\n"?>
        } catch (Throwable $t) {
            <?=$logVar?>->onThrowable($t);
            return $this->renderThrowable($t);
        }
    }

    public function actionCreate(
        <?=$frontParam?>,
        <?=$logParam?>,<?="\n"?>
        <?=$cacheParam?><?="\n"?>
    ): string {<?="\n"?>
        try {<?="\n"?>
            $model = new <?= $modelClass ?>();
            if ($model->dataSave(Yii::$app->request->post())) {
                $this->hideModal = 1;
                <?=$cacheVar?>->flush();
            }

            return $this->renderAjax(
                <?=$frontVar?>->templatePath('_form.php'),
                [
                    'actionTitle' => $this->createTitle(<?=$containerClass?>::NAME),
                    'popup'       => <?=$frontVar?>->creator(),
                    'model'       => $model,
                    'hideModal'   => $this->hideModal,
                ]
            );
        } catch (Throwable $t) {
            <?=$logVar?>->onThrowable($t);
            return $this->renderThrowable($t);
        }
    }

    public function actionUpdate(
        int $id,
        <?=$frontParam?>,
        <?=$logParam?>,<?="\n"?>
        <?=$cacheParam?><?="\n"?>
    ): string {<?="\n"?>
        try {<?="\n"?>
            $model = $this->findModel($id);
            if ($model->dataSave(Yii::$app->request->post())) {
                $this->hideModal = 1;
                <?=$cacheVar?>->flush();
            }

            return $this->renderAjax(
                <?=$frontVar?>->templatePath('_form.php'),
                [
                    'actionTitle' => $this->editTitle(<?=$containerClass?>::NAME),
                    'popup'       => <?=$frontVar?>->editor(),
                    'model'       => $model,
                    'hideModal'   => $this->hideModal,
                ]
            );
        } catch (Throwable $t) {
            <?=$logVar?>->onThrowable($t);
            return $this->renderThrowable($t);
        }
    }

    public function actionDelete(
        int $id,
        <?=$frontParam?>,
        <?=$logParam?>,<?="\n"?>
        <?=$cacheParam?><?="\n"?>
    ): string {<?="\n"?>
        $error = '';
        try {
            $model = $this->findModel($id);
            if (!$model->delete()) {
                $error = $model->getErrorsAsString();
            } else {
                <?=$cacheVar?>->flush();
            }
        } catch (Throwable $t) {
            <?=$logVar?>->onThrowable($t, [$id]);
            $error = $this->renderThrowable($t);
        }

        return $this->renderDeleteAction([
            'title' => $this->deleteTitle(<?=$containerClass?>::NAME),
            'error' => $error,
            'popup' => <?=$frontVar?>->eraser(),
        ]);
    }

    protected function findModel(<?= $actionParams ?>): <?= $modelClass ?>
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        }

        $this->notFoundHttpException();
    }


    public function actionDuplicate(
        int $id,
        <?=$frontParam?>,
        <?=$logParam?>,<?="\n"?>
        <?=$cacheParam?><?="\n"?>
    ): string {<?="\n"?>
        try {<?="\n"?>
            $modelOrigin = $this->findModel($id);
            $copy = $modelOrigin->toArray();
            unset($copy['id'], $copy['cdate'], $copy['udate'], $copy['active']);

            $model = new <?= $modelClass ?>();
            $model->setAttributes($copy);

            if ($model->dataSave(Yii::$app->request->post())) {
                $this->hideModal = 1;
                <?=$cacheVar?>->flush();
            }

            return $this->renderAjax(
                <?=$frontVar?>->templatePath('_form.php'),
                [
                    'actionTitle' => $this->duplicateTitle(<?=$containerClass?>::NAME),
                    'popup'       => <?=$frontVar?>->duplicator(),
                    'model'       => $model,
                    'hideModal'   => $this->hideModal,
                ]
            );
        } catch (Throwable $t) {
            <?=$logVar?>->onThrowable($t);
            return $this->renderThrowable($t);
        }
    }
}
