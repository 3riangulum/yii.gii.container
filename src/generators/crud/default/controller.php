<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */
/* @var $frontClass string */
/* @var $repositoryClassNameFull string */

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
/* @var $repositoryClass string */
/* @var $repositoryParam string */
/* @var $repositoryVar string */

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

<?=$frontUse?><?="\n"?>

/**
* @property \<?=$repositoryClassNameFull?> $repository
* @property <?php echo $frontClass?> $front
*/
class <?= $controllerClass ?> extends ControllerWeb <?= "\n" ?>
{<?="\n"?>

    protected string $frontClass = <?php echo $frontClass?>::class;

    public function actionIndex(): string {<?="\n"?>
        try {<?="\n"?>
            return $this->render(
                $this->front->template('index.php'),
                [
                    'pageTitle' => $this->front->getTitle(),
                    'uiCreator' => $this->front->creator(),
                    'grid'      => $this->front->grid($this->request->queryParams),
                ]
            );<?="\n"?>
        } catch (Throwable $t) {
            Yii::$app->telegramService->sendAdmin($t->getMessage());
            return $this->renderThrowable($t);
        }
    }

    public function actionCreate(): string {<?="\n"?>
        try {<?="\n"?>
            $entity = $this->repository->create();
            $ui = $this->front->creator();
            if ($this->isPost() && $this->repository->dataSave($entity, $this->getPost())) {
                return $this->renderNotifyAction($ui->exportViewSuccessData());
            }

            return $this->renderAjax(
                $this->front->template('_form.php'),
                ['form' => $this->front->loadCrudForm($entity, $ui)]
            );
        } catch (Throwable $t) {
            Yii::$app->telegramService->sendAdmin($t->getMessage());
            return $this->renderThrowable($t);
        }
    }

    public function actionUpdate(int $id): string {<?="\n"?>
        try {<?="\n"?>
            $entity = $this->repository->single($id);
            $ui = $this->front->editor();
            if ($this->isPost() && $this->repository->dataSave($entity, $this->getPost())) {
            return $this->renderNotifyAction($ui->exportViewSuccessData());
            }

            return $this->renderAjax(
                $this->front->template('_form.php'),
                ['form' => $this->front->loadCrudForm($entity, $ui)]
            );
        } catch (Throwable $t) {
            Yii::$app->telegramService->sendAdmin($t->getMessage());
            return $this->renderThrowable($t);
        }
    }

    public function actionDelete( int $id, \<?=$repositoryClassNameFull?> $repository ): string {<?="\n"?>

        $error = '';
        try {
            $entity = $this->repository->single($id);
            if (!$this->repository->delete($entity)) {
                $error = $entity->errorsToString();
            }
        } catch (Throwable $t) {
            $error = $this->renderThrowable($t);
        }

        return $this->renderDeleteAction([
            'error' => $error,
            'popup' => $this->front->eraser(),
        ]);
    }
}
