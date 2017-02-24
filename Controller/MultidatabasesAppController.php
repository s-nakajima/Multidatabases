<?php
/**
 * MultidatabasesAppController Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * MultidatabasesAppController Controller
 *
 * @author Tomoyuki OHNO (Ricksoft, Inc) <ohno.tomoyuki@ricksoft.jp>
 * @package NetCommons\Multidatabases\Controller
 */
class MultidatabasesAppController extends AppController {

    /**
     * 使用するComponent
     *
     * @var array
     */
    public $components = array(
        'Pages.PageLayout',
        'Security',
    );

    /**
     * @var array use model
     */
    public $uses = array(
        'Multidatabases.Multidatabase',
        'Multidatabases.MultidatabaseSetting',
        'Multidatabases.MultidatabaseFrameSetting'
    );


}
