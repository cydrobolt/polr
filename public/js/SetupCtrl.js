polr.controller('SetupCtrl', function($scope) {
    $scope.settings = {
        restrictEmailDomain: false
    };

    $scope.init = function () {
        $('[data-toggle="popover"]').popover({
            trigger: "hover",
            placement: "right"
        });
    };

    $scope.init();
});
