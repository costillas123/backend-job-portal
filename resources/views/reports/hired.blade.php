<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            position: relative;
            padding-top: 10px;
        }

        .header img.left-logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 100px;
        }

        .header img.right-logo {
            position: absolute;
            right: 0;
            top: 0;
            width: 100px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-top: 20px;
            font-size: 9px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            padding: 1px
        }

        .position-title {
            font-size: 10px;
            font-weight: bold;
            padding: 6px;
            background-color: #f0f0f0;
        }

        .footer-section {
            margin-top: 20px;
        }

        .text-center {
            text-align: center;
        }

        .signature-line {
            font-size: 12px;
            text-align: center;
            line-height: 1.3;
        }

        .privacy-notice {
            font-size: 7px;
            font-weight: bold;
            text-align: justify;
            width: 70%;
            border: 1px solid #000;
            padding: 5px;
            margin: 0;
        }

        .company-info table td {
            border: none !important;
            padding: 3px 0;
            font-size: 10px;
            vertical-align: top;
        }

        .contact-info {
            text-align: center;
            font-size: 9px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <img src="{{ public_path('images/logo2.png') }}" class="left-logo">
        <img src="{{ public_path('images/logo1.png') }}" class="right-logo">
        <p style="margin: 0; font-size: 12px; font-weight: bold;">CSR AND PLACEMENT DIVISION</p>
        <p style="margin: 0; font-size: 12px; font-weight: bold;">LIST OF HIRED APPLICANTS</p>
        @if(isset($startDate) && isset($endDate))
        <p style="margin: 5px 0 0 0; font-size: 10px;">
            Period: {{ \Carbon\Carbon::parse($startDate)->format('F d, Y') }} to
            {{ \Carbon\Carbon::parse($endDate)->format('F d, Y') }}
        </p>
        @endif
        <p style="margin: 0; font-size: 10px;">
            Generated: {{ $generated_at ?? now()->format('d F Y') }}
        </p>
    </div>

    <table>
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th style="width: 5%; padding: 8px; border: 1px solid #000; text-align: center;">NO.</th>
                <th style="width: 25%; padding: 8px; border: 1px solid #000; text-align: left;">POSITION</th>
                <th style="width: 30%; padding: 8px; border: 1px solid #000; text-align: left;">NAME</th>
                <th style="width: 20%; padding: 8px; border: 1px solid #000; text-align: left;">CONTACT NOS.<br>(Telephone/Mobile Phone)</th>
                <th style="width: 20%; padding: 8px; border: 1px solid #000; text-align: left;">EDUCATION / COURSE</th>
                <th style="width: 20%; padding: 8px; border: 1px solid #000; text-align: left;">Date Hired</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $app)
            <tr>
                <td style="text-align:center; padding: 6px; border: 1px solid #000;">{{ $index + 1 }}</td>
                <td style="padding: 6px; border: 1px solid #000;">{{ $app->jobVacancy->title ?? 'N/A' }}</td>
                <td style="padding: 6px; border: 1px solid #000;">{{ $app->jobSeeker->user->name ?? 'N/A' }}</td>
                <td style="padding: 6px; border: 1px solid #000;">
                    @php
                    $telephone = $app->jobSeeker->user->telephone ?? '';
                    $mobile = $app->jobSeeker->user->mobile ?? '';
                    @endphp
                    @if($telephone && $mobile)
                    {{ $telephone }} / {{ $mobile }}
                    @elseif($telephone)
                    {{ $telephone }}
                    @elseif($mobile)
                    {{ $mobile }}
                    @else
                    N/A
                    @endif
                </td>
                <td style="padding: 6px; border: 1px solid #000;">
                    @php
                    $education = $app->jobSeeker->education_level ?? '';
                    $field = $app->jobSeeker->field_of_study ?? '';
                    @endphp
                    @if($education && $field)
                    {{ $education }} - {{ $field }}
                    @elseif($education)
                    {{ $education }}
                    @elseif($field)
                    {{ $field }}
                    @else
                    N/A
                    @endif
                </td>
                <td style="padding: 6px; border: 1px solid #000;">
                    {{ $app->date_status ? date('M d, Y', strtotime($app->date_status)) : 'N/A' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding: 20px; border: 1px solid #000;">
                    No hired applicants found for the selected period.
                </td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="6" style="padding: 10px 0; font-size: 10px; text-align: center;">
                    Total Records: {{ $records->count() }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>