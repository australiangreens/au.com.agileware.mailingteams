<?php

require_once 'mailingteams.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function mailingteams_civicrm_config(&$config) {
  _mailingteams_civix_civicrm_config($config);

  $arg = array();
  if(isset($_GET[$config->userFrameworkURLVar])) {
    $arg = explode('/', $_GET[$config->userFrameworkURLVar]);
  }

  if (!(CRM_Core_Config::singleton()->userPermissionTemp)) {
    CRM_Core_Config::singleton()->userPermissionTemp = new CRM_MailingTeam_Permission($arg);
  }
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

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
function mailingteams_civicrm_preProcess($formName, &$form) {
  if ($formName == 'CRM_Team_Form_Settings') {
    /* Add the MailingTeams Group and Elements to the form. */
    $form->add_elementGroup('mailingteams', ts('Mailing'));

    $form->add('checkbox', 'mailingteams_restricted', ts('Restricted'));
    $form->add_elementName(
      'mailingteams_restricted', 'mailingteams',
      ts('A restricted Team cannot load or copy Mailings created by another Team.')
    );

    $form->addEntityRef('mailingteams_from_addresses', ts('From Email Addresses'),
      array (
        'entity' => 'option_value',
        'api' => array ('params' => array ('option_group_id' => 'from_email_address')),
        'select' => array ('minimumInputLength' => 0),
        'multiple' => TRUE,
      ));
    $form->add_elementName(
      'mailingteams_from_addresses', 'mailingteams',
      ts('From Email Addresses that this Team is allowed to create Mailings with. The list of available email addresses is administered from <a href="%1">%2</a>',
        array(
          1 => CRM_Utils_System::url('civicrm/admin/options/from_email_address', 'reset=1'),
          2 => ts('the CiviMail "From Email Addresses" page.')
        )));

    // Generate the magic incantation to find only mailing type groups.
    $mailing_type = civicrm_api3('OptionValue', 'getvalue',
      array('option_group_id' => 'group_type', 'name' => 'Mailing List', 'return' => 'value')
    );
    $mailing_type = "%\x01{$mailing_type}\x01%";

    $form->addEntityRef('mailingteams_groups_draft', ts('Draft Groups'),
      array (
        'entity' => 'group',
        'api' => array('params' => array( 'group_type' => array('LIKE' => $mailing_type))),
        'select' => array ('minimumInputLength' => 0),
        'multiple' => TRUE,
      ));
    $form->add_elementName('mailingteams_groups_draft', 'mailingteams', ts('Groups that this Team can Draft Mailings for.'));

    $form->addEntityRef('mailingteams_groups_publish', ts('Publish Groups'),
      array (
        'entity' => 'group',
        'api' => array('params' => array( 'group_type' => array('LIKE' => $mailing_type))),
        'select' => array ('minimumInputLength' => 0),
        'multiple' => TRUE,
      ));
    $form->add_elementName('mailingteams_groups_publish', 'mailingteams', ts('Groups that this Team can Approve and Schedule Mailings for; the Team will also be able to Draft Mailings for these Groups.'));

    // Get and set the MailingTeam defaults.
    if($form->team_id) {
      $team = civicrm_api3('Team','getsingle', array('id' => $form->team_id));
      $tfea = civicrm_api3('TeamMailingFromAddress', 'get', array('team_id' => $form->team_id, 'options' => array('limit' => 0)));
      $tgr  = civicrm_api3('TeamMailingGroup', 'get', array('team_id' => $form->team_id, 'options' => array('limit' => 0)));

      $defaults['mailingteams_from_addresses'] = array();
      $defaults['mailingteams_groups_draft']   = array();
      $defaults['mailingteams_groups_publish'] = array();

      foreach($tfea['values'] as $e) {
        $defaults['mailingteams_from_addresses'][] = $e['from_email_address_id'];
      }
      foreach($tgr['values'] as $g) {
        if($g['role'] == 'draft') {
          $defaults['mailingteams_groups_draft'][] = $g['group_id'];
        }
        elseif($g['role'] == 'publish') {
          $defaults['mailingteams_groups_publish'][] = $g['group_id'];
        }
      }

      $defaults['mailingteams_restricted']     = !empty($team['data']['mailingteams']['restricted']);
      $defaults['mailingteams_from_addresses'] = implode(',', $defaults['mailingteams_from_addresses']);
      $defaults['mailingteams_groups_draft']   = implode(',', $defaults['mailingteams_groups_draft']);
      $defaults['mailingteams_groups_publish'] = implode(',', $defaults['mailingteams_groups_publish']);

      $form->setDefaults($defaults);
    }
  }
  elseif ($formName == 'CRM_Team_Form_Teams') {
    /* Add columns to Team Listing. */
    $selector =& $form->selector();

    $optgroupID = civicrm_api3('OptionGroup', 'getvalue', array('name' => 'from_email_address', 'return' => 'id'));
    $selector->join('tme', 'LEFT JOIN (
SELECT tme1.team_id, GROUP_CONCAT(ov.label ORDER BY ov.label ASC SEPARATOR ", ") `emails`
       FROM civicrm_option_value ov
       INNER JOIN civicrm_team_mailing_email tme1 ON (ov.value = tme1.from_email_address_id AND ov.option_group_id = @optgroupID)
       GROUP BY tme1.team_id
) tme ON tme.team_id = t.id',
      array('optgroupID' => $optgroupID));
    $selector->addColumn('from_email_addresses', ts('From Email Addresses'), 'tme.emails `from_email_addresses`');

    $selector->join('tmg', 'LEFT JOIN (
SELECT tmg1.team_id, GROUP_CONCAT(DISTINCT g.title ORDER BY g.title ASC SEPARATOR ", ") `groups`
       FROM civicrm_group g
       INNER JOIN civicrm_team_mailing_group tmg1 ON(g.id = tmg1.group_id)
       GROUP BY tmg1.team_id
) tmg ON tmg.team_id = t.id');
    $selector->addColumn('groups', ts('Groups'), 'tmg.groups');
  }
}

