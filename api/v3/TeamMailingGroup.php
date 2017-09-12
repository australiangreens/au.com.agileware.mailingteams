<?php

/**
 * TeamMailingGroup.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_team_mailing_group_create_spec(&$spec) {
  // $spec['some_parameter']['api.required'] = 1;
}

/**
 * TeamMailingGroup.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_team_mailing_group_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * TeamMailingGroup.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_team_mailing_group_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * TeamMailingGroup.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_team_mailing_group_get($params) {
  $bao = _civicrm_api3_get_BAO(__FUNCTION__);
  $sql = new CRM_Utils_SQL_Select(NULL);

  if(isset($params['options']['group_by'])) {
    $group_by = $params['options']['group_by'];
    if(!is_array($group_by)) {
      $group_by = explode(',', $group_by);
    }
    foreach($group_by as $g){
      if (in_array($g, array_keys($bao::fields()))) {
        $sql->groupBy($g);
      }
    }
  }

  return _civicrm_api3_basic_get($bao, $params, TRUE, '', $sql);
}
