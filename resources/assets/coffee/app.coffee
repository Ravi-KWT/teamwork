angular.module 'mis', [
		'angularjs-dropdown-multiselect','googlechart','ngResource','angularUtils.directives.dirPagination', 'ui.bootstrap','cgPrompt','720kb.datepicker', 'angular.filter','angularjs-dropdown-multiselect','cgNotify','datatables','datatables.bootstrap','datatables.buttons','datatables.colvis','datatables.columnfilter','rzModule','ng'
	]

	.filter 'timeAgo', [
		'$interval'
		($interval) ->
		# trigger digest every 60 seconds
			fromNowFilter = (time) ->
				moment(time).fromNow()
			$interval (->
			), 60000
			fromNowFilter.$stateful = true
			fromNowFilter
	]

	.filter 'stringToTimestamp', ->
		(input) ->
			return moment(input).format("ddd,hA")

	.filter 'split', ->
		(input, splitChar, splitIndex) ->
		# do some bounds checking here to ensure it has that index
		input.split(splitChar)[splitIndex]
	.filter 'setDecimal', ($filter) ->
		(input, places) ->
			if isNaN(input)
				return input
			# If we want 1 decimal place, we want to mult/div by 10
			# If we want 2 decimal places, we want to mult/div by 100, etc
			# So use the following to create that factor
			factor = '1' + Array(+(places > 0 and places + 1)).join('0')
			Math.round(input * factor) / factor
	.filter 'groupBy', ->
		results = {}
		(data, key) ->
			if !(data and key)
				return
			result = undefined
			if !@$id
				result = {}
			else
				scopeId = @$id
			if !results[scopeId]
				results[scopeId] = {}
				@$on '$destroy', ->
				delete results[scopeId]
				return
			result = results[scopeId]
			for groupKey of result
				result[groupKey].splice 0, result[groupKey].length
				i = 0
			while i < data.length
				if !result[data[i][key]]
					result[data[i][key]] = []
				result[data[i][key]].push data[i]
				i++
			keys = Object.keys(result)
			k = 0
			while k < keys.length
				if result[keys[k]].length == 0
					delete result[keys[k]]
				k++
			result
	.filter 'strLimit', [
		'$filter'
		($filter) ->
			(input, limit) ->
				if !input
					return
				if input.length <= limit
					return input
				$filter('limitTo')(input, limit) + '...'
	]
	.filter 'filterBy', ->
		(array, query) ->
			parts = query and query.trim().split(/\s+/)
			keys = Object.keys(array[0])
			if !parts or !parts.length
				return array
			array.filter (obj) ->
				parts.every (part) ->
					keys.some (key) ->
						String(obj[key]).toLowerCase().indexOf(part.toLowerCase()) > -1

	.filter 'capitalize', ->
		(input) ->
			if ! !input then input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() else ''
	.filter 'taskViewDateFormat', ($filter) ->
	  	(text) ->
	    	tempdate = new Date(text.replace(/-/g, '/'))
	    	$filter('date') tempdate, 'dd-MM-yyyy'

	.filter 'taskViewYearMonthDayFormat', ($filter) ->
	  	(text) ->
	    	tempdate = new Date(text.replace(/-/g, '/'))
	    	$filter('date') tempdate, 'yyyyMMdd'
	.filter 'parseDate', ->
		(input) ->
			new Date(input)
	.filter 'tel', ->
		tel


	.config (paginationTemplateProvider)->
		paginationTemplateProvider.setPath('/html/dirPagination.tpl.html')	