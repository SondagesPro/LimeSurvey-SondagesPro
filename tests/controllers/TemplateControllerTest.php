<?php

namespace ls\tests\controllers;

use ls\tests\TestBaseClass;
use ls\tests\DummyController;

/**
 * @since 2017-10-15
 * @group tempcontr
 * @group template
 */
class TemplateControllerTest extends TestBaseClass
{

    /**
     * Test copy a template.
     * @group copytemplate
     */
    public function testCopyTemplate()
    {
        \Yii::app()->session['loginID'] = 1;
        \Yii::import('application.controllers.admin.templates', true);
        \Yii::import('application.helpers.globalsettings_helper', true);

        // Clean up from last test.
        $templateName = 'foobartest';
        \TemplateConfiguration::uninstall($templateName);
        \Template::model()->deleteAll('name = \'foobartest\'');
        \Permission::model()->deleteAllByAttributes(array('permission' => $templateName,'entity' => 'template'));

        // Remove folder from last test.
        $newname = 'foobartest';
        $newdirname  = \Yii::app()->getConfig('userthemerootdir') . "/" . $newname;
        if (file_exists($newdirname)) {
            exec('rm -r ' . $newdirname);
        }

        // Simulate a POST.
        $_POST['newname'] = $newname;
        $_POST['copydir'] = 'default';
        $_SERVER['SERVER_NAME'] = 'localhost';

        $contr = new \templates(new \ls\tests\DummyController('dummyid'));
        $contr->templatecopy();

        $flashes = \Yii::app()->user->getFlashes();
        $this->assertEmpty($flashes, 'No flash messages');

        $template = \Template::model()->find('name = \'foobartest\'');
        $this->assertNotEmpty($template);
        $this->assertEquals($templateName, $template->name);

        // Clean up.
        \Template::model()->deleteAll('name = \'foobartest\'');
    }

    /**
     * @todo Copy template folder that does not exist.
     */
    /*
    public function testCopyWrongFolder()
    {
    }
     */
}
