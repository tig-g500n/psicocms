<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2a37;
            line-height: 1.6;
            margin: 0;
            padding: 40px 44px;
        }
        h2 { font-size: 18px; margin: 0 0 14px; }
        h3 { font-size: 14px; margin: 20px 0 8px; }
        p { margin: 0 0 10px; }
        ul { margin: 0 0 10px; padding-left: 18px; }
        li { margin-bottom: 4px; }
        hr { border: none; border-top: 1px solid #d7dde5; margin: 16px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding-top: 10px; }
        .pie {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #d7dde5;
            font-size: 10px;
            color: #6b7684;
            text-align: center;
        }
    </style>
</head>

<body>
    {!! $contenido !!}

    <div class="pie">Documento generado por {{ config('psicocms.nombre') }}</div>
</body>

</html>
