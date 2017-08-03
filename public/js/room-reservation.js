var resApp;
resApp = angular.module('roomReservationApp', ['ngRoute', 'jkuri.datepicker', 'ngAnimate', 'ngSanitize', 'angularSpinner', 'ui.bootstrap', 'ngMap', 'checklist-model'] )
    .config(function ($routeProvider, $locationProvider) {

        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });

        $routeProvider
            .when('/' + objectl10n.searchAndReserveSlug, {
                templateUrl: objectl10n.partialsURL + 'room-search.html',
                controller: 'roomReservationController',
                controllerAs: 'roomReservation'
            })
            .when('/' + objectl10n.browseAndReserveSlug, {
                templateUrl: objectl10n.partialsURL + 'room-browse.html',
                controller: 'roomReservationController',
                controllerAs: 'roomReservation'
            })

        ;
    })
    .controller('roomReservationController',
                ['$scope', '$route', '$routeParams', '$location', '$anchorScroll', 'searchService', 'utilityService', 'userRegistrationService', 'usSpinnerService', '$uibModal','NgMap',
                function ($scope, $route, $routeParams, $location, $anchorScroll, searchService, utilityService, userRegistrationService, usSpinnerService, $uibModal, NgMap) {

        $scope.roomReservation = this;
        $scope.roomReservation.request = {};


        $scope.roomReservation.userRegistrationRequest = {};
        $scope.roomReservation.loginRequest = {};
        $scope.roomReservation.loginRequest.nonce = objectl10n.loginFormNonce ;


        $scope.roomReservation.isLoggedIn = false ;
        $scope.roomReservation.isRegistered = false ;
        $scope.roomReservation.userRegistrationl10n = objectl10n.userRegistrationl10n;
        $scope.roomReservation.loginError = "";



        $scope.roomReservation.displayRegistration = false;
        $scope.roomReservation.displayLogin = false;
        $scope.roomReservation.displayRoomInfo = false;


        $scope.roomReservation.loginControlClass = "displayed-section";
        $scope.roomReservation.registrationControlClass = "displayed-section";

        // TODO: add configuration for login/logout controls
        $scope.roomReservation.loginControl = decodeURI( $scope.roomReservation.userRegistrationl10n.loginLinkContent );

        $scope.roomReservation.loginControlTitle = $scope.roomReservation.userRegistrationl10n.loginLinkTitle;

        $scope.roomReservation.showUserRegistrationForm = function () {
            $scope.registrationModal =  modalInstance = $uibModal.open({
                templateUrl: objectl10n.partialsURL +  'user-registration.html',
                scope: $scope,
                windowTopClass: 'registration-modal',
                size: 'lg'

            });

        };

        $scope.roomReservation.hideUserRegistrationForm = function () {
            $scope.registrationModal.close();

        };

        $scope.roomReservation.showLoginForm = function () {
            $scope.loginModal =  modalInstance = $uibModal.open({
                templateUrl: objectl10n.partialsURL +  'user-login.html',
                scope: $scope,
                windowTopClass: 'login-modal'

            });
        };

        $scope.roomReservation.hideLoginForm = function () {
            $scope.loginModal.close();
        };

        $scope.roomReservation.toggleLogin = function() {
            if ( $scope.roomReservation.isLoggedIn ) {
                $scope.roomReservation.logout();
            }
            else {
                $scope.roomReservation.showLoginForm();
            }
        }




        var now = new Date();
        $scope.roomReservation.today = now;

        // Set default date of arrival
        $scope.roomReservation.request.dateOfArrival = new Date( now.getFullYear(), now.getMonth(), now.getDate() );
        // Set timestamp to today and adjust to check in time.
        utilityService.setCheckinTimestamp( $scope )



        $scope.roomReservation.searchableAmenities = objectl10n.searchableAmenities.split(",");

        $scope.roomReservation.request.lengthOfStay = "";

        $scope.roomReservation.amenitiesTitle = objectl10n.amenitiesTitle;

        $scope.roomReservation.request.numberOfOccupants = "";
        $scope.roomReservation.request.adults = 1;
        $scope.roomReservation.request.children = 0;


        // Init open/closes state of elements.
        $scope.roomReservation.roomListClass = "hidden-section";
        $scope.roomReservation.listOpenButtonClass = "hidden-section";
        $scope.roomReservation.reservationFormClass = "hidden-section";
        $scope.roomReservation.confirmationClass = "hidden-section";
        $scope.roomReservation.offlineInstructionsClass = "hidden-section";
        $scope.roomReservation.roomDetailClass = "hidden-section";

        if ( objectl10n.enableUserControls == true ) {
            $scope.roomReservation.userControlsClass = "displayed-section";
        }
        else {
            $scope.roomReservation.userControlsClass = "hidden-section";
        }


        // This will be changed conditionally based on payment gateway.
        $scope.roomReservation.paymentInfoClass = "hidden-section";
        $scope.roomReservation.cardInfoRequired = "";



        // labels
        $scope.roomReservation.dateOfArrivalLabel = objectl10n.dateOfArrivalLabel;
        $scope.roomReservation.lengthOfStayLabel = objectl10n.lengthOfStayLabel;
        $scope.roomReservation.numberOfOccupantsLabel = objectl10n.numberOfOccupantsLabel;
        $scope.roomReservation.maximumPriceLabel = objectl10n.maximumPriceLabel;
        $scope.roomReservation.selectedAmenitiesLabel = objectl10n.selectedAmenitiesLabel;
        $scope.roomReservation.firstNameLabel = objectl10n.firstNameLabel;
        $scope.roomReservation.lastNameLabel = objectl10n.lastNameLabel;
        $scope.roomReservation.companyNameLabel = objectl10n.companyNameLabel;
        $scope.roomReservation.address1Label = objectl10n.address1Label;
        $scope.roomReservation.address2Label = objectl10n.address2Label;
        $scope.roomReservation.cityLabel = objectl10n.cityLabel;
        $scope.roomReservation.stateLabel = objectl10n.stateLabel;
        $scope.roomReservation.zipLabel = objectl10n.zipLabel;
        $scope.roomReservation.countryLabel = objectl10n.countryLabel;
        $scope.roomReservation.phoneLabel = objectl10n.phoneLabel;
        $scope.roomReservation.mobileLabel = objectl10n.mobileLabel;
        $scope.roomReservation.adultsLabel = objectl10n.adultsLabel;
        $scope.roomReservation.childrenLabel = objectl10n.childrenLabel;
        $scope.roomReservation.emailLabel = objectl10n.emailLabel;
        $scope.roomReservation.amountChargedLabel = objectl10n.amountChargedLabel;
        $scope.roomReservation.roomSearchOpenLabel = objectl10n.roomSearchOpenLabel;
        $scope.roomReservation.roomListOpenLabel = objectl10n.roomListOpenLabel;
        $scope.roomReservation.roomReservationOpenLabel = objectl10n.roomReservationOpenLabel;
        $scope.roomReservation.makeAnotherReservationLabel = objectl10n.makeAnotherReservationLabel;
        $scope.roomReservation.pricePerDayLabel = objectl10n.pricePerDayLabel;
        $scope.roomReservation.roomAmountLabel = objectl10n.roomAmountLabel;
        $scope.roomReservation.feeAmountLabel = objectl10n.feeAmountLabel;
        $scope.roomReservation.taxAmountLabel = objectl10n.taxAmountLabel;
        $scope.roomReservation.totalLabel = objectl10n.totalLabel;
        $scope.roomReservation.cardNumberLabel = objectl10n.cardNumberLabel;
        $scope.roomReservation.cardExpirationLabel = objectl10n.cardExpirationLabel;
        $scope.roomReservation.cardExpirationMonthLabel = objectl10n.cardExpirationMonthLabel;
        $scope.roomReservation.cardExpirationYearLabel = objectl10n.cardExpirationYearLabel;
        $scope.roomReservation.cardCVCLabel = objectl10n.cardCVCLabel;
        $scope.roomReservation.nameOnCardLabel = objectl10n.nameOnCardLabel;
        $scope.roomReservation.payPalLabel = objectl10n.payPalLabel;
        $scope.roomReservation.paymentGatewayPayPalExpress = objectl10n.paymentGatewayPayPalExpress;
        $scope.roomReservation.offlineLabel = objectl10n.offlineLabel;
        $scope.roomReservation.currentPriceLabel = objectl10n.currentPriceLabel;
        $scope.roomReservation.paymentGatewayOffline = objectl10n.paymentGatewayOffline;
        $scope.roomReservation.paymentGatewayOfflineInstructions = objectl10n.paymentGatewayOfflineInstructions;
        $scope.roomReservation.noRoomsFoundMessage = objectl10n.noRoomsFoundMessage;
        $scope.roomReservation.dateOfArrivalErrorMessage = objectl10n.dateOfArrivalErrorMessage;
        $scope.roomReservation.seeMoreInfoLabel = objectl10n.seeMoreInfoLabel;
        $scope.roomReservation.detailsLabel = objectl10n.detailsLabel;
        $scope.roomReservation.reserveLabel = objectl10n.reserveLabel;
        $scope.roomReservation.detailsTitle = objectl10n.detailsTitle;
        $scope.roomReservation.lengthOfStayLabel = objectl10n.lengthOfStayLabel;
        $scope.roomReservation.numberOfGuestsLabel = objectl10n.numberOfGuestsLabel;
        $scope.roomReservation.maxPriceLabel = objectl10n.maxPriceLabel;
        $scope.roomReservation.locationsLabel = objectl10n.locationsLabel;
        $scope.roomReservation.dailyPriceLabel = objectl10n.dailyPriceLabel;
        $scope.roomReservation.roomDescriptionLabel = objectl10n.roomDescriptionLabel;
        $scope.roomReservation.filterLabel = objectl10n.filterLabel ;
        $scope.roomReservation.confirmationHeading = objectl10n.confirmationHeading ;
        $scope.roomReservation.checkInTimeLabel = objectl10n.checkInTimeLabel ;
        $scope.roomReservation.checkOutTimeLabel = objectl10n.checkOutTimeLabel ;
        $scope.roomReservation.checkInTime = objectl10n.checkInTime ;
        $scope.roomReservation.checkOutTime = objectl10n.checkOutTime ;






                    // shared content areas
        $scope.roomReservation.topSharedContentArea = decodeURI( objectl10n.topSharedContentArea );
        $scope.roomReservation.middleSharedContentArea = decodeURI( objectl10n.middleSharedContentArea );
        $scope.roomReservation.bottomSharedContentArea = decodeURI( objectl10n.bottomSharedContentArea );





        // messages
        $scope.roomReservation.reservationInstructions = objectl10n.reservationInstructions;
        $scope.roomReservation.confirmationMessage = objectl10n.confirmationMessage;
        $scope.roomReservation.reservationTimeUnit = objectl10n.reservationTimeUnit;


        // titles
        $scope.roomReservation.roomListTitle = objectl10n.roomListTitle;

        //misc
        $scope.roomReservation.currencySymbol = objectl10n.currencySymbol;
        $scope.roomReservation.paymentInfoHeading = objectl10n.paymentInfoHeading;
        $scope.roomReservation.reserveButtonIsDisabled = false;

        $scope.roomReservation.countries = objectl10n.countries;
        $scope.roomReservation.statesTable = objectl10n.statesTable;
        $scope.roomReservation.states = [];






        $scope.roomReservation.toggleSearchForm = function() {
            if ( $scope.roomReservation.searchFormClass == "displayed-section" ) {
                $scope.roomReservation.searchFormClass = "hidden-section"
            }
            else if  ( $scope.roomReservation.searchFormClass == "hidden-section" ) {
                $scope.roomReservation.searchFormClass = "displayed-section"
            }
        }

        $scope.roomReservation.toggleRoomList = function() {
            if ( $scope.roomReservation.roomListClass == "displayed-section" ) {
                $scope.roomReservation.roomListClass = "hidden-section"
            }
            else if  ( $scope.roomReservation.roomListClass == "hidden-section" ) {
                $scope.roomReservation.roomListClass = "displayed-section"
            }
        }

        $scope.roomReservation.closeReservationForm = function() {
            $scope.roomReservation.reservationFormClass = "hidden-section";
        }

        $scope.roomReservation.countrySelected = function() {
            $scope.roomReservation.states = $scope.roomReservation.statesTable[ $scope.roomReservation.request.country ];
        }

        $scope.roomReservation.regCountrySelected = function() {
            $scope.roomReservation.states = $scope.roomReservation.statesTable[ $scope.roomReservation.userRegistrationRequest.country ];
        }


        $scope.roomReservation.openReservationForm = function() {

            if ( $scope.roomReservation.isLoggedIn || $scope.roomReservation.isRegistered ) {
                // Copy user infomation to form.
                $scope.roomReservation.request.firstName = $scope.roomReservation.user.firstName;
                $scope.roomReservation.request.lastName = $scope.roomReservation.user.lastName;
                $scope.roomReservation.request.address1 = $scope.roomReservation.user.address1;
                $scope.roomReservation.request.address2 = $scope.roomReservation.user.address2;
                $scope.roomReservation.request.city = $scope.roomReservation.user.city;
                $scope.roomReservation.request.state = $scope.roomReservation.user.state;
                // TODO standardize zip to postal_code
                $scope.roomReservation.request.zip = $scope.roomReservation.user.postalCode;
                $scope.roomReservation.request.country = $scope.roomReservation.user.country;
                $scope.roomReservation.request.email = $scope.roomReservation.user.email;
                $scope.roomReservation.request.phone = $scope.roomReservation.user.phone;
                $scope.roomReservation.request.mobile = $scope.roomReservation.user.mobile;
                $scope.roomReservation.request.country = $scope.roomReservation.user.country;

            }
            $scope.roomReservation.reservationFormClass = "displayed-section";
        }

        $scope.roomReservation.reloadSearchPage = function() {
            location.reload();
        }

        $scope.roomReservation.toggleOfflinePaymentInstructions = function() {
            if ( $scope.roomReservation.request.paymentMethod == $scope.roomReservation.paymentGatewayOffline ) {
                $scope.roomReservation.offlineInstructionsClass = "displayed-section"
            }
            else {
                $scope.roomReservation.offlineInstructionsClass = "hidden-section"
            }
        }

        $scope.roomReservation.showRoomInfo = function ( room ) {

            $scope.roomInfoModal = $uibModal.open({
                templateUrl: objectl10n.partialsURL +  'room-info.html',
                scope: $scope,
                windowTopClass: 'registration-modal',
                size: 'lg'

            });

            NgMap.getMap().then( function(map) {
                var mapCenter = map.getCenter();
                google.maps.event.trigger(map, 'resize');
                map.setCenter( mapCenter );
            });


        }

        $scope.roomReservation.hideRoomInfo = function (  ) {
            $scope.roomInfoModal.close();
        }

        $scope.roomReservation.showRoomDetail = function ( room ) {
            $scope.roomReservation.selectedRoom = room ;
            $scope.roomReservation.roomDetailClass = "displayed-section";
            $scope.$broadcast('initSlider',{});
            $location.hash('gst-scroll-to-detail');
            $anchorScroll();
            $location.hash('');

            // Before opening reservation form, If user has not specified number of night,
            // default to 1 here so tha length of stay is set to at least one day.
            if ($scope.roomReservation.request.lengthOfStay == "" ) {
                $scope.roomReservation.request.lengthOfStay = 1;
            }
            $scope.roomReservation.openReservationForm();

        }



        $scope.roomReservation.searchRooms = function(){

            usSpinnerService.spin('load-spinner');

            searchService.searchRooms( $scope )
                .then( function( data ){

                    usSpinnerService.stop('load-spinner');

                    /*
                    * Close detail and reservation form sections since the data on which
                    * they were opened might no longer be valid.
                    */
                    $scope.roomReservation.reservationFormClass = "hidden-section";
                    $scope.roomReservation.roomDetailClass = "hidden-section";


                    if ( data.errorData != null && data.errorData == "true" ) {
                        $scope.roomReservation.errorMessage = data.errorMessage ;
                        $scope.roomReservation.errorContainerClass = "displayed-section";
                    }
                    else {
                        $scope.roomReservation.rooms = data;

                        // check to see if number of rooms returned is greater than 0.
                        if ( $scope.roomReservation.rooms.length > 0 ) {
                            $scope.roomReservation.listOpenButtonClass = "displayed-section";
                            $scope.roomReservation.roomListClass = "displayed-section";
                            $scope.roomReservation.errorContainerClass = "hidden-section";

                        }
                        else {
                            $scope.roomReservation.errorMessage = $scope.roomReservation.noRoomsFoundMessage ;
                            $scope.roomReservation.errorContainerClass = "displayed-section";
                        }

                    }

                }, function( data ) {
                    usSpinnerService.stop('load-spinner');
                    console.log('Error on room search');
                });
        }

        $scope.roomReservation.browseRooms = function(){

            usSpinnerService.spin('load-spinner');

            searchService.browseRooms( $scope )
                .then( function( data ){

                    usSpinnerService.stop('load-spinner');

                    if ( data.errorData != null && data.errorData == "true" ) {
                        $scope.roomReservation.errorMessage = data.errorMessage ;
                        $scope.roomReservation.errorContainerClass = "displayed-section";
                    }
                    else {
                        $scope.roomReservation.rooms = data;
                        // check to see if number of rooms returned is greater than 0.
                        if ( $scope.roomReservation.rooms.length > 0 ) {
                            $scope.roomReservation.roomListClass = "displayed-section";
                            $scope.roomReservation.errorContainerClass = "hidden-section";

                        }
                        else {
                            $scope.roomReservation.errorMessage = $scope.roomReservation.noRoomsFoundMessage ;
                            $scope.roomReservation.errorContainerClass = "displayed-section";
                        }

                    }

                }, function( data ) {

                    usSpinnerService.stop('load-spinner');
                    console.log('Error on retrieving all room for browsing.');
                });
        }


        $scope.roomReservation.reserveRoom = function(){

            usSpinnerService.spin('reserve-spinner');

            $scope.roomReservation.reserveButtonIsDisabled = true;


            searchService.reserveRoom( $scope )
                .then( function( data ){

                    usSpinnerService.stop('reserve-spinner');

                    if ( data.errorData != null && data.errorData == "true" ) {
                        $scope.roomReservation.errorMessage = data.errorMessage ;
                        $scope.roomReservation.errorContainerClass = "displayed-section";
                    }
                    else {

                        if (data.status == 'redirect') {
                            window.location = data.url;
                            $scope.roomReservation.confirmation = data.pending_reservation;
                        }
                        else {
                            $scope.roomReservation.confirmation = data;
                        }
                        $scope.roomReservation.reservationFormClass = "hidden-section";
                        $scope.roomReservation.searchOpenButtonClass = "hidden-section";
                        $scope.roomReservation.listOpenButtonClass = "hidden-section";
                        $scope.roomReservation.confirmationClass = "displayed-section";
                        window.scrollTo(0,document.body.scrollHeight);

                    }
                }, function( data ) {
                    usSpinnerService.stop('reserve-spinner');
                    console.log('Error reserving room.');
                });
        }

        $scope.roomReservation.getGeoLocation = function(  $scope ) {

            searchService.getGeoLocation( $scope )
                .then( function( data ){
                    $scope.roomReservation.geoLocation = data;
                    $scope.roomReservation.request.country = $scope.roomReservation.geoLocation.country_name;
                    $scope.roomReservation.request.state = $scope.roomReservation.geoLocation.region_name;
                    $scope.roomReservation.states = $scope.roomReservation.statesTable[ $scope.roomReservation.request.country ];

                }, function( data ) {
                    console.log('Error getting geolocation');
                });
        }

        $scope.roomReservation.getUser = function() {


            userRegistrationService.getUser( $scope )
                .then( function( data ){

                    if ( data.errorData != null && data.errorData == "true" ) {
                        $scope.roomReservation.errorMessage = data.errorMessage ;
                    }
                    else {
                        $scope.roomReservation.user = data;
                        // check to see if number of rooms returned is greater than 0.
                        if ( $scope.roomReservation.user.id != 0 ) {
                            $scope.roomReservation.isLoggedIn = true ;
                            $scope.roomReservation.isRegistered = true ;
                            $scope.roomReservation.loginControl = $scope.roomReservation.userRegistrationl10n.logoutLinkContent ;
                            $scope.roomReservation.loginControlTitle = $scope.roomReservation.userRegistrationl10n.logoutLinkTitle;
                            $scope.roomReservation.registrationControlClass = "hidden-section";


                        }
                        else {
                            $scope.roomReservation.isLoggedIn = false ;
                            $scope.roomReservation.isRegistered = false ;
                            $scope.roomReservation.loginControl = decodeURI( $scope.roomReservation.userRegistrationl10n.loginLinkContent ) ;
                            $scope.roomReservation.loginControlTitle = $scope.roomReservation.userRegistrationl10n.loginLinkTitle;
                            $scope.roomReservation.registrationControlClass = "displayed-section";

                            $scope.roomReservation.errorMessage = $scope.roomReservation.userRegistrationl10n.registrationFailedMessage ;

                        }

                    }

                }, function( data ) {
                    console.log('Error retrieving user data.');
                });
        }

        $scope.roomReservation.registerUser = function() {

            usSpinnerService.spin('registration-spinner');

            userRegistrationService.registerUser( $scope )
                .then( function( data ){

                    usSpinnerService.stop('registration-spinner');


                    if ( data.errorData != null && data.errorData == "true" ) {
                        $scope.roomReservation.errorMessage = data.errorMessage ;
                        $scope.roomReservation.hideUserRegistrationForm();

                    }
                    else {
                        $scope.roomReservation.user = data;
                        // check to see if number of rooms returned is greater than 0.
                        if ( $scope.roomReservation.user.id != 0 ) {
                            $scope.roomReservation.isRegistered = true ;
                            $scope.roomReservation.registrationControlClass = "hidden-section";
                            $scope.roomReservation.hideUserRegistrationForm();
                            $scope.roomReservation.errorMessage =  $scope.roomReservation.userRegistrationl10n.postRegistrationMessage;
                            $scope.roomReservation.errorContainerClass = "displayed-section";

                        }
                        else {
                            $scope.roomReservation.isRegistered = false ;
                        }

                    }

                }, function( data ) {
                    usSpinnerService.stop('registration-spinner');
                    console.log('Error retrieving user data.');
                });
        }

        $scope.roomReservation.login = function() {

            usSpinnerService.spin('login-spinner');

            userRegistrationService.login( $scope )
                .then( function( data ){

                    usSpinnerService.stop('login-spinner');


                    if ( data.errorData != null && data.errorData == "true" ) {
                        $scope.roomReservation.errorMessage = data.errorMessage ;
                    }
                    else {
                       if ( data.loggedIn) {
                           $scope.roomReservation.loginError = "";
                           $scope.roomReservation.getUser();
                           $scope.roomReservation.hideLoginForm();
                           location.reload();


                       }
                        else {
                           $scope.roomReservation.loginError = $scope.roomReservation.userRegistrationl10n.loginFailedMessage ;
                       }

                    }

                }, function( data ) {
                    usSpinnerService.stop('login-spinner');
                    console.log('Error logging in.');
                });
        }

        $scope.roomReservation.logout = function() {

            usSpinnerService.spin('logout-spinner');

            userRegistrationService.logout( $scope )
                .then( function( data ){

                    usSpinnerService.stop('logout-spinner');

                    if ( data.errorData != null && data.errorData == "true" ) {
                        $scope.roomReservation.errorMessage = data.errorMessage ;
                    }
                    else {
                        if ( data.loggedIn == 'false') {
                            $scope.roomReservation.loginError = "";
                            $scope.roomReservation.getUser();
                            location.reload();
                        }
                        else {
                            $scope.roomReservation.loginError = $scope.roomReservation.userRegistrationl10n.logoutFailedMessage ;
                        }

                    }

                }, function( data ) {
                    usSpinnerService.stop('logout-spinner');
                    console.log('Error logging out.');
                });
        }

        // Get geo location
        $scope.roomReservation.getGeoLocation( $scope );

        // Route specific initializations.
        if ( $route.current.originalPath == '/' + objectl10n.searchAndReserveSlug ) {
            $scope.roomReservation.getUser();
            $scope.roomReservation.searchRooms();
        }

        if ( $route.current.originalPath == '/' + objectl10n.browseAndReserveSlug ) {
            $scope.roomReservation.getUser();
            $scope.roomReservation.browseRooms();
        }




    }]);

