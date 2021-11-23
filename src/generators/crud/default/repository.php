<?php

/* @deprecated Generate on model generate */
/* @var $modelNs string */
/* @var $repositoryClassName string */ ?>

<?php echo '<?php' ?>

namespace <?php echo $modelNs ?>;

use Triangulum\Yii\ModuleContainer\System\Db\RepositoryBase;

class <?php echo $repositoryClassName ?> extends RepositoryBase
{
    public const ID = "<?php echo lcfirst($repositoryClassName) ?>";
}
