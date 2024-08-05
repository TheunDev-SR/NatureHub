<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary Report</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        .table-container {
            width: 90%;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="table-container">
        <h1>Summary Report</h1>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>User Counts</th>
                    <th>Article Counts</th>
                    <th>Event Counts</th>
                    <th>Campaign Counts</th>
                    <th>Total Counts</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($months as $month)
                    <tr>
                        <td>{{ $month }}</td>
                        <td>{{ $userCounts[$month] }}</td>
                        <td>{{ $articleCounts[$month] }}</td>
                        <td>{{ $eventCounts[$month] }}</td>
                        <td>{{ $campaignCounts[$month] }}</td>
                        <td>{{ $totalCounts[$month] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        @foreach ($months as $month)
            @if ($mostLikedArticlesPerMonth[$month]->isNotEmpty())
                <h2>Top 10 Liked Articles for {{ $month }}</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Likes Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mostLikedArticlesPerMonth[$month] as $article)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $article->id }}</td>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->likes_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach

        <br>

        @foreach ($months as $month)
            @if ($mostLikedCampaignsPerMonth[$month]->isNotEmpty())
                <h2>Top 10 Liked Campaigns for {{ $month }}</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Hastag</th>
                            <th>Likes Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mostLikedCampaignsPerMonth[$month] as $campaign)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $campaign->id }}</td>
                                <td>{{ $campaign->name }}</td>
                                <td>{{ $campaign->likes_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    </div>
</body>

</html>
