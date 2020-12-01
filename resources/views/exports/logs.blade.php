<table>
    <thead>
    <tr>
        <th><b>Date</b></th>
        <th><b>LoggedBy</b></th>
        <th><b>Project Name</b></th>
        <th><b>Company</b></th>
        <th><b>Log Description</b></th>
        <th><b>Tasklist</b></th>
        <th><b>Task Name</b></th>
        <th><b>Start Time</b></th>
        <th><b>End Time</b></th>
        <th><b>Billable</b></th>
        <th><b>Hours</b></th>
        <th><b>Minutes</b></th>
        <th><b>Decimal Hours</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach($logs as $log)
        <tr>
            <td>{{ $log[0] }}</td>
            <td>{{ $log[1] }}</td>
            <td>{{ $log[2] }}</td>
            <td>{{ $log[3] }}</td>
            <td>{{ $log[4] }}</td>
            <td>{{ $log[5] }}</td>
            <td>{{ $log[6] }}</td>
            <td>{{ $log[7] }}</td>
            <td>{{ $log[8] }}</td>
            <td>{{ $log[9] }}</td>
            <td>{{ $log[10] }}</td>
            <td>{{ $log[11] }}</td>
            <td>{{ $log[12] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>