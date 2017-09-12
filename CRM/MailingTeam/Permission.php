<?php
/**
 * Overrides certain permission checks in CiviCRM based on Team membership.
 */
class CRM_MailingTeam_Permission extends CRM_Core_Permission_Temp {
  private $wrapped;

  private $arg;

  function __construct($arg, $wrapping = NULL) {
    $this->arg = $arg;

    if(is_a($wrapping, 'CRM_Core_Permission_Temp')) {
      $this->wrapped = $wrapping;
    }
  }

  function check($permission) {
    // We only care about the 'approve mailings' permission.
    if ($permission != 'approve mailings') {
      return ($this->wrapped ? $this->wrapped->check($permission) : FALSE);
    }

    // Pretend we can approve anything on the browse URL
    if ($this->arg[2] == 'browse') {
      return TRUE;
    }

    // Get Mailing ID from form.
    $qfKey = CRM_Utils_Request::retrieve('qfKey', 'String');
    $mid = 0;

    if($qfKey) {
      $vars = array();
      CRM_Core_Session::singleton()->getVars($vars, 'CRM_Mailing_Form_Approve_' . $qfKey);
      // Some forms use mailing_id instead
      $mid = !empty($vars['mid'])? $vars['mid'] : $vars['mailing_id'];
    }
    else {
      // Mailing ID - must exist in request.
      $mid = CRM_Utils_Request::retrieve('mid', 'Positive');
    }

    if (!$mid) {
      return FALSE;
    }

    return (CRM_Team_BAO_Team::checkPermissions('civicrm_mailing', $mid, 'approve'));
  }

  public static function canMail($mid, $action = 'draft', $contact_id = 0) {
    $approve = FALSE;
    try {
      if(!$contact_id) {
        $contact_id = CRM_Core_Session::singleton()->getLoggedInContactID();
      }

      // Get the from_email address of the email in question
      $from_email = civicrm_api3('Mailing', 'getvalue', array('id' => $mid, 'return' => 'from_email'));

      // Mailing does not store the "From Email" ID - speculate based on the address.  This may return multiple results.
      $emails = civicrm_api3('OptionValue', 'get', array(
                  'option_group_id' => 'from_email_address',
                  'domain_id' => CRM_Core_Config::singleton()->domainID(),
                  'label' => array('LIKE' => "%<{$from_email}>%"),
                  'return' => 'value',
                ));

      // Loop through all possible approver lists.
      foreach($emails['values'] as $e) {
        if(CRM_Team_BAO_Team::checkPermissions('from_email_address', $e['value'], 'approve', $contact_id)) {
          $approve = TRUE;
          continue;
        }
      }

      if ($approve) {
        $mgroups = civicrm_api3(
          'MailingGroup', 'get',
          [ 'mailing_id'   => $mid,
            'entity_table' => 'civicrm_group',
            'sequential'   => 1,
            'options'      => ['limit' => 0]
          ]);

        $params = [
          'options' => ['limit' => 0 ],
        ];

        if($action == 'approve') {
          $params['role'] = 'publish';
        }

        $params['group_id'] = [ 'IN' => array_map(function($mg) { return $mg['entity_id']; }, $mgroups['values']) ];

        $tmgroups = civicrm_api3('TeamMailingGroup', 'get', $params);

        $approve = !!$tmgroups['count'];

        $count = array ();

        foreach($tmgroups['values'] as $tmg){
          $count[$tmg['group_id']] += civicrm_api3(
            'TeamContact', 'getcount', [ 'contact_id' => $contact_id, 'team_id' => $tmg['team_id']]
          );

          CRM_Core_Error::debug_log_message(json_encode($count) .  " / {$tmg['team_id']} / {$tmg['group_id']}");
        }

        $approve = $approve && !in_array(0, $count);
      }
    }
    catch (Exception $e) { }

    return $approve;
  }
}