resApp.controller('ModalCtrl', [ '$scope', '$uibModalInstance', function ( $scope, $uibModalInstance ) {
    var $ctrl = this;

    var roomRservation = $scope.roomReservation;

    $ctrl.close = function () {
        $uibModalInstance.close();
    };

}]);

resApp.factory('searchService', function ($q, $http, $location, utilityService ) {
    'use strict';
    var service = {};

    service.searchRooms = function ( $scope ) {

        var deferred = $q.defer();

        // set length of stay "long" to number of milliseconds
        $scope.roomReservation.request.lengthOfStayLong  = $scope.roomReservation.request.lengthOfStay * 86400000 ;

        // $scope.roomReservation.request.dateOfArrivalLong = $scope.roomReservation.request.dateOfArrival.getTime();
        utilityService.setCheckinTimestamp( $scope );



        $http({
                method: 'GET',
                url: '/wp-admin/admin-ajax.php',
                params: {
                    'action':'hospitality_ajax',
                    'fn':'get_available_rooms',
                    'searchCriteria' : $scope.roomReservation.request
                }

            })
            .success(function( data ){
                deferred.resolve( data );
            })
            .error(function( data ) {
                deferred.reject('There was an error!');
            });
            return deferred.promise;
    };

    service.browseRooms = function ( $scope ) {

        var deferred = $q.defer();

        $http({
            method: 'GET',
            url: '/wp-admin/admin-ajax.php',
            params: {
                'action':'hospitality_ajax',
                'fn':'get_all_rooms'
            }

        })
            .success(function( data ){
                deferred.resolve( data );
            })
            .error(function( data ) {
                deferred.reject('There was an error!');
            });
        return deferred.promise;
    };


    service.reserveRoom = function ( $scope ) {

        var deferred = $q.defer();

        // set length of stay "long" to number of milliseconds
        $scope.roomReservation.request.lengthOfStayLong  = $scope.roomReservation.request.lengthOfStay * 86400000 ;

        $scope.roomReservation.request.selectedRoomID = $scope.roomReservation.selectedRoom.id;


        $http({
            method: 'GET',
            url: '/wp-admin/admin-ajax.php',
            params: {
                'action':'hospitality_ajax',
                'fn':'reserve_room',
                'reservationInfo' : $scope.roomReservation.request
            }

        })
            .success(function( data ){
                deferred.resolve( data );
            })
            .error(function( data ) {
                deferred.reject('There was an error!');
            });
        return deferred.promise;
    };

    service.getGeoLocation = function( $scope ) {
        var deferred = $q.defer();

        $http({
            method: 'GET',
            url: '//freegeoip.net/json/',
            dataType: 'jsonp'

        })
            .success(function( data ){
                deferred.resolve( data );
            })
            .error(function( data ) {
                deferred.reject('There was an error!');
            });
        return deferred.promise;
    };



    return service;
});

