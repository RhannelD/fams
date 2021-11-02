<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ScholarList-{{ $scholarship->scholarship }}-{{ \Carbon\Carbon::now()->format("M d, Y") }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="m-2">
        <table class="table table-sm border-top-0 border-left-0 border-right-0">
            <thead class="border-0">
                <tr class="border-0">
                    <th colspan="5" class="border-0">
                        <h4 class="text-center">
                            {{ $scholarship->scholarship }}
                        </h4>
                        <h6 class="text-center mb-3">
                            Scholar List of {{ \Carbon\Carbon::now()->format("M d, Y") }}
                        </h6>
                    </th>
                </tr>
                <tr class="table-bordered">
                    <th class="text-center">
                        #
                    </th>
                    <th>
                        Lastname
                    </th>
                    <th>
                        Firstname
                    </th>
                    <th>
                        Middlename
                    </th>
                    <th>
                        Email
                    </th>
                </tr>
            </thead>
            <tbody class=" table-bordered">
                @foreach ($scholars as $scholar)
                    <tr>
                        <th class="text-right">
                            {{ $loop->index+1 }}
                        </th>
                        <td>
                            {{ $scholar->lastname }}
                        </td>
                        <td>
                            {{ $scholar->firstname }}
                        </td>
                        <td>
                            {{ $scholar->middlename }}
                        </td>
                        <td>
                            {{ $scholar->email }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
