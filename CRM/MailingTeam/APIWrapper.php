<?php

class CRM_MailingTeam_APIWrapper implements API_Wrapper {
  public function fromAPIInput($apiRequest) {
    unset($apiRequest['params']['params']['forMailing']);
    if($apiRequest['entity'] == 'Group') {
      $apiRequest['entity'] = 'TeamMailingGroup';
      $apiRequest['params'] = array_merge(
        $apiRequest['params'],
        [
          'id_field' => 'group_id',
          'label_field' => 'group_id.title',
          'search_field' => 'group_id.title',
          'description_field' => 'group_id.description',
        ]
      );
      $apiRequest['params']['params']['options'] = (
        !empty($apiRequest['params']['params']['options'])
        ? array_merge($apiRequest['params']['params']['options'], [ 'group_by' => 'group_id' ])
        : [ 'group_by' => 'group_id' ]
      );
    }
    CRM_Core_Error::debug_var('apiRequest', $apiRequest);
    return $apiRequest;
  }

  public function toApiOutput($apiRequest, $result){
    //CRM_Core_Error::debug_var('apiRequest, $result', [$apiRequest, $result]);
    return $result;
  }
}