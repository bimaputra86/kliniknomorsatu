<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rancangan Sistem') - Skripsi BAB IV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
            color: #000;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
            background: #fff;
            border: 2px solid #000;
            padding: 20px 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #444;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        .form-label {
            width: 170px;
            font-size: 13px;
            font-weight: bold;
        }
        .form-separator {
            width: 15px;
            font-weight: bold;
        }
        .form-input {
            flex: 1;
            padding: 8px 10px;
            border: 1.5 solid #333;
            border-radius: 4px;
            font-size: 13px;
            background-color: #fff;
            box-sizing: border-box;
        }
        textarea.form-input {
            height: 60px;
            resize: none;
        }
        .button-group {
            border-top: 2px solid #000;
            padding-top: 15px;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        .btn {
            padding: 8px 20px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            border: 1px solid #000;
        }
        .btn-primary {
            background-color: #2563eb;
            color: #fff;
            border-color: #1d4ed8;
        }
        .btn-secondary {
            background-color: #e5e7eb;
            color: #000;
        }
        /* Style untuk Output / Tabel */
        table.table-output {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }
        table.table-output th, table.table-output td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        table.table-output th {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
    </style>
</head>
<body>
    <div class="container">
        @yield('wireframe_content')
    </div>
</body>
</html>
