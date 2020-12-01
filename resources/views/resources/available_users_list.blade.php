@if($users->count() > 0)
<table class="table table-striped">
	<thead>
		<th>Id</th>
		<th>Name</th>
		<th>Department</th>
	</thead>
	<tbody>
		@foreach($users as $user)
		<tr>
			<td>{!! $user->id !!}</td>
			<td>{!! $user->people->fname !!}</td>
			<td>{!! $user->people->department->name !!}</td>
		</tr>
		@endforeach
	</tbody>
</table>
	{!! $users->links() !!}
@else
	<div class="col-md-12">
		<div style="text-align:center;">
			<img src="{!! asset('img/noMilestone1.png') !!}"  height="100px" width="100px" />
			<h3 ng-cloak>No Record Found</h3>
		</div>
	</div>
@endif