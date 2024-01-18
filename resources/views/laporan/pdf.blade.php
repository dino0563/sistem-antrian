<!DOCTYPE html>
<html>

<head>
    <title>Laporan Antrian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>{{$name }}</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Antrian</th>
                <th>Loket</th>
                <th>Waktu Pengambilan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['nomor_antrian'] }}</td>
                    <td>{{ $item['loket_nama'] }}</td>
                    <td>{{ $item['created_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
