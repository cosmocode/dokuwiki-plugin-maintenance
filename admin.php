<?php
/**
 * DokuWiki Plugin autologoff (Admin Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <gohr@cosmocode.de>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class admin_plugin_maintenance extends DokuWiki_Admin_Plugin {
    /** @var  helper_plugin_maintenance */
    private $helper;

    public function __construct() {
        $this->helper = $this->loadHelper('maintenance', false);
    }

    /**
     * @return int sort number in admin menu
     */
    public function getMenuSort() {
        return 600;
    }

    /**
     * Should carry out any processing required by the plugin.
     */
    public function handle() {

    }

    /**
     * Render HTML output, e.g. helpful text and a form
     */
    public function html() {
        global $ID;

        echo $this->locale_xhtml('intro');

        if(empty($_REQUEST['run'])) {
            $form = new Doku_Form(array('action' => script(), 'method' => 'post'));
            $form->addHidden('id', $ID);
            $form->addHidden('page', 'maintenance');
            $form->addHidden('run', 'dry');
            $form->addElement(form_makeButton('submit', 'admin', $this->getLang('preview')));
            $form->printForm();
        } else {

            $this->helper->run();
            echo '<ul class="maintenance_files">';
            foreach($this->helper->list as $file) {
                echo '<li><div class="li">' . hsc($file) . '</div></li>';
            }
            echo '</ul>';
            echo '<p>' . sprintf($this->getLang('sum'), count($this->helper->list), filesize_h($this->helper->size)) . '</p>';

            if($_REQUEST['run'] == 'dry') {
                $form = new Doku_Form(array('action' => script(), 'method' => 'post'));
                $form->addHidden('id', $ID);
                $form->addHidden('page', 'maintenance');
                $form->addHidden('run', 'real');
                $form->addElement(form_makeButton('submit', 'admin', $this->getLang('execute')));
                $form->printForm();
            } else {
                echo '<p>' . $this->getLang('done') . '</p>';
            }
        }

    }
}

// vim:ts=4:sw=4:et: