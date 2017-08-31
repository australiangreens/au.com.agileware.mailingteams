<?php

require_once 'mailingteams.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function mailingteams_civicrm_config(&$config) {
  _mailingteams_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function mailingteams_civicrm_xmlMenu(&$files) {
  _mailingteams_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function mailingteams_civicrm_install() {
  _mailingteams_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function mailingteams_civicrm_uninstall() {
  _mailingteams_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function mailingteams_civicrm_enable() {
  _mailingteams_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function mailingteams_civicrm_disable() {
  _mailingteams_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function mailingteams_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _mailingteams_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function mailingteams_civicrm_managed(&$entities) {
  _mailingteams_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function mailingteams_civicrm_caseTypes(&$caseTypes) {
  _mailingteams_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function mailingteams_civicrm_angularModules(&$angularModules) {
_mailingteams_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function mailingteams_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _mailingteams_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function mailingteams_civicrm_entityTypes(&$entityTypes) {
  $entityFiles = _mailingteams_civix_find_files(__DIR__, '*.entityType.php');
  foreach ($entityFiles as $file) {
    $et = include $file;
    foreach ($et as $e) {
      $entityTypes[$e['class']] = $e;
    }
  }
}

function mailingteams_civicrm_preProcess($formName, &$form) {
  if ($formName == 'CRM_Team_Form_Settings') {
    $form->add_elementGroup('mailingteams', ts('Mailing'));

    $form->add('checkbox', 'mailingteams.restricted', ts('Restricted'));
    $form->add_elementName(
      'mailingteams.restricted', 'mailingteams',
      ts('A restricted Team cannot load or copy Mailings created by another Team.')
    );

    $form->addEntityRef('mailingteams.from_addresses', ts('From Email Addresses'),
      array (
        'entity' => 'option_value',
        'api' => array ('params' => array ('option_group_id' => 'from_email_address')),
        'select' => array ('minimumInputLength' => 0),
        'multiple' => TRUE,
      ));
    $form->add_elementName(
      'mailingteams.from_addresses', 'mailingteams',
      ts('From Email Addresses that this Team is allowed to create Mailings with. The list of available email addresses is administered from <a href="%1">%2</a>',
        array(
          1 => CRM_Utils_System::url('civicrm/admin/options/from_email_address', 'reset=1'),
          2 => ts('the CiviMail "From Email Addresses" page.')
        )));

    // Generate the magic incantation to find only mailing type groups.
    // @TODO stab my eyeballs out with a spoon.
    $mailing_type = civicrm_api3('OptionValue', 'getvalue',
      array('option_group_id' => 'group_type', 'name' => 'Mailing List', 'return' => 'value')
    );
    $mailing_type = "%\x01{$mailing_type}\x01%";

    $form->addEntityRef('mailingteams.groups_draft', ts('Draft Groups'),
      array (
        'entity' => 'group',
        'api' => array('params' => array( 'group_type' => array('LIKE' => $mailing_type))),
        'multiple' => TRUE,
      ));
    $form->add_elementName('mailingteams.groups_draft', 'mailingteams', ts('Groups that this Team can draft Mailings for.'));

    $form->addEntityRef('mailingteams.groups_publish', ts('Publish Groups'),
      array (
        'entity' => 'group',
        'api' => array('params' => array( 'group_type' => array('LIKE' => $mailing_type))),
        'multiple' => TRUE,
      ));
    $form->add_elementName('mailingteams.groups_publish', 'mailingteams', ts('Groups that this Team can approve and Schedule for Mailings.'));
  }
}

function mailingteams_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Team_Form_Settings') {
    $values = $form->exportValues();

    $team = civicrm_api3('Team', 'getsingle', array('id' => $values['team_id']));
  }
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function mailingteams_civicrm_navigationMenu(&$menu) {
  _mailingteams_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'au.com.agileware.mailingteams')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _mailingteams_civix_navigationMenu($menu);
} // */