function mailingteams_civicrm_buildForm($formName, &$form) {
  if($formName == 'CRM_Team_Form_Teams') {
    $form->addEntityRef('mailingteams_from_address', ts('From Email Address'),
      array (
        'entity' => 'option_value',
        'api' => array ('params' => array ('option_group_id' => 'from_email_address')),
        'select' => array ('minimumInputLength' => 0),
      ));

    $mailing_type = civicrm_api3('OptionValue', 'getvalue',
      array('option_group_id' => 'group_type', 'name' => 'Mailing List', 'return' => 'value')
    );
    $mailing_type = "%\x01{$mailing_type}\x01%";

    $form->addEntityRef('mailingteams_groups', ts('Groups'),
      array (
        'entity' => 'group',
        'api' => array('params' => array( 'group_type' => array('LIKE' => $mailing_type))),
        'select' => array ('minimumInputLength' => 0),
        'multiple' => TRUE,
      ));
    $form->addSearchElement(array(
        'title' => ts('Mailing'),
        'children' => array('mailingteams_from_address', 'mailingteams_groups')
      ));
  }
}

function mailingteams_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Team_Form_Settings') {
    $values = $form->exportValues();

    $team = civicrm_api3('Team', 'getsingle', array('id' => $form->team_id));

    $data = &$team['data'];

    // Flag if we need to update the data field of the Team
    $update_data = FALSE;

    if((!empty($values['mailingteams_restricted']) &&  empty($data['mailingteams']['restricted'])) ||
         empty($values['mailingteams_restricted']) && !empty($data['mailingteams']['restricted'])) {
      $data['mailingteams']['restricted'] = !empty($values['mailingteams_restricted']);
      $update_data = TRUE;
    }

    if($update_data) {
      // Yes we have data updates! Apply them.
      civicrm_api3('Team', 'create', array('id' => $form->team_id, 'data' => $data));
    }

    $from_addresses = (!empty($values['mailingteams_from_addresses'])
                      ? explode(',', $values['mailingteams_from_addresses'])
                      : array());
    _mailingteams_update_from_addresses($form->team_id, $from_addresses);

    $draft_groups   = (!empty($values['mailingteams_groups_draft'])
                      ? explode(',', $values['mailingteams_groups_draft'])
                      : array());

    $publish_groups = (!empty($values['mailingteams_groups_publish'])
                      ? explode(',', $values['mailingteams_groups_publish'])
                      : array());

    _mailingteams_update_groups($form->team_id, $draft_groups, $publish_groups);
  }
  elseif ($formName == 'CRM_Team_Form_Teams') {
    $selector =& $form->selector();

    if ($group_ids = CRM_Utils_Request::retrieve('mailingteams_groups', 'String')) {
      $selector->where(
        'EXISTS (SELECT 1 FROM civicrm_team_mailing_group WHERE group_id IN (@group_id) AND team_id = t.id)',
        array('group_id' => $group_ids)
      );
    }

    if ($email_id = CRM_Utils_Request::retrieve('mailingteams_from_address', 'String')) {
      $selector->where(
        'EXISTS (SELECT 1 FROM civicrm_team_mailing_email WHERE from_email_address_id IN (@email_id) AND team_id = t.id)',
        array('email_id' => $email_id)
      );
    }
  }
}

