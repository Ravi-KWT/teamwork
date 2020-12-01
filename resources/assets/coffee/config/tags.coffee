angular.module 'mis'

  .config ($interpolateProvider)->
    $interpolateProvider.startSymbol '{%'
    $interpolateProvider.endSymbol '%}'