resApp.factory('utilityService', function () {
    'use strict';
    var service = {};

    service.setCheckinTimestamp = function( $scope ) {
        // Set to time of checkin.
        // Convert checkin time to decimal value.
        var hoursFromMidnight = 0;
        if ( objectl10n.checkInTime.endsWith('PM')) {
            hoursFromMidnight = 12;
        }

        var timeParts =  objectl10n.checkInTime.split(':');
        var hours = parseInt( timeParts[0] );
        var minuteParts = timeParts[1].split(' ');
        var minutes = parseInt( minuteParts[0] );

        hoursFromMidnight +=  hours;
        hoursFromMidnight += minutes / 60 ;

        if ( $scope.roomReservation.request.dateOfArrival instanceof Date ) {
            var dateOfArrival = $scope.roomReservation.request.dateOfArrival ;
        }
        else {
            var dateOfArrival = new Date( $scope.roomReservation.request.dateOfArrival );
        }

        $scope.roomReservation.request.dateOfArrivalLong = dateOfArrival.getTime();

        var todayAtMidnight = new Date( $scope.roomReservation.today.getFullYear(),
                                        $scope.roomReservation.today.getMonth(),
                                        $scope.roomReservation.today.getDate() ).getTime() ;

        if (  $scope.roomReservation.request.dateOfArrivalLong < todayAtMidnight ) {
            $scope.roomReservation.request.dateOfArrivalLong = todayAtMidnight;
        }

        // Add in milliseconds since midnight for checkin time to set start time of reservation.
        $scope.roomReservation.request.dateOfArrivalLong += hoursFromMidnight * 60 * 60 * 1000 ;
    }


    return service ;
});


