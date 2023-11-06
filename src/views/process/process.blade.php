<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Table to Script</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        @if (count($tables) == 0)
        <h2>No hay tblas</h2>
        @else
            @foreach ($tables as $key => $table)
                <table class="table">
                    <thead>
                    <tr>
                        @foreach ($table['header'] as $item)
                        <th scope="col">{{$item}}</th>
                        @endforeach                
                    </tr>
                    </thead>
                    <tbody>              
                                    
                    </tbody>
                </table>  
                <br>  
                <br>
                <br>
                <br>
                <br>             
            @endforeach                       
        @endif   
    </div>
</body>
</html>