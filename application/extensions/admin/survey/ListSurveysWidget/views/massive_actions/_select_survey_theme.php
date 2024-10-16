<?php
/** @var AdminController $this */

?>

<?php $form = $this->beginWidget('CActiveForm', array('id'=>'survey-theme',)); ?>

<div id='select-theme-modal' >
    <label class="" for='theme'><?php  eT("Survey theme:"); ?></label>
        <div class=" ">
            <select id='theme' class="form-control custom-data"  name='theme' >
                <?php
                    $athemeList = Template::getTemplateList();
                    foreach ($athemeList as $themeName => $folder) {
                        if (Permission::model()->hasGlobalPermission('themes','read') || Permission::model()->hasTemplatePermission($themeName) ) { ?>
                            <option value='<?php echo CHtml::encode($themeName); /* sanitize_filename aloow single quote */?>'>
                                <?php echo CHtml::encode($themeName); ?>
                            </option>
                            <?php }
                        }
                ?>
            </select>
        </div>
        <?php eT('This will update the survey theme for all selected active surveys.').' '.eT('Continue?'); ?>
</div>
<?php $this->endWidget(); ?>
