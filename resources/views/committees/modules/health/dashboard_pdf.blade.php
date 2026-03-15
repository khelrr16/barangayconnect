<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Dashboard Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
        }

        h1, h2 {
            margin: 0;
        }

        .meta {
            margin: 6px 0 16px;
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Health Dashboard Report</h1>
    <div class="meta">Period: {{ $periodLabel }}</div>

    <h2>Overall Medicines Used</h2>
    <table>
        <thead>
            <tr>
                <th>Medicine</th>
                <th class="text-center">Immunization</th>
                <th class="text-center">Direct Infant Entries</th>
                <th class="text-center">Overall Used</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicineUsage as $row)
                <tr>
                    <td>{{ $row['medicine_name'] }}</td>
                    <td class="text-center">{{ $row['immunization_total'] }}</td>
                    <td class="text-center">{{ $row['direct_total'] }}</td>
                    <td class="text-center">{{ $row['overall_total'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No medicine records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Monitoring Status Per Infant</h2>
    <table>
        <thead>
            <tr>
                <th>Infant</th>
                <th>Status</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monitoringInfants as $infant)
                <tr>
                    <td>{{ $infant->name }}</td>
                    <td>{{ $infant->status }}</td>
                    <td>{{ optional($infant->updated_at)->format('M d, Y h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No monitoring records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Nutritional Status Per Infant (Latest in Period)</h2>
    <table>
        <thead>
            <tr>
                <th>Infant</th>
                <th>Category</th>
                <th>Status</th>
                <th>Assessment Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($latestNutritionalByInfant as $record)
                <tr>
                    <td>{{ $record->infant_name }}</td>
                    <td>{{ str_replace('_', ' ', (string) $record->category) }}</td>
                    <td>{{ $record->status ?: '-' }}</td>
                    <td>{{ optional($record->assessment_date)->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No nutritional records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
