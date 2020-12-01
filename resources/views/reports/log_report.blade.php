<!DOCTYPE html>
<html>
<head>
    <title>Log Reports</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="{{ elixir('css/vendor.css') }}">
        <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    <style type="text/css">
        td, th{
           border: 1px solid #ccc;
        }
        th{
            text-align: center;
        }
        table{
            border-collapse: collapse;
        }
        hr{
            border: none;
            height: 1px;
            /* Set the hr color */
            color: #e0e0e0; /* old IE */
            background-color: #e0e0e0; /* Modern Browsers */
        }
        .totals_hours{
            font-size: 12px;
            margin-bottom: 5px;
        }
        .pdflogo{
            text-align: center;
            width: 100%;
            margin-bottom: 15px;
            display: block;
        }
        .pdflogo img{
            width: 200px;
        }
    </style>
</head>
<body style="font-family: sans-serif;font-size: 10px;">
    <script type="text/php">
        if (isset($pdf) ) { 
            $pdf->page_script('
                if ($PAGE_COUNT > 1) {
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $size = 8;
                    $pageText = "Page " . $PAGE_NUM . " of " . $PAGE_COUNT;
                    $y = 15;
                    $x = 730;
                    $pdf->text($x, $y, $pageText, $font, $size);
                } 
            ');
        }
    </script>
    @php
        $path = public_path('img/pdflogo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $pdflogo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    @endphp
    <div class="pdflogo">
        <img src="{{ $pdflogo }}" alt="logo">
    </div>
    <p><strong>@if($project_id)
        {{ \DB::table('projects')->where('id',$project_id)->first()->name}}
    @endif [ Start Date: {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }} End Date: {{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }} ]</strong></p>

<div class="totals_hours">
    @if(count($logs))
        Totals:
        Logged:<strong>                
            {!!floor($totalLoggedHours/60)." Hours ".($totalLoggedHours%60)." Minutes ( "
      . number_format($totalLoggedHours/60,2)!!} )</strong>

        Billable:
       <strong>{!!floor($totalBillableHours/60)." Hours ".($totalBillableHours%60)." Minutes ( "
      . number_format($totalBillableHours/60,2)!!} )</strong>

        Non-Billable:
        <strong>
          {!!floor($totalNonBillableHours/60)."  Hours ".($totalNonBillableHours%60)." Minutes ( "
      . number_format($totalNonBillableHours/60,2)!!} )</strong>
    @endif
</div>
    @php
        $path = public_path('img/billable.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $billable = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $path = public_path('img/non_billable.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $non_billable = 'data:image/' . $type . ';base64,' . base64_encode($data);
    @endphp
    <table  align="center" width="100%" cellpadding="2">
        <tr>
            <th>Date</th> 
            <th>Logged By</th> 
            @if(empty($project_id))
            <th>Project</th> 
            @endif
            <th>Description</th>
            <th>Task list</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Billable</th>
            <th>Time</th>
            <th>Hours</th>
        </tr>
        @foreach($logs as $log)
            <tr >
                
                <td align="center">{{ \Carbon\Carbon::parse($log->date)->format('d-m-Y') }}</td>
                <td>{{ $log->user->people->fname}}{{$log->user->people->lname?" ".$log->user->people->lname:''}}</td>
                @if(empty($project_id))
                <td>{{$log->task->project->name}}</td> 
                @endif
                <td>
                    Task: {{$log->task->name or '-'}}<hr>
                        {{$log->description or '-'}}
                </td>
                <td >{{ $log->task->category->name ? $log->task->category->name : '-'}}</td>
                <td  align="center">{!! $log->start_time ? $log->start_time : '-' !!}</td >
                <td  align="center">{!! $log->end_time ? $log->end_time : '-' !!}</td>
                <td align="center">
                    {{-- {{dd(public_path('img/billable.png'))}} --}}
                    @if($log->billable)
                        <img src="{{ $billable }}" alt="billable" width="20px" height="20px">
                    @else
                        <img src="{{ $non_billable }}" alt="non billable" width="20px" height="20px">
                    @endif
                </td>
                <td >
                    @php
                        $t1 = strtotime($log->start_time);
                        $t2 = strtotime($log->end_time);
                        $differenceInSeconds = $t2 - $t1;
                        $differenceInMinutes = $differenceInSeconds / 60;
                        $differenceInHours = $differenceInSeconds / 3600;
                    @endphp
                    {!! floor($differenceInHours)." hrs ". floor($differenceInMinutes%60)." mins " !!}
                </td>
                <td align="center">{!!$log->hour ? $log->hour : '-' !!}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>