/**
 * "Internal" function for updating from addresses from a list of from address ids.
 *
 * @param int $team_id
 * @param array $from_addresses
 *
 **/
function _mailingteams_update_from_addresses($team_id, $from_addresses) {
  try {
    $team_from_addresses = civicrm_api3('TeamMailingFromAddress', 'get', array('team_id' => $team_id));
    $team_from_addresses = $team_from_addresses['values'];
  }
  catch(CiviCRM_API3_Exception $e){
    $team_from_addresses = array();
  }

  $tfa_match = array();

  // Start with known address ids.
  foreach($team_from_addresses as $id => $tfa) {
    if (!in_array($tfa['from_email_address_id'], $from_addresses)) {
      // Delete those that do not match input.
      civicrm_api3('TeamMailingFromAddress', 'delete', array('id' => $id));
    }
    else {
      // Mark those that match input as not requiring update.
      $tfa_match[$tfa['from_email_address_id']] = TRUE;
    }
  }

  // Loop through input to find new addresses ids
  foreach($from_addresses as $eid) {
    if(empty($tfa_match[$eid])) {
      civicrm_api3('TeamMailingFromAddress', 'create', array('team_id' => $team_id, 'from_email_address_id' => $eid));
    }
  }
}

/**
 * "Internal" function for updating groups links from lists of from group ids.
 *
 * @param int $team_id
 * @param array $draft_groups
 * @param array $publish_groups
 *
 **/
function _mailingteams_update_groups($team_id, $draft_groups, $publish_groups) {
  try {
    $team_groups = civicrm_api3('TeamMailingGroup', 'get', array('team_id' => $team_id));
    $team_groups = $team_groups['values'];
  }
  catch(CiviCRM_API3_Exception $e){
    $team_groups = array();
  }

  $dgr_match = array();
  $pgr_match = array();

  // Start with known groups.
  foreach($team_groups as $id => $tgr) {
    switch($tgr['role']){
      case 'draft':
        if (!in_array($tgr['group_id'], $draft_groups)) {
          // Delete those that do not match input.
          civicrm_api3('TeamMailingGroup', 'delete', array('id' => $id));
        }
        else {
          // Mark as not requiring update.
          $dgr_match[$tgr['group_id']] = TRUE;
        }
        break;
      case 'publish':
        if (!in_array($tgr['group_id'], $publish_groups)) {
                  // Delete those that do not match input.
          civicrm_api3('TeamMailingGroup', 'delete', array('id' => $id));
        }
        else {
          // Mark as not requiring update.
          $pgr_match[$tgr['group_id']] = TRUE;
        }
        break;
      default:
        break;
    }
  }

  // Loop through input to find new groups' ids
  foreach($draft_groups as $gid) {
    if(empty($dgr_match[$gid])) {
      civicrm_api3('TeamMailingGroup', 'create', array('team_id' => $team_id, 'group_id' => $gid, 'role' => 'draft'));
    }
  }
  foreach($publish_groups as $gid) {
    if(empty($pgr_match[$gid])) {
      civicrm_api3('TeamMailingGroup', 'create', array('team_id' => $team_id, 'group_id' => $gid, 'role' => 'publish'));
    }
  }
}

