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

                    /* report */
                    .report-container {
                        width: 100%;
                        margin-top: 20px;
                    }

                    .reportDate {
                        font-size: 1.5rem;
                        font-weight: bold;
                        text-align: center;
                        margin-bottom: 10px;
                        padding: 10px;
                        border-radius: 5px;
                    }

                    .table-custom {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 10px;
                    }

                    .table-custom th, .table-custom td {
                        padding: 12px 15px;
                        text-align: left;
                        border-bottom: 1px solid #ddd;
                    }

                    .table-custom thead th {
                        background-color: #007bff;
                        color: #ffffff;
                        font-size: 1.1rem;
                        text-transform: uppercase;
                        border-bottom: 2px solid #004d99;
                    }

                    .table-custom tbody tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }

                    .table-custom tbody tr:hover {
                        background-color: #f1f1f1;
                    }

                    .report-note {
                        font-size: 0.9rem;
                        color: #666;
                        text-align: center;
                        margin-top: 10px;
                    }
                </style>
                @include('admin.header')
                <div class="container-fluid">
                    <h1>Тайлан</h1>
                    <div class="content">
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
                                <button type="submit" class="btn btn-primary mt-4 mb-2" onclick="exportTableToExcel()">Тайлан гаргах</button>
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
                                        <td>Давхардсан</td>
                                        <td>{{ $counts['Duplicated'] ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Нэмэлт мэдээлэл шаардлагатай</td>
                                        <td>{{ $counts['Additional information is required'] ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Татгалзсан</td>
                                        <td>{{ $counts['Refused'] ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Хөрсний шинжилгээ хийх</td>
                                        <td>{{ $counts['Conduct_soil_analysis'] ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Байршилд шууд бүртгэх</td>
                                        <td>{{ $counts['Register_directly_on_location'] ?? 0 }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="reportTable" style="display: none;" class="report-container">
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
                                <td>Давхардсан</td>
                                <td>{{ $counts['Duplicated'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>Нэмэлт мэдээлэл шаардлагатай</td>
                                <td>{{ $counts['Additional information is required'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>Татгалзсан</td>
                                <td>{{ $counts['Refused'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>Хөрсний шинжилгээ хийх</td>
                                <td>{{ $counts['Conduct_soil_analysis'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>Байршилд шууд бүртгэх</td>
                                <td>{{ $counts['Register_directly_on_location'] ?? 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="report-note">
                        <p>Энэ тайлан нь {{ $startDate ?? 'эхлэх огноо' }} -аас {{ $endDate ?? 'дуусах огноо' }} хүртэлх мэдэгдлүүдийн статусын хуваарилалтыг харуулж байна.</p>
                    </div>
                </div>
                <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

                <script>
                    function exportTableToExcel() {
                        // Get the data from the table
                        let table = document.getElementById('reportTable');
                        let workbook = XLSX.utils.book_new();
                        let worksheet = XLSX.utils.table_to_sheet(table);

                        // Optional: Customize header style by adding a title row
                        XLSX.utils.sheet_add_aoa(worksheet, [["Тайлан - Статусын Тоо Хуваарилалт"]], { origin: "A1" });
                        XLSX.utils.sheet_add_aoa(worksheet, [[`Хугацаа: ${document.querySelector('.reportDate').innerText}`]], { origin: "A2" });

                        // Move the table data to start from the third row to make room for the title and date
                        worksheet['!ref'] = XLSX.utils.encode_range({
                            s: { r: 2, c: 0 },
                            e: XLSX.utils.decode_range(worksheet['!ref']).e
                        });

                        // Add custom styles
                        worksheet['A1'].s = { fill: { fgColor: { rgb: "007BFF" } }, font: { color: { rgb: "FFFFFF" }, bold: true } };
                        worksheet['B1'].s = { fill: { fgColor: { rgb: "007BFF" } }, font: { color: { rgb: "FFFFFF" }, bold: true } };

                        // Apply general styling across cells if needed
                        for (let cell in worksheet) {
                            if (cell[0] === "!") continue; // skip metadata keys
                            worksheet[cell].s = { font: { name: "Arial", sz: 12 } };
                        }

                        XLSX.utils.book_append_sheet(workbook, worksheet, "Тайлан");
                        XLSX.writeFile(workbook, "Тайлан_Статусын_Тооллого.xlsx");
                    }

                    // circle graphics
                    window.onload = function() {

                    var chart = new CanvasJS.Chart("chartContainer", {
                        animationEnabled: true,
                        title: {
                            text: "Мэдэгдлүүдийн статус хуваарилалт"
                        },
                        data: [{
                            type: "pie",
                            startAngle: 240,
                            yValueFormatString: "##0.00\"%\"",
                            indexLabel: "{label} {y}",
                            dataPoints: [
                                {y: {{ $counts['new'] ?? 0 }}, label: "Шинээр ирсэн"},
                                {y: {{ $counts['Duplicated'] ?? 0 }}, label: "Давхардсан"},
                                {y: {{ $counts['Additional information is required'] ?? 0 }}, label: "Нэмэлт мэдээлэл шаардлагатай"},
                                {y: {{ $counts['Refused'] ?? 0 }}, label: "Татгалзсан"},
                                {y: {{ $counts['Conduct_soil_analysis'] ?? 0 }}, label: "Хөрсний шинжилгээ хийх"},
                                {y: {{ $counts['Register_directly_on_location'] ?? 0 }}, label: "Байршилд шууд бүртгэх"},
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