resApp.directive( 'ignoreMouseWheel', function( $rootScope ) {
    return {
        restrict: 'A',
        link: function( scope, element, attrs ){
            element.bind('mousewheel', function ( event ) {
                element.blur();
            } );
        }
    }
} );

resApp.directive('validateDateOfArrival', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attr, roomReservation ) {
            function validateDateOfArrival( value ) {

                var dateOfArrival = new Date( value ).getTime();
                var now = new Date();
                var todayAtMidnight = new Date( now.getFullYear(),
                                                now.getMonth(),
                                                now.getDate() ).getTime() ;

                if ( dateOfArrival < todayAtMidnight ) {
                    var m = new moment();
                    value = m.format( objectl10n.dateFormat );
                    // roomReservation.$setViewValue( value );
                    roomReservation.$setValidity( 'arrivalDateError', false );
                }
                else {
                    roomReservation.$setValidity( 'arrivalDateError', true );
                }

                return value;
            }
            roomReservation.$parsers.push( validateDateOfArrival );
        }
    };
});

resApp.directive('ngSize', function() {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            if (!element.nodeName === 'SELECT') {
                return;
            }
            attrs.$observe('ngSize', function setSize(value) {
                attrs.$set('size', attrs.ngSize);
            });
        }
    };
});

resApp.directive('slider', function($timeout) {
    return {
        restrict: 'AE',
        replace: true,
        scope: {
            images: '=',
            sliderConfig: '='
        },
        link: function(scope, elem, attrs) {

            scope.currentIndex = 0; // Initially the index is at the first image

            scope.next = function() {
                scope.currentIndex < scope.images.length - 1 ? scope.currentIndex++ : scope.currentIndex = 0;
            };

            scope.prev = function() {
                scope.currentIndex > 0 ? scope.currentIndex-- : scope.currentIndex = scope.images.length - 1;
            };

            scope.first = function() {
                scope.currentIndex  = 0;
            };


            scope.displayCurrent = function () {
                // Ignore  at initialization.
                if ( scope.images == undefined )
                    return;

                scope.images.forEach(function(image) {
                    image.visible = false; // make every image invisible
                });

                scope.images[scope.currentIndex].visible = true; // make the current image visible

            }

            scope.$watch('images', function() {
                scope.displayCurrent();
                scope.sliderTimer();

            });


            scope.$watch('currentIndex', function() {
                scope.displayCurrent();
            });



            var timer;

            scope.sliderTimer = function() {

                // Ignore  at initialization.
                if ( scope.images == undefined )
                    return;

                $timeout.cancel(timer); // cancel previous timer

                timer = $timeout(function() {
                    if ( scope.images !== undefined ) {
                        scope.next();
                        timer = $timeout( scope.sliderTimer, scope.sliderConfig.slide_duration );
                    }

                }, scope.sliderConfig.slide_duration);
            };

            // sliderTimer();

            scope.$on('initSlider',function(event, data){
                scope.displayCurrent();
                scope.sliderTimer();
            });

            scope.$on('$destroy', function() {
                $timeout.cancel(timer); // when the scope is getting destroyed, cancel the timer
            });
        },
        templateUrl: objectl10n.partialsURL +  'slider.html'
    };
});

