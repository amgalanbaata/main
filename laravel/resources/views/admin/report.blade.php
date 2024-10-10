@include('admin.head')
<html>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <body class="sb-nav-fixed">
                @include('admin.menu')
                <style>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                    }
                    .header, .footer {
                        text-align: center;
                    }
                    .content {
                        margin: 20px;
                    }

                    .form-group {
                        margin-right: 10px;
                    }

                    .form-control {
                        border-radius: 5px;
                        padding: 10px;
                        font-size: 14px;
                    }

                    .btn-primary {
                        background-color: #007bff;
                        border: none;
                        border-radius: 5px;
                        padding: 10px 20px;
                        font-size: 16px;
                        cursor: pointer;
                        transition: background-color 0.3s;
                    }

                    .btn-primary:hover {
                        background-color: #0056b3;
                    }

                    .d-flex {
                        align-items: flex-end;
                    }

                    .table-custom {
                        width: 100%;
                        margin-top: 20px;
                        border-collapse: separate;
                        color: #0056b3;
                    }

                    .table-custom th, .table-custom td {
                        border: 1px solid #ddd;
                        padding: 8px;
                    }

                    .table-custom th {
                        background-color: #f2f2f2;
                        text-align: left;
                    }
                    .reportDate {
                        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                    }
                    .row {
                        /* justify-content: space-around; */
                    }

                    .type-counts-card {
                        border-radius: 10px;
                        overflow: hidden;
                    }

                    .card-body {
                        display: flex;
                        justify-content: space-between;
                        flex-wrap: wrap;
                    }

                    .type-column {
                        flex: 1;
                        margin: 0 10px;
                    }

                    .type-section {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 10px 15px;
                        margin-bottom: 10px;
                        border-radius: 8px;
                        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                    }

                    .type-section h5 {
                        margin: 0;
                        font-size: 1.2rem;
                    }

                    .type-section h3 {
                        margin: 0;
                        font-size: 1.5rem;
                        font-weight: bold;
                    }

                    .type-section:hover {
                        transform: translateY(-3px);
                        box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.3);
                    }

                    .bg-dark h5, .bg-dark h3 {
                        color: #f8f9fa;
                    }

                    .bg-warning h5, .bg-warning h3 {
                        color: #212529;
                    }

                    .bg-danger h5, .bg-danger h3 {
                        color: #f8f9fa;
                    }

                    .bg-info h5, .bg-info h3 {
                        color: #212529;
                    }
                </style>
                @include('admin.header')
                <div class="container-fluid">
                    <h1>Тайлан</h1>
                    <div class="content">
                        <form class="row" action="{{route('admin.postsPost')}}" method="POST">
                            @csrf
                            <input type="text" name="status" id="status" style="display: none">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <h4 class="card-body">Шинээр ирсэн {{ $counts['new'] }}</h4>
                                    <button onclick="statusFunction(1)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div>
                                        <h4 class="card-body">Хүлээн авсан{{ $counts['received'] }}</h4>
                                    </div>
                                    <button onclick="statusFunction(2)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-5">
                                <div class="card bg-secondary text-white mb-4 shadow type-counts-card">
                                    <div class="card-body d-flex">
                                        <!-- Left Column -->
                                        <div class="type-column">
                                            <div class="type-section bg-dark">
                                                <h5>Бусад</h5>
                                                <h3>{{ $typeCounts['Бусад'] }}</h3>
                                            </div>
                                            <div class="type-section bg-warning text-dark">
                                                <h5>Хог хягдал</h5>
                                                <h3>{{ $typeCounts['Хог хягдал'] }}</h3>
                                            </div>
                                        </div>
                                        <!-- Right Column -->
                                        <div class="type-column">
                                            <div class="type-section bg-danger">
                                                <h5>эвдрэл доройтол</h5>
                                                <h3>{{ $typeCounts['эвдрэл доройтол'] }}</h3>
                                            </div>
                                            <div class="type-section bg-info text-dark">
                                                <h5>Бохир</h5>
                                                <h3>{{ $typeCounts['Бохир'] }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <h4 class="card-body">Шийдвэрлэсэн{{ $counts['resolved'] }}</h4>
                                    <button onclick="statusFunction(3)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div> --}}
                            {{-- <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <h4 class="card-body">Татгалзсан{{ $counts['rejected'] }}</h4>
                                    <button onclick="statusFunction(4)" class="btn">
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        Дэлгэрэнгүй
                                        <div class="small text-white"><i class="icon-angle-right"></i></div>
                                    </div>
                                    </button>
                                </div>
                            </div> --}}
                        </form>
                        <form action="{{ route('report.generate') }}" method="POST">
                            @csrf
                            <input type="text" style="display: none" id="action_type" name="action_type">
                            <div class="d-flex flex-row justify-content-between">
                              <div class="d-flex">
                                <div class="form-group">
                                  <label for="start_date">Эхлэх огноо:</label>
                                  <input type="date" name="start_date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                  <label for="end_date">Дуусах огноо:</label>
                                  <input type="date" name="end_date" class="form-control" required>
                                </div>
                              </div>
                              <div>
                                <button type="submit" id="seeReportBtn" class="btn btn-primary mt-4 mb-2" onclick="setActionType('html')">Мэдээлэл шүүх</button>
                                <button type="submit" class="btn btn-primary mt-4 mb-2" onclick="setActionType('excel')">Тайлан гаргах</button>
                              </div>
                            </div>
                        </form>
                        <div id="reportDetails">
                            <h2 class="reportDate bg-primary text-white p-3 ">Тайлан {{ $startDate ?? '' }} -> {{ $endDate ?? '' }}</h2>
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th>Статус</th>
                                        <th>Тоо</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Шинээр ирсэн</td>
                                        <td>{{ $counts['new'] ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Шийдвэрлэсэн</td>
                                        <td>{{ $counts['resolved'] ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Хүлээн авсан</td>
                                        <td>{{ $counts['received'] ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Татгалзсан</td>
                                        <td>{{ $counts['rejected'] ?? 0 }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
                <script>
                    window.onload = function() {

                    var chart = new CanvasJS.Chart("chartContainer", {
                        animationEnabled: true,
                        title: {
                            text: "Санал хүсэлтийн статус хуваарилалт"
                        },
                        data: [{
                            type: "pie",
                            startAngle: 240,
                            yValueFormatString: "##0.00\"%\"",
                            indexLabel: "{label} {y}",
                            dataPoints: [
                                {y: {{ $counts['new'] ?? 0 }}, label: "Шинээр ирсэн"},
                                {y: {{ $counts['received'] ?? 0 }}, label: "Хүлээн авсан"},
                                {y: {{ $counts['resolved'] ?? 0 }}, label: "Шийдвэрлэсэн"},
                                {y: {{ $counts['rejected'] ?? 0 }}, label: "Татгалзсан"},
                                // {y: 1.26, label: "Others"}
                            ]
                        }]
                    });
                    chart.render();

                    }
                    </script>
                <script>
                    function statusFunction(status) {
                        document.getElementById('status').value = status;
                        document.querySelector('form.row').submit();
                    }
                    function setActionType(actionType) {
                        document.getElementById('action_type').value = actionType;
                    }
                </script>
            </body>
        </div>
    </div>
</html>
