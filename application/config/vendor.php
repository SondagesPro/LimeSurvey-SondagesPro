<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/** @var array $userConfig */

/**
 * This file contains package definition for third party libraries.
 * Defining them here allows for easy inclusion in views.
 */
/* Tag if debug is set : debug is set in user config file and this file is directly required in internal.php where $userConfig var arry is set */
/* This allow us to use minified version according to debug */
$debug = isset($userConfig['config']['debug']) ? $userConfig['config']['debug'] : 0;
/* To add more easily min version : config > 2 , seems really an core dev issue to fix bootstrap.js ;) */
$minVersion = ($debug > 0) ? "" : ".min";
$minFolder = ($debug > 0) ? "/dev" : "/min";

/* Please : comment the reason, mantis bug link: ajax don't need any package if i don't make error */
/* Ajax must renderPartial (better : always return json) and never render and don't registerScript (IMHO) / Shnoulle on 2016-11-16 */
if (isset($_GET['isAjax'])) {
    return array();
}

return array(

    // jQuery
    'jquery' => array(
        'devBaseUrl' => 'assets/packages/jquery',
        'basePath' => 'core.jquery',
        'position' =>CClientScript::POS_HEAD,
        'js' => array(
            'jquery-3.6.1'.$minVersion.'.js',
            'jquery-migrate-3.4.0'.$minVersion.'.js',
        )
    ),

    // Bootstrap
    // This package replace the Yiistrap register() function
    // Then instead of using the composer dependency system for themes
    // We can use the package dependency system (easier for now)
    'bootstrap' => array(
        'devBaseUrl' => 'assets/packages/bootstrap/',
        'basePath' => 'core.bootstrap',
        'css'=> array(
            'bootstrap'.$minVersion.'.css', /* Admin need it, not public */
            'yiistrap'.$minVersion.'.css',
        ),
        'js'=>array(
            'bootstrap'.$minVersion.'.js',
            'plugins/bootstrapconfirm/bootstrapconfirm'.$minVersion.'.js'
        ),
        'depends' => array(
            'jquery',
        )
    ),

    // Bootstrap admin
    // must be different for theme editor not to colide with theme files replacement
    'bootstrap-admin' => array(
        'devBaseUrl' => 'assets/packages/bootstrap/',
        'basePath' => 'core.bootstrap',
        'css'=> array(
            'bootstrap'.$minVersion.'.css', /* Admin need it, not public */
            'yiistrap'.$minVersion.'.css',
        ),
        'js'=>array(
            'bootstrap'.$minVersion.'.js'
        ),
        'depends' => array(
            'jquery',
        )
    ),

    // bootstrap-slider : for multinumeric with slider
    'bootstrap-slider' => array(
        'devBaseUrl' => 'assets/packages/bootstrap/plugins/slider',
        'basePath' => 'core.bootstrap.plugins.slider',
        'position' => CClientScript::POS_BEGIN,
        'css'=> array(
            'css/bootstrap-slider'.$minVersion.'.css'
        ),
        'js' => array(
            'bootstrap-slider'.$minVersion.'.js'
        ),
        'depends' => array(
            'jquery',
            'bootstrap'
        )
    ),

    // Bootstrap Multiselect
    'bootstrap-multiselect' => array(
        'devBaseUrl' => 'assets/packages/bootstrap/plugins/multiselect',
        'basePath' => 'core.bootstrap.plugins.multiselect',
        'position' => CClientScript::POS_BEGIN,
        'js' => array(
            'js/bootstrap-multiselect.js',
        ),
        'css' => array(
            'css/bootstrap-multiselect.css',
        ),
        'depends' => array(
            'jquery',
            'bootstrap'
        )
    ),

    // Bootstrap Multiselect2
    'bootstrap-select2' => array(
        'devBaseUrl' => 'assets/packages/bootstrap/plugins/select2',
        'basePath' => 'core.bootstrap.plugins.select2',
        'js' => array(
            'js/select2.full'.$minVersion.'.js',
        ),
        'css' => array(
            'css/select2.css',
            'css/select2-bootstrap.css',
        ),
        'depends' => array(
            'jquery',
            'bootstrap'
        )
    ),

    'bootstrap-datetimepicker' => array(
        'devBaseUrl' => 'assets/packages/bootstrap/plugins/datetimepicker/build',
        'basePath' => 'core.bootstrap.plugins.datetimepicker.build',
        'position' => CClientScript::POS_BEGIN,
        'css' => array(
            'css/bootstrap-datetimepicker'.$minVersion.'.css'
        ),
        'js' => array(
            'js/bootstrap-datetimepicker.min.js'
        ),
        'depends' => array(
            'jquery',
            'bootstrap',
            'moment'
        )
    ),

    'bootstrap-switch' => array(
        'devBaseUrl' => 'assets/packages/bootstrap/plugins/switch/',
        'basePath' => 'core.bootstrap.plugins.switch',
        'position' => CClientScript::POS_BEGIN,
        'css' => array(
            'css/bootstrap-switch'.$minVersion.'.css'
        ),
        'js' => array(
            'js/bootstrap-switch'.$minVersion.'.js'
        ),
        'depends' => array(
            'jquery',
            'bootstrap',
            'moment'
        )
    ),

    // jQuery UI
    'jqueryui' => array(
        'devBaseUrl' => 'assets/packages/jquery-ui',
        'basePath' => 'core.jquery-ui',
        'position' =>CClientScript::POS_HEAD,
        'js' => array(
            'jquery-ui'.$minVersion.'.js',
        ),
        'css' => array(
            'jquery-ui.structure.css', /* else autocomplete or other broken */
        ),
        'depends' => array(
            'jquery',
        )
    ),

    // jquery bindWithDelay
    'jquery-bindWithDelay' => array(
        'basePath' => 'core.jquery-bindWithDelay-ui',
        'js' => array(
            'bindWithDelay.js'
        ),
        'depends' => array(
            'jquery'
        )
    ),

    // jQuery Cookie
    'js-cookie' => array(
        'basePath' => 'core.js-cookie',
        'js' => array(
            'js.cookie.js'
        )
    ),

    // jQuery json
    'jquery-json' => array(
        'basePath' => 'core.jquery-json',
        'js' => array(
            'jquery.json-2.4.min.js'
        ),
        'depends' => array(
            'jquery'
        )
    ),

    // jQuery blockUI
    'jquery-blockUI' => array(
        'basePath' => 'core.jquery-blockui',
        'js' => array(
            'jquery.blockUI.js'
        ),
        'depends' => array(
            'jquery'
        )
    ),

    // jQuery Table Sorter
    'jquery-tablesorter' => array(
        'basePath' => 'core.jquery-tablesorter',
        'js' => array(
            'jquery.tablesorter.min.js'
        ),
        'depends' => array(
            'jquery'
        )
    ),

    // jQuery NestedSortable
    'jquery-nestedSortable' => array(
        'basePath' => 'core.jquery-nestedSortable',
        'position' => CClientScript::POS_BEGIN,
        'js' => array(
            'jquery.mjs.nestedSortable.js'
        ),
        'depends' => array(
            'jqueryui'
        )
    ),

    // Ace
    'ace' => array(
        'devBaseUrl' => 'assets/packages/ace',
        'basePath' => 'core.ace',
        'position' => CClientScript::POS_BEGIN,
        'js' => array(
            $minFolder.'/ace.js'
        ),
        'depends' => array(
            'jquery-ace'
        )
    ),

    // jQuery Ace
        'jquery-ace' => array(
            'devBaseUrl' => 'assets/packages/jquery-ace',
            'basePath' => 'core.jquery-ace',
            'position' => CClientScript::POS_BEGIN,
        'js' => array(
            'jquery.ace.js',
        ),
        'depends' => array(
            'jquery',
        )
    ),

    // jQuery selectboxes
    'jquery-selectboxes' => array(
        'basePath' => 'core.jquery-selectboxes.selectboxes',
        'js' => array(
            'jquery.selectboxes.js'
        ),
        'depends' => array(
            'jquery'
        )
    ),

    // jQuery touch punch : seems uneended now ?
    'jquery-touch-punch' => array(
        'basePath' => 'core.jquery-touch-punch',
        'js' => array(
            'jquery.ui.touch-punch.min.js'
        ),
        'depends' => array(
            'jqueryui'
        )
    ),

    // Decimal.js calculate in js
    'decimal' => array(
        'position' => CClientScript::POS_BEGIN,
        'devBaseUrl' => 'assets/packages/decimal',
        'basePath' => 'core.decimal',
        'js' => array(
            'decimal.js'
        ),
        'depends' => array(
        )
    ),

    // Moment.js use real simple dateTime modification
    'moment' => array(
        'devBaseUrl' => 'assets/packages/moment',
        'basePath' => 'core.moment',
        'js' => array(
            'moment-with-locales'.$minVersion.'.js'
        ),
        'depends' => array(
        )
    ),

    // leaflet, needed for short text question with map (OSM)

    'jsuri' => array(
        'basePath' => 'core.jsUri',
        'js' => array(
            'Uri.js'
        ),
    ),

    'jquery-datatable' => array(
        'basePath' => 'core.datatables',
        'position' => CClientScript::POS_BEGIN,
        'css' => array(
            'css/datatables'.$minVersion.'.css'
        ),
        'js' => array(
            'js/jquery.dataTables'.$minVersion.'.js',
            'js/dataTables.bootstrap'.$minVersion.'.js'
        ),
        'depends' => array(
            'jquery',
            'bootstrap'
        )
    ),
    'es6promise' => array(
        'basePath' => 'core.es6promise',
        'js' => array(
            'es6-promise.auto.min.js'
        )
    ),

    'dom2image' => array(
        'basePath' => 'core.dom-to-image',
        'js' => array(
            'dist/dom-to-image.min.js',
        )
    ),

    'jspdf' => array(
        'basePath' => 'core.jspdf',
        'position' => CClientScript::POS_BEGIN,
        'js' => array(
            'jspdf.min.js',
            'createpdf_worker.js'
        ),
        'depends' => array(
            'dom2image',
            'es6promise',
            'jquery',
            'bootstrap'
        )
    ),
    /* Used by ranking question type */
    'sortable' => array(
        'devBaseUrl' => 'assets/packages/sortable',
        'basePath' => 'core.sortable', /* for sorting ability */
        'position' => CClientScript::POS_BEGIN,
        'js' => array(
            'jquery.fn.sortable'.$minVersion.'.js'
            )
        ),
    'jquery-actual' => array(
        'position' => CClientScript::POS_BEGIN,
        'devBaseUrl' => 'assets/packages/jquery-actual',
        'basePath' => 'core.jquery-actual', /* for samechoiceheight/samelistheight */
        'js' => array(
            'jquery.actual'.$minVersion.'.js'
        ),
    ),
    /* Used by short text with map by leaflet */
    'leaflet' => array(
        'basePath' => 'core.leaflet',
        'position' => CClientScript::POS_BEGIN,
        'js' => array(
            'leaflet.js'
        ),
        'css' => array(
            'leaflet.css'
        ),
    ),
    'devbridge-autocomplete' => array(
        'basePath' => 'core.devbridge-autocomplete.dist', /* For geoname search autocomplete without jquery */
        'position' => CClientScript::POS_BEGIN,
        'js' => array(
            'jquery.autocomplete'.$minVersion.'.js'
        ),
    ),
    'jszip' => array(
        'basePath' => 'core.jszip',
        'position' => CClientScript::POS_BEGIN,
        'js' => array(
            'jszip.js',
            'fileSaver.js',
        ),
        'depends' => array(
            'jquery',
        )
    )
);
