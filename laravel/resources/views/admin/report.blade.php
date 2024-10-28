@include('admin.head')
<html>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <body class="sb-nav-fixed">
                @include('admin.menu')
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
                        font-family: sans-serif;
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
                        <div>
                            <h2 class="reportDate bg-primary text-white p-3 ">Тайлан {{ $startDate ?? '' }} -> {{ $endDate ?? '' }}</h2>
                            <table class="table-custom" id="reportTable">
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
                <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                {{-- circle graphics --}}
                <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
                <script>
                    window.onload = function() {
                    var chart = new CanvasJS.Chart("chartContainer", {
                        animationEnabled: true,
                        title: {
                            text: "Мэдэгдэлийн статус хуваарилалт"
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
                {{-- report to excel --}}
                <script>
                    function exportTableToExcel() {
                        // Get the report date text
                        var reportDate = document.querySelector('.reportDate').innerText;

                        // Create the Excel-compatible HTML structure with the report date as the first row
                        var tab_text = "<table border='2px' style='border-collapse:collapse; width: 40%;'>";
                        tab_text += "<tr><th colspan='2' style='background-color: #007BFF; color: white; padding: 10px; font-size: 18px;'>" + reportDate + "</th></tr>";

                        // Get the table content
                        var tab = document.getElementById('reportTable');

                        // Add padding and style adjustments for the table rows
                        for (var j = 0; j < tab.rows.length; j++) {
                            tab_text += "<tr>";
                            for (var i = 0; i < tab.rows[j].cells.length; i++) {
                                tab_text += "<td style='padding: 8px; font-size: 14px; border: 1px solid #000;'>" + tab.rows[j].cells[i].innerHTML + "</td>";
                            }
                            tab_text += "</tr>";
                        }

                        tab_text += "</table>";

                        // Remove links, images, and input fields from the exported content
                        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");
                        tab_text = tab_text.replace(/<img[^>]*>/gi, "");
                        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, "");

                        var msie = window.navigator.userAgent.indexOf("MSIE ");
                        var sa;

                        // Check if the browser is Internet Explorer
                        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                            txtArea1.document.open("txt/html", "replace");
                            txtArea1.document.write(tab_text);
                            txtArea1.document.close();
                            txtArea1.focus();
                            sa = txtArea1.document.execCommand("SaveAs", true, "Report.xls");
                        } else {
                            // For other browsers
                            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
                        }

                        return sa;
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
