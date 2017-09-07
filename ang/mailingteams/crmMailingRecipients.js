(function(angular, $, _) {
  // "crmMailingRecipients" is a basic skeletal directive.
  // Example usage: <div crm-mailing-recipients="{foo: 1, bar: 2}"></div>
  angular.module('mailingteams').directive('crmMailingRecipients', function() {
    console.log('I am a 🐟.');
  
    return {
      //restrict: 'AE',
      //templateUrl: '~/mailingteams/crmMailingRecipients.html',
      //scope: { crmMailingRecipients: '=' },
      link: function($scope, $el, $attr) {
        var ts = $scope.ts = CRM.ts('mailingteams');
        $scope.$watch('crmMailingRecipients', function(newValue){
          $scope.myOptions = newValue;
        });
      }
    };
  });

 /* angular.module('mailingteams').decorator(
    'crmMailingRecipients',
    $delegate => {
      console.log($delegate);
    }
  )*/
})(angular, CRM.$, CRM._);
