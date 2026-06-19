<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>API UI — View Only</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .db-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-family: system-ui, sans-serif;
            font-size: 0.95rem;
        }

        .db-status.connected {
            background: #e6f4ea;
            color: #1e7e34;
            border: 1px solid #b6e2c1;
        }

        .db-status.disconnected {
            background: #fdecea;
            color: #b02a37;
            border: 1px solid #f1b0b7;
        }

        .db-status .dot {
            width: 0.6rem;
            height: 0.6rem;
            border-radius: 50%;
            background: currentColor;
        }
    </style>
</head>

<body>

    <header>
        <h1>API Request — View only UI</h1>

        @if ($database['connected'])
            <div class="db-status connected">
                <span class="dot"></span>
                Database connected ({{ $database['driver'] }} / {{ $database['database'] }} @ {{ $database['host'] }})
            </div>
        @else
            <div class="db-status disconnected">
                <span class="dot"></span>
                Database not connected: {{ $database['error'] }}
            </div>
        @endif
    </header>
</body>

</html>
