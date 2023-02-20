<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Test Task</title>

       
    </head>
    <body>
        <div>
            @if(isset($data))
            <table class="table table-striped">
                <thead>
                    <tr>
                    @foreach  ($data[0] as $key => $value)
                        <th scope="col">{{$value}}</th>     
                    @endforeach
                    </tr>
                </thead> 
                <tbody>
                    @foreach  ($data as $key)
                    <tr>              
                    @if ($loop->index !== 0 && !$loop->last)
                        @foreach ($key as $row )
                        <td scope="row">{{$row}}</td> 
                        @endforeach 
                    @endif
                    
                    </tr>
                    @if ($loop->last)
                        @foreach ($key as $row )
                        <th scope="row">{{$row}}</th> 
                        @endforeach 
                    @endif                           
                    @endforeach
                  </tbody> 
               </table> 
            @endif
        </div>
    </body>
</html>