function mailingteams_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  // Recursion guard
  static $in_wrap = FALSE;

  if(!$in_wrap && (($apiRequest['entity'] == 'Group' && isset($apiRequest['params']['params']['forMailing']))
        || $apiRequest['entity'] == 'Mailing' || $apiRequest['entity'] == 'OptionValue')) {
    $in_wrap = TRUE;

    if(!CRM_Core_Permission::check('access CiviMail') && !CRM_Core_Permission::check('administer teams')) {
      $wrappers[] = new CRM_MailingTeam_APIWrapper();
    }

    $in_wrap = FALSE;
  }
}

function mailingteams_civicrm_selectWhereClause($entity, &$clauses) {
  static $contact_id = NULL;

  if($contact_id === NULL){
    $contact_id = CRM_Core_Session::getLoggedInContactID();
  }

  if(CRM_Core_Permission::check('access CiviMail')) {
    return;
  }

  if(CRM_Team_BAO_TeamMailingGroup::$doAclCheck && $entity == 'Group') {
     $clauses['id'][] = 'IN (SELECT tmg.group_id FROM civicrm_team_mailing_group tmg INNER JOIN civicrm_team_contact tc USING(team_id) WHERE tc.contact_id = ' . $contact_id . ')';
  }

  if($entity == 'Mailing' && !CRM_Core_Permission::check('administer teams')) {
    // @TODO expand to restrict by team that saved the mailing when ready (no interface yet )
    $sqlstr = <<<'EOS'
IN (SELECT m.id FROM civicrm_mailing m
           LEFT JOIN civicrm_mailing_group mg ON m.id = mg.mailing_id AND mg.entity_table = 'civicrm_group'
           LEFT JOIN civicrm_team_mailing_group tmg ON tmg.group_id = mg.entity_id
           LEFT JOIN civicrm_team_contact tc ON tmg.team_id = tc.team_id
               WHERE mg.id is NULL OR tc.contact_id = %1$d
)
EOS;
    $clauses['id'][] = sprintf($sqlstr, $contact_id);
  }
}

function _mailingteams_fea_id($option_value_id) {
  static $fea_id = NULL;

  if(empty($fea_id)) {
    $bao = new CRM_Core_BAO_OptionGroup;
    $bao->name = 'from_email_address';
    $bao->find(1);

    $fea_id = $bao->id;
  }

  return ($option_value_id == 'from_email_address') || ($option_value_id == $fea_id);
}

function mailingteams_civicrm_team_permissions($entity_table, $entity_id, $action, $contact_id, &$permissions) {
  if($entity_table == 'from_email_address' && !CRM_Core_Permission::check('access CiviMail')) {
    $addresses = civicrm_api3(
      'TeamMailingFromAddress', 'get',
      ['from_email_address_id' => $entity_id, 'options' => ['limit' => 0]]
    );
    $count = civicrm_api3(
      'TeamContact', 'getcount',
      [ 'contact_id' => $contact_id,
        'team_id' => [ 'IN' => array_map(function($v){ return $v['team_id']; }, $addresses['values'])]
      ]
    );

    $permissions[] = !!$count;
  }
  if ($entity_table == 'civicrm_mailing' && !CRM_Core_Permission::check('access CiviMail')) {
    $permissions[] = CRM_MailingTeam_Permission::canMail($entity_id, $action, $contact_id);
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