resApp.directive('reservationForm', function($timeout) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            roomReservation: '='
        },
        link: function(scope, elem, attrs) {

        },
        templateUrl: objectl10n.partialsURL +  'reservation-form.html'

    };
});

resApp.directive('roomBrowse', function($timeout) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            roomReservation: '='
        },
        link: function(scope, elem, attrs) {

        },
        templateUrl: objectl10n.partialsURL +  'room-browse.html'

    };
});

resApp.directive('roomInfo', function($timeout) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            roomReservation: '='
        },
        link: function(scope, elem, attrs) {

        },
        templateUrl: objectl10n.partialsURL +  'room-info.html'

    };
});

// User registration
resApp.factory('userRegistrationService', function ($q, $http, $location, utilityService ) {
    'use strict';
    var service = {};

    service.getUser = function ( $scope ) {

        var deferred = $q.defer();

        $http({
            method: 'GET',
            url: '/wp-admin/admin-ajax.php',
            params: {
                'action': 'hospitality_ajax',
                'fn': 'get_user',
                'userRegistrationRequest': $scope.roomReservation.userRegistrationRequest
            }

        })
            .success(function( data ){
                deferred.resolve( data );
            })
            .error(function( data ) {
                deferred.reject('There was an error sending get user request.');
            });
        return deferred.promise;
    };

    service.registerUser = function ( $scope ) {

        var deferred = $q.defer();

        $http({
            method: 'GET',
            url: '/wp-admin/admin-ajax.php',
            params: {
                'action':'hospitality_ajax',
                'fn':'register_user',
                'userRegistrationRequest' : $scope.roomReservation.userRegistrationRequest
            }

        })
            .success(function( data ){
                deferred.resolve( data );
            })
            .error(function( data ) {
                deferred.reject('There was an error while registering user!');
            });
        return deferred.promise;
    };


    service.login = function ( $scope ) {

        var deferred = $q.defer();

        $http({
            method: 'GET',
            url: '/wp-admin/admin-ajax.php',
            params: {
                'action':'ajaxlogin',
                'username' : $scope.roomReservation.loginRequest.userName,
                'password' : $scope.roomReservation.loginRequest.password,
                'security' : $scope.roomReservation.loginRequest.nonce
            }

        })
            .success(function( data ){
                deferred.resolve( data );
            })
            .error(function( data ) {
                deferred.reject('There was an error logging in!');
            });
        return deferred.promise;
    };

    service.logout = function ( $scope ) {

        var deferred = $q.defer();

        $http({
            method: 'GET',
            url: '/wp-admin/admin-ajax.php',
            params: {
                'action':'hospitality_ajax',
                'fn':'logout',
                'userRegistrationRequest' : $scope.roomReservation.userRegistrationRequest

            }

        })
            .success(function( data ){
                deferred.resolve( data );
            })
            .error(function( data ) {
                deferred.reject('There was an error while logging out.');
            });
        return deferred.promise;
    };


    return service;
});

resApp.directive('registerUser', function($timeout) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            roomReservation: '='
        },
        link: function(scope, elem, attrs) {

        },
        templateUrl: objectl10n.partialsURL +  'user-registration.html'

    };
});

resApp.directive('login', function($timeout) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            roomReservation: '='
        },
        link: function(scope, elem, attrs) {

        },
        templateUrl: objectl10n.partialsURL +  'user-login.html'

    };
});
