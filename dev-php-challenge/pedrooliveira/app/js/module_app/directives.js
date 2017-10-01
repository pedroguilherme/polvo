/**
 * Created by PedroGuilherme on 06/09/2017.
 */
angular.module('app.directives', [])
    .directive('menu-polvo', function() {
        return {
            restrict: 'AE',
            template: 'templates/menu.html'
        };
    });