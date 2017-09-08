(function(angular, $, _) {
  angular.module('mailingteams').directive('crmMailingRecipients', function() {

    /**
     * Utility function wrapping the jQuery transport to add our marker in.
     */
    function select2_transport_wrap(select_2) {
      let oTransport = select_2.opts.ajax.transport;

      select_2.opts.ajax.transport = function(params) {
        // Just add an extra parameter.  We'll pick this up in the API Wrapper.
        // See class CRM_MailingTeam_APIWrapper.
        params.data.params.forMailing = true;
        return oTransport(params);
      }
    }

    return {
      // Add an additional link function to the crmMailingRecipients directive
      // that invoikes our wrapper code.
      link: function(scope, element, attrs, ngModel) {
        if(select_2 = element.data('select2')) {
          // If the select2 has already been initialized, go ahead and wrap it now.
          select2_transport_wrap(select_2);
        } else {
          // Otherwise proxy the data function
          let oData = CRM.$.fn.data;

          CRM.$.fn.data = function () {
            if(this[0] == element[0] && arguments.length > 1) {
              select_2 = arguments[1];
              select2_transport_wrap(arguments[1]);

              // Once we've wrapped, we no longer need the proxy, so remove it.
              CRM.$.fn.data = oData;
            }
            return oData.apply(this, arguments);
          }
        }
      }
    };
  });
})(angular, CRM.$, CRM._);
